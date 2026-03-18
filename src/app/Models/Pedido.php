<?php

namespace App\Models;

use App\Helpers\UtilsNormalizarNumero;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    protected $fillable = ['numero', 'data_pedido', 'cliente', 'fornecedor', 'valor', 'texto_bruto'];

    protected $casts = [
        'valor' => 'decimal:2',
        'data_pedido' => 'date',
    ];

    public function scopeSearch($query, array $filtros = [])
    {
        $query->with('itens')
            ->orderBy('id', 'desc');

        if (!empty($filtros['numero']) && is_scalar($filtros['numero'])) {
            $query->where('numero', 'like', '%' . trim((string)$filtros['numero']) . '%');
        }

        if (!empty($filtros['cliente']) && is_scalar($filtros['cliente'])) {
            $query->where('cliente', 'like', '%' . trim((string)$filtros['cliente']) . '%');
        }

        if (!empty($filtros['fornecedor']) && is_scalar($filtros['fornecedor'])) {
            $query->where('fornecedor', 'like', '%' . trim((string)$filtros['fornecedor']) . '%');
        }

        if (!empty($filtros['data_inicio'])) {
            $query->whereDate('data_pedido', '>=', $filtros['data_inicio']);
        }

        if (!empty($filtros['data_fim'])) {
            $query->whereDate('data_pedido', '<=', $filtros['data_fim']);
        }

        $valorMin = isset($filtros['valor_min']) && is_scalar($filtros['valor_min'])
            ? UtilsNormalizarNumero::normalizarNumero((string)$filtros['valor_min'])
            : null;

        if ($valorMin !== null) {
            $query->where('valor', '>=', $valorMin);
        }

        $valorMax = isset($filtros['valor_max']) && is_scalar($filtros['valor_max'])
            ? UtilsNormalizarNumero::normalizarNumero((string)$filtros['valor_max'])
            : null;

        if ($valorMax !== null) {
            $query->where('valor', '<=', $valorMax);
        }

        return $query;
    }

    public function itens(): HasMany
    {
        return $this->hasMany(PedidoItem::class, 'pedido_id');
    }
}
