<div class="pedido-card">

    <div class="pedido-header">

        <div class="row">
            <div class="col-4">
                <strong>Informações do Pedido nº </strong>
                {{ $pedido->numero ?? '—' }}
            </div>

            @if($pedido->data_pedido)
                <div class="col-4">
                    <strong>Data: </strong>
                    {{ $pedido->data_pedido->format('d/m/Y') }}
                </div>
            @endif
        </div>

        <div class="row">
            <div class="col-4">
                <strong>Cliente: </strong>
                {{ $pedido->cliente ? $pedido->cliente : '' }}
            </div>

            <div class="col-4">
                <strong>Quantidade de itens: </strong>
                {{ $pedido->itens->count() }}
            </div>
        </div>

        <div class="row">
            <div class="col-4">
                <strong>Fornecedor: </strong>
                {{ $pedido->fornecedor ? $pedido->fornecedor : '' }}
            </div>

            <div class="col-4">
                <strong>Total: </strong>
                {{ $pedido->valor ? 'R$ ' . number_format((float) $pedido->valor, 2, ',', '.') : '—' }}
            </div>
        </div>

    </div>

    @if($pedido->itens->isEmpty())
        <p class="empty small">Nenhum item extraído deste PDF.</p>
    @else
        <div class="itens-grid">
            @foreach($pedido->itens as $item)
                <div class="item-card">

                    <div class="item-campo">
                        <span class="label">Item</span>
                        {{ $item->item ?? '—' }}
                    </div>

                    <div class="item-campo">
                        <span class="label">Material</span>
                        {{ $item->material ?? '—' }}
                    </div>

                    <div class="item-campo full">
                        <span class="label">Denominação</span>
                        {{ $item->denominacao ?? '—' }}
                    </div>

                    <div class="item-campo">
                        <span class="label">Qtd.</span>
                        {{ $item->qtd ?? '—' }}
                    </div>

                    <div class="item-campo">
                        <span class="label">Un.</span>
                        {{ $item->un ?? '—' }}
                    </div>

                    <div class="item-campo">
                        <span class="label">Preço</span>
                        {{ $item->preco !== null ? number_format((float) $item->preco, 4, ',', '.') : '—' }}
                    </div>

                    <div class="item-campo">
                        <span class="label">Vlr Tot.</span>
                        {{ $item->vlr_tot !== null ? number_format((float) $item->vlr_tot, 4, ',', '.') : '—' }}
                    </div>

                    <div class="item-campo">
                        <span class="label">ICMS (%)</span>
                        {{ $item->icms !== null ? number_format((float) $item->icms, 4, ',', '.') : '—' }}
                    </div>

                    <div class="item-campo">
                        <span class="label">IPI (%)</span>
                        {{ $item->ipi !== null ? number_format((float) $item->ipi, 4, ',', '.') : '—' }}
                    </div>

                </div>
            @endforeach
        </div>
    @endif

</div>
