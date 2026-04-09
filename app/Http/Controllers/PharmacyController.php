<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMedicineCatalogRequest;
use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\StoreSupplierRequest;
use App\Models\Inventory;
use App\Models\Lot;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\MedicinePresentation;
use App\Models\Presentation;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Supplier;
use App\Models\SupplierEmail;
use App\Models\SupplierPhone;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Throwable;

class PharmacyController extends Controller
{
    public function index(): View
    {
        $lowStockAlerts = $this->lowStockAlerts();

        return view('farmacia.index', [
            'lowStockAlerts' => $lowStockAlerts,
        ]);
    }

    public function purchaseMenu(): View
    {
        return view('farmacia.compras.menu');
    }

    public function sales(): View
    {
        return view('farmacia.ventas.index');
    }

    public function reports(): View
    {
        return view('farmacia.reportes.index');
    }

    public function createSupplier(): View
    {
        return view('farmacia.proveedores.create');
    }

    public function suppliers(Request $request): View
    {
        $suppliers = Supplier::query()
            ->with(['email', 'phone'])
            ->when($request->filled('q'), function ($query) use ($request): void {
                $term = trim((string) $request->string('q'));

                $query->where(function ($innerQuery) use ($term): void {
                    $innerQuery->where('nombre', 'ILIKE', "%{$term}%")
                        ->orWhere('direccion', 'ILIKE', "%{$term}%")
                        ->orWhereHas('email', fn ($emailQuery) => $emailQuery->where('correo', 'ILIKE', "%{$term}%"))
                        ->orWhereHas('phone', fn ($phoneQuery) => $phoneQuery->where('numero', 'ILIKE', "%{$term}%"));
                });
            })
            ->when($request->filled('estado'), function ($query) use ($request): void {
                $query->where('estado', $request->string('estado')->value() === 'activo');
            })
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view('farmacia.proveedores.index', [
            'suppliers' => $suppliers,
            'filters' => $request->only(['q', 'estado']),
        ]);
    }

    public function medicines(Request $request): View
    {
        $medicines = MedicinePresentation::query()
            ->with(['medicine.category', 'presentation'])
            ->when($request->filled('q'), function ($query) use ($request): void {
                $term = trim((string) $request->string('q'));

                $query->where(function ($innerQuery) use ($term): void {
                    $innerQuery->whereHas('medicine', fn ($medicineQuery) => $medicineQuery->where('nombre', 'ILIKE', "%{$term}%"))
                        ->orWhereHas('medicine.category', fn ($categoryQuery) => $categoryQuery->where('nombre', 'ILIKE', "%{$term}%"))
                        ->orWhereHas('presentation', fn ($presentationQuery) => $presentationQuery->where('nombre', 'ILIKE', "%{$term}%"));
                });
            })
            ->when($request->filled('estado'), function ($query) use ($request): void {
                $query->where('estado', $request->string('estado')->value() === 'activo');
            })
            ->orderByDesc('id_medicamento_presentacion')
            ->paginate(10)
            ->withQueryString();

        return view('farmacia.medicamentos.index', [
            'medicines' => $medicines,
            'filters' => $request->only(['q', 'estado']),
        ]);
    }

    public function createMedicine(): View
    {
        return view('farmacia.medicamentos.create', [
            'categories' => MedicineCategory::query()->orderBy('nombre')->get(),
            'presentations' => Presentation::query()->orderBy('nombre')->get(),
        ]);
    }

