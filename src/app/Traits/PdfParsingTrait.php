<?php

namespace App\Traits;

use App\Helpers\UtilsNormalizarNumero;

trait PdfParsingTrait
{
    private function extrairNumero(string $texto): ?string
    {
        preg_match('/Pedido de Compra nº(\d+)/i', $texto, $m);
        return isset($m[1]) ? trim($m[1]) : null;
    }

    private function extrairData(string $texto): ?string
    {
        preg_match('/Data\s+do\s+pedido\s*:\s*(\d{2})\.(\d{2})\.(\d{4})/i', $texto, $d);
        return (isset($d[1]) && isset($d[2]) && isset($d[3])) ? "{$d[3]}-{$d[2]}-{$d[1]}" : null;
    }

    private function extrairCliente(string $texto): ?string
    {
        preg_match('/Cliente:\s*(.+?)(?:\n|$)/i', $texto, $cliente);
        if (empty($cliente[1])) {
            preg_match('/Faturamento\s*\n\s*(.+?)(?:\n|Rua|CNPJ)/is', $texto, $cliente);
        }
        return isset($cliente[1]) ? trim(preg_replace('/\s+/', ' ', $cliente[1])) : null;
    }

    private function extrairValorTotal(string $texto): ?string
    {
        preg_match('/Vlr\s+Total\s+do\s+Pedido\s*:\s*([\d\.,]+)/i', $texto, $valor);
        if (empty($valor[1])) {
            preg_match('/Total:\s*R?\$?\s*([\d\.,]+)/i', $texto, $valor);
        }
        return isset($valor[1]) ? UtilsNormalizarNumero::normalizarNumero($valor[1]) : null;
    }

    private function extrairFornecedor(string $texto): ?string
    {
        if (!preg_match('/Dados\s+do\s+Fornecedor\s*\n+\s*([^\n]+)/i', $texto, $m)) {
            return null;
        }
        $linha = trim($m[1]);
        // Remover endereço se vier na mesma linha (RUA, número, bairro, CEP)
        if (preg_match('/^(.+?)\s+RUA\s+/i', $linha, $nome)) {
            return trim($nome[1]);
        }
        return $linha ?: null;
    }

    private function extrairItensDoTexto(string $texto): array
    {
        $linhas = preg_split('/\r\n|\r|\n/', $texto);
        $headerEncontrado = false;
        $itemLines = [];  // [{ item, material, denominacao }, ...]
        $valueLines = []; // [{ qtd, un, preco, vlr_tot, icms, ipi }, ...]

        foreach ($linhas as $linha) {
            $linha = trim($linha);
            if ($linha === '') {
                continue;
            }

            // Cabeçalho: "ItemMaterialDenominação" ou "Item Material Denominação"
            if (preg_match('/Item\s*Material\s*Denom/i', $linha) || preg_match('/ItemMaterialDenom/i', $linha)) {
                $headerEncontrado = true;
                continue;
            }

            if (!$headerEncontrado) {
                continue;
            }

            // Parar em totais
            if (preg_match('/^TOTAIS\s*:/i', $linha) || preg_match('/^Vlr\s+Total\s+do\s+Pedido/i', $linha)) {
                break;
            }

            // Linha de valores: "1 UR 10.778,46 10.778,46  0,00 %  0,00 %"
            if (preg_match('/^\s*(\d+)\s+([A-Z]{2,})\s+([\d\.,]+)\s+([\d\.,]+)(?:\s+([\d\.,]+)\s*%?)?(?:\s+([\d\.,]+)\s*%?)?/i', $linha, $v)) {
                $valueLines[] = [
                    'qtd' => $v[1],
                    'un' => $v[2],
                    'preco' => UtilsNormalizarNumero::normalizarNumero($v[3]),
                    'vlr_tot' => UtilsNormalizarNumero::normalizarNumero($v[4]),
                    'icms' => UtilsNormalizarNumero::normalizarNumero($v[5] ?? null),
                    'ipi' => UtilsNormalizarNumero::normalizarNumero($v[6] ?? null),
                ];
                continue;
            }

            // Linha de item + material/denom: "00010	SERV MANUT MAQUINAS E EQUP" ou "00010  SERV MANUT..."
            if (preg_match('/^(\d{4,})\s+(.+)$/', $linha, $m) && !preg_match('/^\d+\s+[A-Z]{2,}\s+[\d\.,]/', $linha)) {
                $resto = trim($m[2]);
                if (strlen($resto) > 2 && !preg_match('/^(Total|Subtotal|Dt\.|Tipo\s+de|Local\s+da|Base\s+de)/i', $resto)) {
                    $itemLines[] = [
                        'item' => $m[1],
                        'material' => $resto,
                        'denominacao' => $resto,
                    ];
                }
            }
        }

        // Montar itens: emparelhar por ordem (cada value line = 1 item; usar item line correspondente se houver)
        $itens = [];
        foreach ($valueLines as $i => $val) {
            $itemLine = $itemLines[$i] ?? null;
            $itens[] = [
                'item' => $itemLine['item'] ?? (string)($i + 1),
                'material' => $itemLine['material'] ?? null,
                'denominacao' => $itemLine['denominacao'] ?? null,
                'qtd' => $val['qtd'],
                'un' => $val['un'],
                'preco' => $val['preco'],
                'vlr_tot' => $val['vlr_tot'],
                'icms' => $val['icms'],
                'ipi' => $val['ipi'],
            ];
        }

        // Fallback: se não achou linhas de valor, tentar parsing por colunas (espaços múltiplos)
        if (empty($itens)) {
            foreach ($linhas as $linha) {
                $linha = trim($linha);
                if (preg_match('/Item\s+Material\s+Denom/i', $linha)) {
                    continue;
                }
                if (preg_match('/^TOTAIS|^Vlr\s+Total/i', $linha)) {
                    break;
                }
                $row = $this->parsearLinhaItemColunas($linha);
                if ($row !== null) {
                    $itens[] = $row;
                }
            }
        }

        return $itens;
    }

    /**
     * Parseia linha com colunas separadas por 2+ espaços (formato alternativo).
     */
    private function parsearLinhaItemColunas(string $linha): ?array
    {
        $partes = preg_split('/\s{2,}/', $linha, -1, PREG_SPLIT_NO_EMPTY);
        $n = count($partes);
        if ($n < 7) {
            return null;
        }
        $qtd = UtilsNormalizarNumero::normalizarNumero($partes[$n - 6] ?? null);
        $un = $partes[$n - 5] ?? null;
        $preco = UtilsNormalizarNumero::normalizarNumero($partes[$n - 4] ?? null);
        $vlrTot = UtilsNormalizarNumero::normalizarNumero($partes[$n - 3] ?? null);
        $icms = UtilsNormalizarNumero::normalizarNumero($partes[$n - 2] ?? null);
        $ipi = UtilsNormalizarNumero::normalizarNumero($partes[$n - 1] ?? null);
        $denomPartes = array_slice($partes, 2, $n - 6);
        $denominacao = $denomPartes !== [] ? implode(' ', $denomPartes) : null;
        return [
            'item' => $partes[0] ?? null,
            'material' => $partes[1] ?? null,
            'denominacao' => $denominacao ?: null,
            'qtd' => $qtd,
            'un' => $un,
            'preco' => $preco,
            'vlr_tot' => $vlrTot,
            'icms' => $icms,
            'ipi' => $ipi,
        ];
    }

}
