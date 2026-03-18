<?php

namespace App\Services;

use App\Models\Pedido;
use App\Traits\PdfParsingTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Smalot\PdfParser\Parser;

class PedidoUploadService
{
    use PdfParsingTrait;

    /**
     * @throws Exception
     */
    public function processarUpload($arquivo)
    {
        $texto = (new Parser())->parseFile($arquivo->getRealPath())->getText();
        $arquivo->store('pdfs');

        $dados = [
            'numero'      => $this->extrairNumero($texto),
            'data_pedido' => $this->extrairData($texto),
            'cliente'     => $this->extrairCliente($texto),
            'fornecedor'  => $this->extrairFornecedor($texto),
            'valor'       => $this->extrairValorTotal($texto),
            'texto_bruto' => $texto,
        ];

        return DB::transaction(function () use ($dados, $texto) {
            $pedido = Pedido::create($dados);
            $itens = $this->extrairItensDoTexto($texto);
            $pedido->itens()->createMany($itens);

            return $pedido;
        });
    }
}
