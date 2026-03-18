@extends('layouts.app')

@section('title', 'Criar Pedido ')

{{-- Carregamos o CSS e JS específicos para esta tela, bem separados --}}
@push('styles')
    @vite(['resources/css/pedidos/create.css'])
@endpush

@push('scripts')
    @vite(['resources/js/pedidos/create.js'])
@endpush

@section('content')
    <div class="card shadow-lg">
        <h1 class="text-2xl font-bold mb-6">Criar Pedido Manual</h1>

        <form action="{{ route('pedidos.save') }}" method="POST">
            @csrf

            <div class="card bg-gray-50 p-6 border mb-8">
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="numero" class="font-bold">Número do Pedido</label>
                        <input type="text" name="numero" id="numero" class="form-control" value="{{ old('numero') }}" placeholder="Ex: 123456">
                    </div>
                    <div class="form-group">
                        <label for="data_pedido" class="font-bold">Data do Pedido</label>
                        <input type="date" name="data_pedido" id="data_pedido" class="form-control" value="{{ old('data_pedido') }}">
                    </div>
                    <div class="form-group">
                        <label for="cliente" class="font-bold">Cliente</label>
                        <input type="text" name="cliente" id="cliente" class="form-control" value="{{ old('cliente') }}" placeholder="Nome do comprador">
                    </div>
                    <div class="form-group">
                        <label for="fornecedor" class="font-bold">Fornecedor</label>
                        <input type="text" name="fornecedor" id="fornecedor" class="form-control" value="{{ old('fornecedor') }}" placeholder="Nome da empresa vendedora">
                    </div>
                    <div class="form-group">
                        <label for="valor" class="font-bold">Valor Total (R$)</label>
                        <input type="number" step="0.01" name="valor" id="valor" class="form-control" value="{{ old('valor') }}" placeholder="0,00">
                    </div>
                </div>
            </div>

            <!-- Seção de Itens -->
            <div class="mt-8">


                <div id="itens-container">
                    {{-- Template de Item --}}
                    <div class="item-form-card card mb-4 p-4 border" style="background: white">
                        <div class="item-header">
                            <div class="font-bold"> Itens do Pedido </div>
                            <div>
                                <button type="button" class="btn-toggle-item" title="Minimizar/Expandir">-</button>
                                <button type="button" class="btn-remove-item" title="Remover Item">&times;</button>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="text-xs font-bold uppercase">Item</label>
                                <input type="text" name="itens[0][item]" class="form-control" placeholder="10">
                            </div>
                            <div class="form-group">
                                <label class="text-xs font-bold uppercase">Material</label>
                                <input type="text" name="itens[0][material]" class="form-control" placeholder="Cód. Material">
                            </div>
                            <div class="form-group">
                                <label class="text-xs font-bold uppercase">Denominação</label>
                                <input type="text" name="itens[0][denominacao]" class="form-control" placeholder="Descrição">
                            </div>
                            <div class="form-group">
                                <label class="text-xs font-bold uppercase">Quantidade</label>
                                <input type="number" step="0.0001" name="itens[0][qtd]" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="text-xs font-bold uppercase">Unidade</label>
                                <input type="text" name="itens[0][un]" class="form-control" placeholder="UN">
                            </div>
                            <div class="form-group">
                                <label class="text-xs font-bold uppercase">Preço Unit.</label>
                                <input type="number" step="0.0001" name="itens[0][preco]" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="text-xs font-bold uppercase">Valor Total</label>
                                <input type="number" step="0.0001" name="itens[0][vlr_tot]" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="text-xs font-bold uppercase">ICMS (%)</label>
                                <input type="number" step="0.0001" name="itens[0][icms]" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="text-xs font-bold uppercase">IPI (%)</label>
                                <input type="number" step="0.0001" name="itens[0][ipi]" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <button type="button" id="add-item" class="btn btn-sm" style="background-color: #10b981;">
                    + Adicionar Item
                </button>
                <button type="submit" class="btn btn-primary px-8">Salvar Lançamento</button>
                <a href="{{ route('pedidos.index') }}" class="btn-back">Cancelar</a>

        </form>
    </div>
@endsection
