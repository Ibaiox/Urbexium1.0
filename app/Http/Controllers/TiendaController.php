<?php
// ============================================================
// app/Http/Controllers/TiendaController.php
// ============================================================
namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Pedido;
use App\Models\PedidoItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TiendaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /** Listado de productos con filtro y paginación */
    public function index(Request $request)
    {
        $query = Producto::where('activo', true);

        if ($request->filled('q')) {
            $query->where('nombre', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('categoria') && $request->categoria !== 'todos') {
            $query->where('categoria', $request->categoria);
        }

        $productos = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('tienda.index', compact('productos'));
    }

    /** Formulario crear producto (solo admin) */
    public function create()
    {
        $this->authorize('admin');
        return view('tienda.create');
    }

    /** Guardar nuevo producto */
    public function store(Request $request)
    {
        $this->authorize('admin');

        $data = $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio'      => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'categoria'   => 'required|in:equipo,ropa,seguridad,accesorios',
            'imagen'      => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        Producto::create($data);

        return redirect()->route('tienda.index')->with('success', 'Producto añadido correctamente.');
    }

    /** Formulario editar producto (solo admin) */
    public function edit(Producto $producto)
    {
        $this->authorize('admin');
        return view('tienda.edit', compact('producto'));
    }

    /** Actualizar producto */
    public function update(Request $request, Producto $producto)
    {
        $this->authorize('admin');

        $data = $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio'      => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'categoria'   => 'required|in:equipo,ropa,seguridad,accesorios',
            'imagen'      => 'nullable|image|max:2048',
            'activo'      => 'boolean',
        ]);

        if ($request->hasFile('imagen')) {
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($data);

        return redirect()->route('tienda.index')->with('success', 'Producto actualizado correctamente.');
    }

    /** Eliminar producto (solo admin) */
    public function destroy(Producto $producto)
    {
        $this->authorize('admin');

        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }
        $producto->delete();

        return redirect()->route('tienda.index')->with('success', 'Producto eliminado.');
    }

    /** Crear pedido desde el carrito (datos enviados por JS) */
    public function checkout(Request $request)
    {
        $request->validate([
            'items'            => 'required|array|min:1',
            'items.*.id'       => 'required|exists:productos,id',
            'items.*.qty'      => 'required|integer|min:1',
            'direccion_envio'  => 'nullable|string|max:500',
        ]);

        $total = 0;
        $pedido = Pedido::create([
            'user_id'        => Auth::id(),
            'total'          => 0,
            'estado'         => 'pendiente',
            'direccion_envio'=> $request->direccion_envio,
        ]);

        foreach ($request->items as $item) {
            $producto = Producto::findOrFail($item['id']);
            $subtotal = $producto->precio * $item['qty'];
            $total += $subtotal;

            PedidoItem::create([
                'pedido_id'      => $pedido->id,
                'producto_id'    => $producto->id,
                'cantidad'       => $item['qty'],
                'precio_unitario'=> $producto->precio,
            ]);

            // Descontar stock
            $producto->decrement('stock', $item['qty']);
        }

        $pedido->update(['total' => $total]);

        return response()->json(['success' => true, 'pedido_id' => $pedido->id]);
    }

    /** Ver detalle de un pedido */
    public function showPedido(Pedido $pedido)
    {
        $this->authorize('view', $pedido);
        $pedido->load('items.producto');
        return view('tienda.pedido', compact('pedido'));
    }
}
