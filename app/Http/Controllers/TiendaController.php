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
use App\Mail\PedidoConfirmadoMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TiendaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ── Catálogo ──────────────────────────────────────────────────────────

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

    public function show(Producto $producto)
    {
        abort_if(! $producto->activo && ! Auth::user()->isAdmin(), 404);
        $relacionados = Producto::where('activo', true)
            ->where('categoria', $producto->categoria)
            ->where('id', '!=', $producto->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();
        return view('tienda.show', compact('producto', 'relacionados'));
    }

    // ── CRUD productos (admin) ────────────────────────────────────────────

    public function create()
    {
        abort_unless(Auth::user()->isAdmin(), 403);
        return view('tienda.create');
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

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

    public function edit(Producto $producto)
    {
        abort_unless(Auth::user()->isAdmin(), 403);
        return view('tienda.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $data = $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio'      => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'categoria'   => 'required|in:equipo,ropa,seguridad,accesorios',
            'imagen'      => 'nullable|image|max:2048',
            'activo'      => 'nullable|boolean',
        ]);

        if ($request->hasFile('imagen')) {
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $data['activo'] = $request->boolean('activo');
        $producto->update($data);

        return redirect()->route('tienda.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }
        $producto->delete();

        return redirect()->route('tienda.index')->with('success', 'Producto eliminado.');
    }

    // ── Checkout ──────────────────────────────────────────────────────────

    public function checkoutView()
    {
        return view('tienda.checkout');
    }

    // ── Stripe: crear PaymentIntent ───────────────────────────────────────

    /**
     * Crea un PaymentIntent en Stripe y devuelve el client_secret al frontend.
     * El carrito viene del localStorage (enviado como JSON desde JS).
     */
    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'items'           => 'required|array|min:1',
            'items.*.id'      => 'required|exists:productos,id',
            'items.*.qty'     => 'required|integer|min:1',
            'direccion_envio' => 'nullable|string|max:500',
        ]);

        // Calcular total en servidor (nunca confiar en el frontend)
        $total      = 0;
        $itemsData  = [];

        foreach ($request->items as $item) {
            $producto = Producto::findOrFail($item['id']);
            abort_if(! $producto->activo, 422, 'Producto no disponible: ' . $producto->nombre);
            abort_if($producto->stock < $item['qty'], 422, "Stock insuficiente para {$producto->nombre}.");
            $total += $producto->precio * $item['qty'];
            $itemsData[] = ['producto' => $producto, 'qty' => $item['qty']];
        }

        // Stripe
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $intent = \Stripe\PaymentIntent::create([
            'amount'   => (int) round($total * 100), // céntimos
            'currency' => 'eur',
            'metadata' => [
                'user_id' => Auth::id(),
            ],
        ]);

        // Guardar pedido en estado "pendiente" con el intent id
        $pedido = Pedido::create([
            'user_id'               => Auth::id(),
            'total'                 => $total,
            'estado'                => 'pendiente',
            'direccion_envio'       => $request->direccion_envio,
            'metodo_pago'           => 'stripe',
            'stripe_payment_intent' => $intent->id,
        ]);

        foreach ($itemsData as $itemData) {
            PedidoItem::create([
                'pedido_id'       => $pedido->id,
                'producto_id'     => $itemData['producto']->id,
                'cantidad'        => $itemData['qty'],
                'precio_unitario' => $itemData['producto']->precio,
            ]);
            $itemData['producto']->decrement('stock', $itemData['qty']);
        }

        return response()->json([
            'client_secret' => $intent->client_secret,
            'pedido_id'     => $pedido->id,
        ]);
    }
  /** Cambio de estado en lote de pedidos */
    public function bulkEstadoPedidos(Request $request)
    {
        $request->validate([
            'pedidos'       => 'required|array|min:1',
            'pedidos.*'     => 'integer|exists:pedidos,id',
            'nuevo_estado'  => 'required|in:pendiente,procesando,enviado,entregado,cancelado',
        ]);

        $count = \App\Models\Pedido::whereIn('id', $request->pedidos)
                    ->update(['estado' => $request->nuevo_estado]);

        return back()->with('success', "{$count} pedido(s) actualizados a «{$request->nuevo_estado}».");
    }


    /**
     * Webhook de Stripe — confirma el pago y cambia estado del pedido.
     * Registrar en Stripe Dashboard: POST /stripe/webhook
     */
    public function stripeWebhook(Request $request)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $request->getContent(),
                $request->header('Stripe-Signature'),
                $webhookSecret
            );
        } catch (\Exception $e) {
            return response('Webhook error: ' . $e->getMessage(), 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $intentId = $event->data->object->id;
            Pedido::where('stripe_payment_intent', $intentId)
                  ->update(['estado' => 'procesando']);
        }

        if ($event->type === 'payment_intent.payment_failed') {
            $intentId = $event->data->object->id;
            $pedido   = Pedido::where('stripe_payment_intent', $intentId)->first();
            if ($pedido && $pedido->estado === 'pendiente') {
                foreach ($pedido->items as $item) {
                    $item->producto->increment('stock', $item->cantidad);
                }
                $pedido->update(['estado' => 'cancelado']);
            }
        }

        return response('OK', 200);
    }

    /**
     * Página de pago exitoso — Stripe redirige aquí con payment_intent en la URL.
     * También acepta pedido_id pasado directamente.
     */
   public function pagoExitoso(Request $request)
{
    if ($request->filled('payment_intent')) {
        $pedido = Pedido::where('stripe_payment_intent', $request->payment_intent)
                        ->where('user_id', Auth::id())
                        ->firstOrFail();
    } elseif ($request->filled('pedido_id')) {
        $pedido = Pedido::where('id', $request->pedido_id)
                        ->where('user_id', Auth::id())
                        ->firstOrFail();
    } else {
        return redirect()->route('tienda.index');
    }

    $pedido->load('items.producto');

    if (!$pedido->email_confirmacion_enviado) {
        try {
            $user = Auth::user();

            Mail::to($user->email)->send(new PedidoConfirmadoMail($user, $pedido));

            $pedido->update([
                'email_confirmacion_enviado' => true,
            ]);

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Email pedido fallido: ' . $e->getMessage());
        }
    }

    return view('tienda.pago-exitoso', compact('pedido'));
}

    // ── Pedidos del usuario ───────────────────────────────────────────────

    public function misPedidos()
    {
        $pedidos = Auth::user()
            ->pedidos()
            ->with('items.producto')
            ->latest()
            ->paginate(10);

        return view('tienda.mis-pedidos', compact('pedidos'));
    }

    public function showPedido(Pedido $pedido)
    {
        abort_unless($pedido->user_id === Auth::id() || Auth::user()->isAdmin(), 403);
        $pedido->load('items.producto');
        return view('tienda.pedido', compact('pedido'));
    }

    // ── Panel admin pedidos ───────────────────────────────────────────────

    public function adminPedidos(Request $request)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $query = Pedido::with('user', 'items.producto')->latest();

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $pedidos = $query->paginate(20);

        return view('tienda.admin-pedidos', compact('pedidos'));
    }

    public function updateEstadoPedido(Request $request, Pedido $pedido)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $request->validate([
            'estado' => 'required|in:pendiente,procesando,enviado,entregado,cancelado',
        ]);

        $pedido->update(['estado' => $request->estado]);

        return back()->with('success', 'Estado del pedido actualizado.');
    }
}
