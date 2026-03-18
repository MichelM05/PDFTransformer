<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PedidoItem extends Model
{
    protected $table = 'pedido_itens';

    protected $fillable = [
        'pedido_id',
        'item',
        'material',
        'denominacao',
        'qtd',
        'un',
        'preco',
        'vlr_tot',
        'icms',
        'ipi',
    ];

    protected $casts = [
        'qtd' => 'decimal:4',
        'preco' => 'decimal:4',
        'vlr_tot' => 'decimal:4',
        'icms' => 'decimal:4',
        'ipi' => 'decimal:4',
    ];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }
}