    public function storeMedicine(StoreMedicineCatalogRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated): void {
                $category = ! empty($validated['id_categoria'])
                    ? MedicineCategory::query()->findOrFail((int) $validated['id_categoria'])
                    : MedicineCategory::query()->firstOrCreate(
                        ['nombre' => trim($validated['nueva_categoria'])],
                        ['descripcion' => filled($validated['descripcion_categoria'] ?? null) ? trim($validated['descripcion_categoria']) : null]
                    );

                $presentation = ! empty($validated['id_presentacion'])
                    ? Presentation::query()->findOrFail((int) $validated['id_presentacion'])
                    : Presentation::query()->firstOrCreate(
                        ['nombre' => trim($validated['nueva_presentacion'])],
                        ['descripcion' => filled($validated['descripcion_presentacion'] ?? null) ? trim($validated['descripcion_presentacion']) : null]
                    );

                $medicine = Medicine::query()->firstOrNew([
                    'id_categoria' => $category->id_categoria,
                    'nombre' => trim($validated['nombre']),
                ]);

                $medicine->descripcion = filled($validated['descripcion'] ?? null) ? trim($validated['descripcion']) : null;
                $medicine->estado = (bool) $validated['estado'];
                $medicine->save();

                $medicinePresentation = MedicinePresentation::query()->firstOrNew([
                    'id_medicamento' => $medicine->id_medicamento,
                    'id_presentacion' => $presentation->id_presentacion,
                ]);

                $medicinePresentation->precio_venta = $validated['precio_venta'];
                $medicinePresentation->stock_minimo = (int) $validated['stock_minimo'];
                $medicinePresentation->estado = (bool) $validated['estado'];
                $medicinePresentation->save();
            });
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with('error', 'No se pudo registrar el medicamento. Verifica los datos e intenta nuevamente.');
        }

        return redirect()
            ->route('farmacia.medicines.index')
            ->with('status', 'Medicamento registrado correctamente.');
    }

    public function storeSupplier(StoreSupplierRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated): void {
                $supplier = Supplier::create([
                    'nombre' => trim($validated['nombre']),
                    'direccion' => trim($validated['direccion']),
                    'estado' => (bool) $validated['estado'],
                ]);

                if (! empty($validated['correo'])) {
                    SupplierEmail::create([
                        'id_proveedor' => $supplier->id_proveedor,
                        'correo' => trim($validated['correo']),
                        'estado' => (bool) $validated['estado'],
                    ]);
                }

                SupplierPhone::create([
                    'id_proveedor' => $supplier->id_proveedor,
                    'numero' => trim($validated['telefono']),
                    'tipo' => 'Principal',
                    'estado' => (bool) $validated['estado'],
                ]);
            });
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with('error', 'No se pudo registrar el proveedor. Verifica los datos e intenta nuevamente.');
        }

        return redirect()
            ->route('farmacia.suppliers.index')
            ->with('status', 'Proveedor registrado correctamente.');
    }

    public function createPurchase(): View
    {
        return view('farmacia.compras.create', [
            'suppliers' => Supplier::query()
                ->where('estado', true)
                ->orderBy('nombre')
                ->get(),
            'medicinePresentations' => MedicinePresentation::query()
                ->with(['medicine', 'presentation'])
                ->where('estado', true)
                ->orderBy('id_medicamento_presentacion')
                ->get(),
        ]);
    }

    public function storePurchase(StorePurchaseRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $subtotal = (float) $validated['cantidad'] * (float) $validated['precio_compra'];

        try {
            DB::transaction(function () use ($validated, $request, $subtotal): void {
                $purchase = Purchase::create([
                    'id_proveedor' => (int) $validated['id_proveedor'],
                    'id_usuario' => (int) $request->user()->id_usuario,
                    'fecha' => $validated['fecha'],
                    'total' => $subtotal,
                    'estado' => (bool) $validated['estado'],
                ]);

                $detail = PurchaseDetail::create([
                    'id_compra' => $purchase->id_compra,
                    'id_medicamento_presentacion' => (int) $validated['id_medicamento_presentacion'],
                    'cantidad' => (int) $validated['cantidad'],
                    'precio_compra' => $validated['precio_compra'],
                    'subtotal' => $subtotal,
                ]);

                $lot = Lot::create([
                    'id_detalle_compra' => $detail->id_detalle_compra,
                    'numero_lote' => trim($validated['numero_lote']),
                    'fecha_vencimiento' => $validated['fecha_vencimiento'],
                    'fecha_ingreso' => $validated['fecha_ingreso'],
                    'estado' => (bool) $validated['estado'],
                ]);

                Inventory::create([
                    'id_lote' => $lot->id_lote,
                    'cantidad_actual' => (int) $validated['cantidad'],
                    'fecha_actualizacion' => now(),
                ]);
            });
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with('error', 'No se pudo registrar la compra. Verifica los datos e intenta nuevamente.');
        }

        return redirect()
            ->route('farmacia.purchases.index')
            ->with('status', 'Compra registrada correctamente y stock actualizado.');
    }

    public function purchases(Request $request): View
    {
        $purchases = Purchase::query()
            ->with(['supplier', 'user', 'details'])
            ->when($request->filled('q'), function ($query) use ($request): void {
                $term = trim((string) $request->string('q'));

                $query->where(function ($innerQuery) use ($term): void {
                    $innerQuery->whereHas('supplier', fn ($supplierQuery) => $supplierQuery->where('nombre', 'ILIKE', "%{$term}%"))
                        ->orWhereHas('user', fn ($userQuery) => $userQuery->where('username', 'ILIKE', "%{$term}%"));
                });
            })
            ->when($request->filled('estado'), function ($query) use ($request): void {
                $query->where('estado', $request->string('estado')->value() === 'activo');
            })
            ->orderByDesc('fecha')
            ->paginate(10)
            ->withQueryString();

        return view('farmacia.compras.index', [
            'purchases' => $purchases,
            'filters' => $request->only(['q', 'estado']),
        ]);
    }

    public function inventory(Request $request): View
    {
        $inventory = Inventory::query()
            ->with([
                'lot.purchaseDetail.medicinePresentation.medicine.category',
                'lot.purchaseDetail.medicinePresentation.presentation',
            ])
            ->when($request->filled('q'), function ($query) use ($request): void {
                $term = trim((string) $request->string('q'));

                $query->where(function ($innerQuery) use ($term): void {
                    $innerQuery->whereHas('lot', function ($lotQuery) use ($term): void {
                        $lotQuery->where('numero_lote', 'ILIKE', "%{$term}%")
                            ->orWhereHas('purchaseDetail.medicinePresentation.medicine', fn ($medicineQuery) => $medicineQuery->where('nombre', 'ILIKE', "%{$term}%"))
                            ->orWhereHas('purchaseDetail.medicinePresentation.presentation', fn ($presentationQuery) => $presentationQuery->where('nombre', 'ILIKE', "%{$term}%"));
                    });
                });
            })
            ->orderByDesc('fecha_actualizacion')
            ->paginate(10)
            ->withQueryString();

        return view('farmacia.inventario.index', [
            'inventory' => $inventory,
            'filters' => $request->only(['q']),
            'lowStockAlerts' => $this->lowStockAlerts(),
        ]);
    }

    private function lowStockAlerts(int $limit = 5): Collection
    {
        return Inventory::query()
            ->with([
                'lot.purchaseDetail.medicinePresentation.medicine',
                'lot.purchaseDetail.medicinePresentation.presentation',
            ])
            ->get()
            ->filter(fn (Inventory $inventory): bool => $inventory->is_low_stock)
            ->sortBy('cantidad_actual')
            ->take($limit)
            ->values();
    }
}
