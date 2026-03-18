<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchPedidoRequest;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Helpers\UtilsNormalizarNumero;
use App\Services\PedidoUploadService;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;

class PedidoController extends Controller
{
    public function index(SearchPedidoRequest $request)
    {
        $pedidos = Pedido::search($request->validated())->paginate(15);
        $filtros = $request->validated();
        return view('pedidos.index', compact('pedidos', 'filtros'));
    }

    public function show(Pedido $pedido)
    {
        $pedido->load('itens');
        return view('pedidos.show', compact('pedido'));
    }

    public function create()
    {
        return view('pedidos.create');
    }

    public function save(Request $request)
    {
        // Aqui virá a lógica de salvar que vamos aprender depois
        return "Salvando o pedido...";
    }

    public function upload(Request $request, PedidoUploadService $service)
    {
        $request->validate(['pdf' => 'required|mimes:pdf']);

        try {
            $pedido = $service->processarUpload($request->file('pdf'));
            return redirect()->route('pedidos.show', $pedido)->with('success', 'Pedido importado!');
        } catch (\Exception $e) {
            return back()->withErrors(['pdf' => 'Erro ao processar: ' . $e->getMessage()]);
        }
    }

    public function delete(Pedido $pedido)
    {
        $pedido->delete();

        // Redireciona de volta com uma mensagem de sucesso
        return redirect()->route('pedidos.index')->with('success', 'Pedido excluído com sucesso!');
    }
}
