<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMedicineCatalogRequest;
use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateMedicineCatalogRequest;
use App\Models\Inventory;
use App\Models\Lot;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\MedicinePresentation;
use App\Models\Patient;
use App\Models\Presentation;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Supplier;
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

    public function purchaseMenu(): RedirectResponse
    {
        return redirect()->route('farmacia.purchases.index');
    }

    public function sales(Request $request): View
    {
        $sales = Sale::query()
            ->with([
                'user',
                'patient',
                'details.medicinePresentation.medicine',
                'details.medicinePresentation.presentation',
            ])
            ->when($request->filled('q'), function ($query) use ($request): void {
                $term = trim((string) $request->string('q'));

                $query->where(function ($innerQuery) use ($term): void {
                    $innerQuery->whereHas('user', fn ($userQuery) => $userQuery->where('username', 'ILIKE', "%{$term}%"))
                        ->orWhereHas('patient', function ($patientQuery) use ($term): void {
                            $patientQuery->where('nombres', 'ILIKE', "%{$term}%")
                                ->orWhere('apellidos', 'ILIKE', "%{$term}%");
                        })
                        ->orWhereHas('details.medicinePresentation.medicine', fn ($medicineQuery) => $medicineQuery->where('nombre', 'ILIKE', "%{$term}%"))
                        ->orWhereHas('details.medicinePresentation.presentation', fn ($presentationQuery) => $presentationQuery->where('nombre', 'ILIKE', "%{$term}%"));
                });
            })
            ->when($request->filled('estado'), function ($query) use ($request): void {
                $query->where('estado', $request->string('estado')->value() === 'activo');
            })
            ->orderByDesc('fecha')
            ->paginate(10)
            ->withQueryString();

        return view('farmacia.ventas.index', [
            'sales' => $sales,
            'filters' => $request->only(['q', 'estado']),
        ]);
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
                        ->orWhere('correo', 'ILIKE', "%{$term}%")
                        ->orWhere('telefono', 'ILIKE', "%{$term}%")
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

    public function editMedicine(MedicinePresentation $medicinePresentation): View
    {
        $medicinePresentation->load(['medicine.category', 'presentation']);

        return view('farmacia.medicamentos.edit', [
            'medicinePresentation' => $medicinePresentation,
        ]);
    }

    public function storeMedicine(StoreMedicineCatalogRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated): void {
                $category = ($validated['modo_categoria'] ?? 'existente') === 'existente'
                    ? MedicineCategory::query()->findOrFail((int) $validated['id_categoria'])
                    : MedicineCategory::query()->firstOrCreate(
                        ['nombre' => trim($validated['nueva_categoria'])],
                        ['descripcion' => filled($validated['descripcion_categoria'] ?? null) ? trim($validated['descripcion_categoria']) : null]
                    );

                $presentation = ($validated['modo_presentacion'] ?? 'existente') === 'existente'
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

    public function updateMedicine(UpdateMedicineCatalogRequest $request, MedicinePresentation $medicinePresentation): RedirectResponse
    {
        $validated = $request->validated();

        $medicinePresentation->update([
            'precio_venta' => $validated['precio_venta'],
            'stock_minimo' => (int) $validated['stock_minimo'],
            'estado' => (bool) $validated['estado'],
        ]);

        return redirect()
            ->route('farmacia.medicines.index')
            ->with('status', 'Catalogo actualizado correctamente.');
    }

    public function storeSupplier(StoreSupplierRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated): void {
                Supplier::create([
                    'nombre' => trim($validated['nombre']),
                    'direccion' => trim($validated['direccion']),
                    'correo' => filled($validated['correo'] ?? null) ? trim($validated['correo']) : null,
                    'telefono' => trim($validated['telefono']),
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

    public function createSale(): View
    {
        return view('farmacia.ventas.create', [
            'patients' => Patient::query()
                ->where('estado', true)
                ->orderBy('nombres')
                ->orderBy('apellidos')
                ->get(),
            'availableInventory' => $this->availableInventoryForSales(),
        ]);
    }

    public function storePurchase(StorePurchaseRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $subtotal = (float) $validated['cantidad'] * (float) $validated['precio_compra'];
        $isDelivered = $validated['estado_entrega'] === 'entregada';

        try {
            DB::transaction(function () use ($validated, $request, $subtotal, $isDelivered): void {
                $purchase = Purchase::create([
                    'id_proveedor' => (int) $validated['id_proveedor'],
                    'id_usuario' => (int) $request->user()->id_usuario,
                    'fecha' => $validated['fecha'],
                    'total' => $subtotal,
                    'estado_entrega' => $validated['estado_entrega'],
                    'estado' => $isDelivered,
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
                    'estado' => $isDelivered,
                ]);

                if ($isDelivered) {
                    Inventory::create([
                        'id_lote' => $lot->id_lote,
                        'cantidad_actual' => (int) $validated['cantidad'],
                        'fecha_actualizacion' => now(),
                    ]);
                }
            });
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with('error', 'No se pudo registrar la compra. Verifica los datos e intenta nuevamente.');
        }

        return redirect()
            ->route('farmacia.purchases.index')
            ->with('status', $isDelivered
                ? 'Compra registrada como entregada y stock actualizado.'
                : 'Compra registrada como pendiente. El inventario se actualizara cuando la marques como entregada.');
    }

    public function storeSale(StoreSaleRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated, $request): void {
                $inventory = Inventory::query()
                    ->with(['lot.purchaseDetail.medicinePresentation.medicine', 'lot.purchaseDetail.medicinePresentation.presentation'])
                    ->where('id_lote', (int) $validated['id_lote'])
                    ->lockForUpdate()
                    ->firstOrFail();

                $medicinePresentation = $inventory->medicine_presentation;

                if (! $medicinePresentation || $inventory->cantidad_actual < (int) $validated['cantidad']) {
                    throw new \RuntimeException('Inventario insuficiente para registrar la venta.');
                }

                $patientMode = $validated['modo_paciente'] ?? 'ninguno';
                $patient = null;

                if ($patientMode === 'existente') {
                    $patient = Patient::query()->findOrFail((int) $validated['id_paciente']);
                }

                if ($patientMode === 'nuevo') {
                    $patient = Patient::create([
                        'nombres' => trim($validated['nombres_paciente']),
                        'apellidos' => trim($validated['apellidos_paciente']),
                        'fecha_nacimiento' => $validated['fecha_nacimiento_paciente'],
                        'sexo' => trim($validated['sexo_paciente']),
                        'direccion' => trim($validated['direccion_paciente']),
                        'estado' => true,
                    ]);
                }

                $subtotal = (float) $medicinePresentation->precio_venta * (int) $validated['cantidad'];

                $sale = Sale::create([
                    'id_paciente' => $patient?->id_paciente,
                    'id_usuario' => (int) $request->user()->id_usuario,
                    'fecha' => $validated['fecha'],
                    'total' => $subtotal,
                    'estado' => (bool) $validated['estado'],
                ]);

                SaleDetail::create([
                    'id_venta' => $sale->id_venta,
                    'id_medicamento_presentacion' => $medicinePresentation->id_medicamento_presentacion,
                    'cantidad' => (int) $validated['cantidad'],
                    'precio_unitario' => $medicinePresentation->precio_venta,
                    'subtotal' => $subtotal,
                ]);

                $inventory->cantidad_actual -= (int) $validated['cantidad'];
                $inventory->fecha_actualizacion = now();
                $inventory->save();
            });
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with('error_title', 'No se pudo registrar la venta')
                ->with('error', $this->saleFailureMessage($exception));
        }

        return redirect()
            ->route('farmacia.sales.index')
            ->with('status', 'Venta registrada correctamente y stock actualizado.');
    }

    public function purchases(Request $request): View
    {
        $purchases = Purchase::query()
            ->with([
                'supplier',
                'user',
                'details.medicinePresentation.medicine',
                'details.medicinePresentation.presentation',
                'details.lots',
            ])
            ->when($request->filled('q'), function ($query) use ($request): void {
                $term = trim((string) $request->string('q'));

                $query->where(function ($innerQuery) use ($term): void {
                    $innerQuery->whereHas('supplier', fn ($supplierQuery) => $supplierQuery->where('nombre', 'ILIKE', "%{$term}%"))
                        ->orWhereHas('user', fn ($userQuery) => $userQuery->where('username', 'ILIKE', "%{$term}%"))
                        ->orWhereHas('details.medicinePresentation.medicine', fn ($medicineQuery) => $medicineQuery->where('nombre', 'ILIKE', "%{$term}%"))
                        ->orWhereHas('details.medicinePresentation.presentation', fn ($presentationQuery) => $presentationQuery->where('nombre', 'ILIKE', "%{$term}%"))
                        ->orWhereHas('details.lots', fn ($lotQuery) => $lotQuery->where('numero_lote', 'ILIKE', "%{$term}%"));
                });
            })
            ->when($request->filled('estado_entrega'), function ($query) use ($request): void {
                $query->where('estado_entrega', $request->string('estado_entrega')->value());
            })
            ->orderByDesc('fecha')
            ->paginate(10)
            ->withQueryString();

        return view('farmacia.compras.index', [
            'purchases' => $purchases,
            'filters' => $request->only(['q', 'estado_entrega']),
        ]);
    }

    public function deliverPurchase(Purchase $purchase): RedirectResponse
    {
        if ($purchase->is_delivered) {
            return redirect()
                ->route('farmacia.purchases.index')
                ->with('status', 'La compra ya estaba marcada como entregada.');
        }

        try {
            DB::transaction(function () use ($purchase): void {
                $purchase->load(['details.lots.inventory']);

                foreach ($purchase->details as $detail) {
                    foreach ($detail->lots as $lot) {
                        if (! $lot->estado) {
                            $lot->estado = true;
                            $lot->save();
                        }

                        $inventory = Inventory::query()->firstOrNew([
                            'id_lote' => $lot->id_lote,
                        ]);

                        if (! $inventory->exists) {
                            $inventory->cantidad_actual = (int) $detail->cantidad;
                        }

                        $inventory->fecha_actualizacion = now();
                        $inventory->save();
                    }
                }

                $purchase->estado_entrega = 'entregada';
                $purchase->estado = true;
                $purchase->save();
            });
        } catch (Throwable $exception) {
            report($exception);

            return back()->with('error', 'No se pudo marcar la compra como entregada. Intenta nuevamente.');
        }

        return redirect()
            ->route('farmacia.purchases.index')
            ->with('status', 'Compra marcada como entregada y stock actualizado.');
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

    private function availableInventoryForSales(): Collection
    {
        return Inventory::query()
            ->with([
                'lot.purchaseDetail.medicinePresentation.medicine',
                'lot.purchaseDetail.medicinePresentation.presentation',
            ])
            ->where('cantidad_actual', '>', 0)
            ->get()
            ->filter(function (Inventory $inventory): bool {
                $lot = $inventory->lot;
                $medicinePresentation = $inventory->medicine_presentation;

                return (bool) ($lot?->estado)
                    && ! ($lot?->fecha_vencimiento?->isBefore(today()) ?? true)
                    && (bool) ($medicinePresentation?->estado)
                    && (bool) ($medicinePresentation?->medicine?->estado);
            })
            ->sortBy(fn (Inventory $inventory): string => sprintf(
                '%012d|%s|%s',
                $inventory->lot?->fecha_vencimiento?->timestamp ?? PHP_INT_MAX,
                $inventory->medicine_name,
                $inventory->presentation_name
            ))
            ->values();
    }

    private function saleFailureMessage(Throwable $exception): string
    {
        $message = $exception->getMessage();

        if (filled($message) && ! str_contains(strtolower($message), 'sqlstate')) {
            return $message;
        }

        return 'No se pudo registrar la venta. Verifica los datos e intenta nuevamente.';
    }
}
