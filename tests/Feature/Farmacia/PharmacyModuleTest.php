<?php

namespace Tests\Feature\Farmacia;

use App\Models\Inventory;
use App\Models\Lot;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\MedicinePresentation;
use App\Models\Patient;
use App\Models\Presentation;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Role;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PharmacyModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_farmacia_user_can_access_pharmacy_menu(): void
    {
        $user = $this->createPharmacyUser();

        $this->actingAs($user)
            ->get('/farmacia')
            ->assertOk()
            ->assertSee('Men&uacute; principal de farmacia', false);
    }

    public function test_farmacia_user_can_access_purchase_menu(): void
    {
        $user = $this->createPharmacyUser();

        $this->actingAs($user)
            ->get('/farmacia/compras/menu')
            ->assertRedirect(route('farmacia.purchases.index'));
    }

    public function test_farmacia_user_can_access_purchases_page(): void
    {
        $user = $this->createPharmacyUser();

        $this->actingAs($user)
            ->get('/farmacia/compras')
            ->assertOk()
            ->assertSee('Compras de insumos')
            ->assertSee('Compras registradas')
            ->assertSee('Proveedores');
    }

    public function test_farmacia_user_can_access_inventory_page(): void
    {
        $user = $this->createPharmacyUser();

        $this->actingAs($user)
            ->get('/farmacia/inventario')
            ->assertOk()
            ->assertSee('Inventario');
    }

    public function test_farmacia_user_can_access_sales_page(): void
    {
        $user = $this->createPharmacyUser();

        $this->actingAs($user)
            ->get('/farmacia/ventas')
            ->assertOk()
            ->assertSee('Ventas registradas')
            ->assertSee('Registrar venta');
    }

    public function test_farmacia_user_can_access_sale_creation_screen(): void
    {
        $user = $this->createPharmacyUser();

        $this->actingAs($user)
            ->get('/farmacia/ventas/crear')
            ->assertOk()
            ->assertSee('Registrar venta')
            ->assertSee('Lote disponible')
            ->assertSee('Sin paciente')
            ->assertSee('Registrar paciente nuevo');
    }

    public function test_farmacia_user_can_access_suppliers_page(): void
    {
        $user = $this->createPharmacyUser();

        $this->actingAs($user)
            ->get('/farmacia/proveedores')
            ->assertOk()
            ->assertSee('Gestion de proveedores');
    }

    public function test_farmacia_user_can_access_medicines_catalog_page(): void
    {
        $user = $this->createPharmacyUser();

        $this->actingAs($user)
            ->get('/farmacia/medicamentos')
            ->assertOk()
            ->assertSee('Catalogo de medicamentos')
            ->assertSee('Registrar compra');
    }

    public function test_farmacia_user_can_access_medicine_catalog_edit_screen(): void
    {
        $user = $this->createPharmacyUser();
        $category = MedicineCategory::create([
            'nombre' => 'Analgesicos',
            'descripcion' => 'Categoria de prueba',
        ]);
        $medicine = Medicine::create([
            'id_categoria' => $category->id_categoria,
            'nombre' => 'Diclofenaco',
            'descripcion' => 'Medicamento de prueba',
            'estado' => true,
        ]);
        $presentation = Presentation::create([
            'nombre' => 'Tabletas',
            'descripcion' => 'Presentacion de prueba',
        ]);
        $medicinePresentation = MedicinePresentation::create([
            'id_medicamento' => $medicine->id_medicamento,
            'id_presentacion' => $presentation->id_presentacion,
            'precio_venta' => 12.00,
            'stock_minimo' => 8,
            'estado' => true,
        ]);

        $this->actingAs($user)
            ->get(route('farmacia.medicines.edit', $medicinePresentation))
            ->assertOk()
            ->assertSee('Editar catalogo')
            ->assertSee('Diclofenaco');
    }

    public function test_farmacia_user_can_register_medicine_catalog_item(): void
    {
        $user = $this->createPharmacyUser();

        $this->actingAs($user)
            ->post('/farmacia/medicamentos', [
                'nombre' => 'Paracetamol',
                'descripcion' => 'Analgesico de prueba',
                'modo_categoria' => 'nueva',
                'nueva_categoria' => 'Analgesicos',
                'descripcion_categoria' => 'Categoria de prueba',
                'modo_presentacion' => 'nueva',
                'nueva_presentacion' => 'Blister',
                'descripcion_presentacion' => 'Presentacion de prueba',
                'precio_venta' => '8.50',
                'stock_minimo' => 15,
                'estado' => '1',
            ])
            ->assertRedirect(route('farmacia.medicines.index'))
            ->assertSessionHas('status', 'Medicamento registrado correctamente.');

        $category = MedicineCategory::query()->firstOrFail();
        $presentation = Presentation::query()->firstOrFail();
        $medicine = Medicine::query()->firstOrFail();
        $medicinePresentation = MedicinePresentation::query()->firstOrFail();

        $this->assertSame('Analgesicos', $category->nombre);
        $this->assertSame('Blister', $presentation->nombre);
        $this->assertSame('Paracetamol', $medicine->nombre);
        $this->assertSame($category->id_categoria, $medicine->id_categoria);
        $this->assertSame($medicine->id_medicamento, $medicinePresentation->id_medicamento);
        $this->assertSame($presentation->id_presentacion, $medicinePresentation->id_presentacion);
        $this->assertEquals(8.50, (float) $medicinePresentation->precio_venta);
        $this->assertSame(15, $medicinePresentation->stock_minimo);
    }

    public function test_farmacia_user_can_register_supplier(): void
    {
        $user = $this->createPharmacyUser();

        $this->actingAs($user)
            ->post('/farmacia/proveedores', [
                'nombre' => 'Distribuidora Central',
                'direccion' => 'Zona 1, Guatemala',
                'correo' => 'contacto@central.test',
                'telefono' => '55554444',
                'estado' => '1',
            ])
            ->assertRedirect(route('farmacia.suppliers.index'))
            ->assertSessionHas('status', 'Proveedor registrado correctamente.');

        $supplier = Supplier::query()->firstOrFail();

        $this->assertSame('Distribuidora Central', $supplier->nombre);
        $this->assertSame('Zona 1, Guatemala', $supplier->direccion);
        $this->assertSame('contacto@central.test', $supplier->correo);
        $this->assertSame('55554444', $supplier->telefono);
        $this->assertTrue((bool) $supplier->estado);
        $this->assertDatabaseHas('proveedor', [
            'id_proveedor' => $supplier->id_proveedor,
            'correo' => 'contacto@central.test',
            'telefono' => '55554444',
        ]);
    }

    public function test_farmacia_user_can_register_purchase_and_update_inventory(): void
    {
        $user = $this->createPharmacyUser();
        $supplier = Supplier::create([
            'nombre' => 'Proveedor Farmacia',
            'direccion' => 'Zona 3',
            'estado' => true,
        ]);
        $category = MedicineCategory::create([
            'nombre' => 'Antibioticos',
            'descripcion' => 'Categoria de prueba',
        ]);
        $medicine = Medicine::create([
            'id_categoria' => $category->id_categoria,
            'nombre' => 'Amoxicilina',
            'descripcion' => 'Medicamento de prueba',
            'estado' => true,
        ]);
        $presentation = Presentation::create([
            'nombre' => 'Caja',
            'descripcion' => 'Presentacion de prueba',
        ]);
        $medicinePresentation = MedicinePresentation::create([
            'id_medicamento' => $medicine->id_medicamento,
            'id_presentacion' => $presentation->id_presentacion,
            'precio_venta' => 7.50,
            'stock_minimo' => 10,
            'estado' => true,
        ]);

        $this->actingAs($user)
            ->post('/farmacia/compras', [
                'id_proveedor' => $supplier->id_proveedor,
                'id_medicamento_presentacion' => $medicinePresentation->id_medicamento_presentacion,
                'cantidad' => 24,
                'precio_compra' => 3.75,
                'fecha' => '2026-04-04 10:30:00',
                'numero_lote' => 'L-2026-001',
                'fecha_ingreso' => '2026-04-04',
                'fecha_vencimiento' => '2027-04-04',
                'estado_entrega' => 'entregada',
            ])
            ->assertRedirect(route('farmacia.purchases.index'))
            ->assertSessionHas('status', 'Compra registrada como entregada y stock actualizado.');

        $purchase = Purchase::query()->firstOrFail();
        $detail = PurchaseDetail::query()->firstOrFail();
        $lot = Lot::query()->firstOrFail();
        $inventory = Inventory::query()->firstOrFail();

        $this->assertSame($supplier->id_proveedor, $purchase->id_proveedor);
        $this->assertSame($user->id_usuario, $purchase->id_usuario);
        $this->assertSame('entregada', $purchase->estado_entrega);
        $this->assertEquals(90.00, (float) $purchase->total);
        $this->assertSame($purchase->id_compra, $detail->id_compra);
        $this->assertSame($medicinePresentation->id_medicamento_presentacion, $detail->id_medicamento_presentacion);
        $this->assertSame(24, $detail->cantidad);
        $this->assertSame($detail->id_detalle_compra, $lot->id_detalle_compra);
        $this->assertSame('L-2026-001', $lot->numero_lote);
        $this->assertSame($lot->id_lote, $inventory->id_lote);
        $this->assertSame(24, $inventory->cantidad_actual);
        $this->assertSame(10, $inventory->stock_minimo);
    }

    public function test_farmacia_user_can_register_pending_purchase_without_updating_inventory(): void
    {
        $user = $this->createPharmacyUser();
        $supplier = Supplier::create([
            'nombre' => 'Proveedor Pendiente',
            'direccion' => 'Zona 8',
            'estado' => true,
        ]);
        $category = MedicineCategory::create([
            'nombre' => 'Antivirales',
            'descripcion' => 'Categoria de prueba',
        ]);
        $medicine = Medicine::create([
            'id_categoria' => $category->id_categoria,
            'nombre' => 'Aciclovir',
            'descripcion' => 'Medicamento de prueba',
            'estado' => true,
        ]);
        $presentation = Presentation::create([
            'nombre' => 'Frasco',
            'descripcion' => 'Presentacion de prueba',
        ]);
        $medicinePresentation = MedicinePresentation::create([
            'id_medicamento' => $medicine->id_medicamento,
            'id_presentacion' => $presentation->id_presentacion,
            'precio_venta' => 15.00,
            'stock_minimo' => 5,
            'estado' => true,
        ]);

        $this->actingAs($user)
            ->post('/farmacia/compras', [
                'id_proveedor' => $supplier->id_proveedor,
                'id_medicamento_presentacion' => $medicinePresentation->id_medicamento_presentacion,
                'cantidad' => 30,
                'precio_compra' => 9.50,
                'fecha' => '2026-04-05 09:00:00',
                'numero_lote' => 'PEND-001',
                'fecha_ingreso' => '2026-04-05',
                'fecha_vencimiento' => '2027-04-05',
                'estado_entrega' => 'pendiente',
            ])
            ->assertRedirect(route('farmacia.purchases.index'))
            ->assertSessionHas('status', 'Compra registrada como pendiente. El inventario se actualizara cuando la marques como entregada.');

        $purchase = Purchase::query()->firstOrFail();
        $lot = Lot::query()->firstOrFail();

        $this->assertSame('pendiente', $purchase->estado_entrega);
        $this->assertFalse((bool) $purchase->estado);
        $this->assertFalse((bool) $lot->estado);
        $this->assertDatabaseCount('inventario', 0);
    }

    public function test_farmacia_user_can_mark_pending_purchase_as_delivered(): void
    {
        $user = $this->createPharmacyUser();
        $supplier = Supplier::create([
            'nombre' => 'Proveedor Pendiente',
            'direccion' => 'Zona 8',
            'estado' => true,
        ]);
        $category = MedicineCategory::create([
            'nombre' => 'Antivirales',
            'descripcion' => 'Categoria de prueba',
        ]);
        $medicine = Medicine::create([
            'id_categoria' => $category->id_categoria,
            'nombre' => 'Aciclovir',
            'descripcion' => 'Medicamento de prueba',
            'estado' => true,
        ]);
        $presentation = Presentation::create([
            'nombre' => 'Frasco',
            'descripcion' => 'Presentacion de prueba',
        ]);
        $medicinePresentation = MedicinePresentation::create([
            'id_medicamento' => $medicine->id_medicamento,
            'id_presentacion' => $presentation->id_presentacion,
            'precio_venta' => 15.00,
            'stock_minimo' => 5,
            'estado' => true,
        ]);
        $purchase = Purchase::create([
            'id_proveedor' => $supplier->id_proveedor,
            'id_usuario' => $user->id_usuario,
            'fecha' => '2026-04-05 09:00:00',
            'total' => 285.00,
            'estado_entrega' => 'pendiente',
            'estado' => false,
        ]);
        $detail = PurchaseDetail::create([
            'id_compra' => $purchase->id_compra,
            'id_medicamento_presentacion' => $medicinePresentation->id_medicamento_presentacion,
            'cantidad' => 30,
            'precio_compra' => 9.50,
            'subtotal' => 285.00,
        ]);
        $lot = Lot::create([
            'id_detalle_compra' => $detail->id_detalle_compra,
            'numero_lote' => 'PEND-002',
            'fecha_vencimiento' => '2027-04-05',
            'fecha_ingreso' => '2026-04-05',
            'estado' => false,
        ]);

        $this->actingAs($user)
            ->patch(route('farmacia.purchases.deliver', $purchase))
            ->assertRedirect(route('farmacia.purchases.index'))
            ->assertSessionHas('status', 'Compra marcada como entregada y stock actualizado.');

        $purchase->refresh();
        $lot->refresh();
        $inventory = Inventory::query()->firstOrFail();

        $this->assertSame('entregada', $purchase->estado_entrega);
        $this->assertTrue((bool) $purchase->estado);
        $this->assertTrue((bool) $lot->estado);
        $this->assertSame($lot->id_lote, $inventory->id_lote);
        $this->assertSame(30, $inventory->cantidad_actual);
    }

    public function test_farmacia_user_can_register_sale_and_update_inventory(): void
    {
        $user = $this->createPharmacyUser();
        $supplier = Supplier::create([
            'nombre' => 'Proveedor de prueba',
            'direccion' => 'Zona 1',
            'estado' => true,
        ]);
        $category = MedicineCategory::create([
            'nombre' => 'Analgesicos',
            'descripcion' => 'Categoria de prueba',
        ]);
        $medicine = Medicine::create([
            'id_categoria' => $category->id_categoria,
            'nombre' => 'Paracetamol',
            'descripcion' => 'Medicamento de prueba',
            'estado' => true,
        ]);
        $presentation = Presentation::create([
            'nombre' => 'Caja',
            'descripcion' => 'Presentacion de prueba',
        ]);
        $medicinePresentation = MedicinePresentation::create([
            'id_medicamento' => $medicine->id_medicamento,
            'id_presentacion' => $presentation->id_presentacion,
            'precio_venta' => 12.50,
            'stock_minimo' => 5,
            'estado' => true,
        ]);
        $purchase = Purchase::create([
            'id_proveedor' => $supplier->id_proveedor,
            'id_usuario' => $user->id_usuario,
            'fecha' => '2026-04-04 10:30:00',
            'total' => 60.00,
            'estado_entrega' => 'entregada',
            'estado' => true,
        ]);
        $purchaseDetail = PurchaseDetail::create([
            'id_compra' => $purchase->id_compra,
            'id_medicamento_presentacion' => $medicinePresentation->id_medicamento_presentacion,
            'cantidad' => 8,
            'precio_compra' => 7.50,
            'subtotal' => 60.00,
        ]);
        $lot = Lot::create([
            'id_detalle_compra' => $purchaseDetail->id_detalle_compra,
            'numero_lote' => 'LOT-VENTA-01',
            'fecha_vencimiento' => '2027-05-01',
            'fecha_ingreso' => '2026-04-04',
            'estado' => true,
        ]);
        $inventory = Inventory::create([
            'id_lote' => $lot->id_lote,
            'cantidad_actual' => 8,
            'fecha_actualizacion' => now(),
        ]);

        $this->actingAs($user)
            ->post('/farmacia/ventas', [
                'modo_paciente' => 'nuevo',
                'nombres_paciente' => 'Juan',
                'apellidos_paciente' => 'Perez',
                'fecha_nacimiento_paciente' => '1995-05-10',
                'sexo_paciente' => 'Masculino',
                'direccion_paciente' => 'Zona 1',
                'id_lote' => $lot->id_lote,
                'cantidad' => 3,
                'fecha' => '2026-04-10 09:15:00',
                'estado' => '1',
            ])
            ->assertRedirect(route('farmacia.sales.index'))
            ->assertSessionHas('status', 'Venta registrada correctamente y stock actualizado.');

        $sale = Sale::query()->firstOrFail();
        $detail = SaleDetail::query()->firstOrFail();
        $patient = Patient::query()->firstOrFail();

        $this->assertSame($patient->id_paciente, $sale->id_paciente);
        $this->assertSame($user->id_usuario, $sale->id_usuario);
        $this->assertEquals(37.50, (float) $sale->total);
        $this->assertSame($sale->id_venta, $detail->id_venta);
        $this->assertSame($medicinePresentation->id_medicamento_presentacion, $detail->id_medicamento_presentacion);
        $this->assertSame(3, $detail->cantidad);

        $inventory->refresh();

        $this->assertSame(5, $inventory->cantidad_actual);
    }

    public function test_farmacia_user_can_register_sale_without_patient(): void
    {
        $user = $this->createPharmacyUser();
        $supplier = Supplier::create([
            'nombre' => 'Proveedor sin paciente',
            'direccion' => 'Zona 5',
            'estado' => true,
        ]);
        $category = MedicineCategory::create([
            'nombre' => 'Gastrointestinales',
            'descripcion' => 'Categoria de prueba',
        ]);
        $medicine = Medicine::create([
            'id_categoria' => $category->id_categoria,
            'nombre' => 'Omeprazol',
            'descripcion' => 'Medicamento de prueba',
            'estado' => true,
        ]);
        $presentation = Presentation::create([
            'nombre' => 'Blister',
            'descripcion' => 'Presentacion de prueba',
        ]);
        $medicinePresentation = MedicinePresentation::create([
            'id_medicamento' => $medicine->id_medicamento,
            'id_presentacion' => $presentation->id_presentacion,
            'precio_venta' => 10.00,
            'stock_minimo' => 5,
            'estado' => true,
        ]);
        $purchase = Purchase::create([
            'id_proveedor' => $supplier->id_proveedor,
            'id_usuario' => $user->id_usuario,
            'fecha' => '2026-04-04 10:30:00',
            'total' => 50.00,
            'estado_entrega' => 'entregada',
            'estado' => true,
        ]);
        $purchaseDetail = PurchaseDetail::create([
            'id_compra' => $purchase->id_compra,
            'id_medicamento_presentacion' => $medicinePresentation->id_medicamento_presentacion,
            'cantidad' => 5,
            'precio_compra' => 6.00,
            'subtotal' => 30.00,
        ]);
        $lot = Lot::create([
            'id_detalle_compra' => $purchaseDetail->id_detalle_compra,
            'numero_lote' => 'LOT-VENTA-03',
            'fecha_vencimiento' => '2027-08-01',
            'fecha_ingreso' => '2026-04-04',
            'estado' => true,
        ]);
        $inventory = Inventory::create([
            'id_lote' => $lot->id_lote,
            'cantidad_actual' => 5,
            'fecha_actualizacion' => now(),
        ]);

        $this->actingAs($user)
            ->post('/farmacia/ventas', [
                'modo_paciente' => 'ninguno',
                'id_lote' => $lot->id_lote,
                'cantidad' => 2,
                'fecha' => '2026-04-12 09:15:00',
                'estado' => '1',
            ])
            ->assertRedirect(route('farmacia.sales.index'))
            ->assertSessionHas('status', 'Venta registrada correctamente y stock actualizado.');

        $sale = Sale::query()->firstOrFail();

        $this->assertNull($sale->id_paciente);

        $inventory->refresh();

        $this->assertSame(3, $inventory->cantidad_actual);
    }

    public function test_sale_shows_reason_when_requested_quantity_exceeds_stock(): void
    {
        $user = $this->createPharmacyUser();
        $supplier = Supplier::create([
            'nombre' => 'Proveedor de prueba',
            'direccion' => 'Zona 1',
            'estado' => true,
        ]);
        $category = MedicineCategory::create([
            'nombre' => 'Analgesicos',
            'descripcion' => 'Categoria de prueba',
        ]);
        $medicine = Medicine::create([
            'id_categoria' => $category->id_categoria,
            'nombre' => 'Ibuprofeno',
            'descripcion' => 'Medicamento de prueba',
            'estado' => true,
        ]);
        $presentation = Presentation::create([
            'nombre' => 'Caja',
            'descripcion' => 'Presentacion de prueba',
        ]);
        $medicinePresentation = MedicinePresentation::create([
            'id_medicamento' => $medicine->id_medicamento,
            'id_presentacion' => $presentation->id_presentacion,
            'precio_venta' => 9.50,
            'stock_minimo' => 3,
            'estado' => true,
        ]);
        $purchase = Purchase::create([
            'id_proveedor' => $supplier->id_proveedor,
            'id_usuario' => $user->id_usuario,
            'fecha' => '2026-04-04 10:30:00',
            'total' => 19.00,
            'estado_entrega' => 'entregada',
            'estado' => true,
        ]);
        $purchaseDetail = PurchaseDetail::create([
            'id_compra' => $purchase->id_compra,
            'id_medicamento_presentacion' => $medicinePresentation->id_medicamento_presentacion,
            'cantidad' => 2,
            'precio_compra' => 5.00,
            'subtotal' => 10.00,
        ]);
        $lot = Lot::create([
            'id_detalle_compra' => $purchaseDetail->id_detalle_compra,
            'numero_lote' => 'LOT-VENTA-02',
            'fecha_vencimiento' => '2027-05-01',
            'fecha_ingreso' => '2026-04-04',
            'estado' => true,
        ]);
        Inventory::create([
            'id_lote' => $lot->id_lote,
            'cantidad_actual' => 2,
            'fecha_actualizacion' => now(),
        ]);

        $this->actingAs($user)
            ->from(route('farmacia.sales.create'))
            ->post('/farmacia/ventas', [
                'modo_paciente' => 'nuevo',
                'nombres_paciente' => 'Luis',
                'apellidos_paciente' => 'Ramirez',
                'fecha_nacimiento_paciente' => '1992-03-14',
                'sexo_paciente' => 'Masculino',
                'direccion_paciente' => 'Zona 4',
                'id_lote' => $lot->id_lote,
                'cantidad' => 5,
                'fecha' => '2026-04-10 09:15:00',
                'estado' => '1',
            ])
            ->assertRedirect(route('farmacia.sales.create'))
            ->assertSessionHasErrors([
                'cantidad' => 'La cantidad solicitada supera el stock disponible del lote seleccionado.',
            ]);

        $this->assertDatabaseCount('venta', 0);
        $this->assertDatabaseCount('detalle_venta', 0);
    }

    public function test_farmacia_user_can_update_catalog_price_stock_and_availability(): void
    {
        $user = $this->createPharmacyUser();
        $category = MedicineCategory::create([
            'nombre' => 'Analgesicos',
            'descripcion' => 'Categoria de prueba',
        ]);
        $medicine = Medicine::create([
            'id_categoria' => $category->id_categoria,
            'nombre' => 'Naproxeno',
            'descripcion' => 'Medicamento de prueba',
            'estado' => true,
        ]);
        $presentation = Presentation::create([
            'nombre' => 'Caja',
            'descripcion' => 'Presentacion de prueba',
        ]);
        $medicinePresentation = MedicinePresentation::create([
            'id_medicamento' => $medicine->id_medicamento,
            'id_presentacion' => $presentation->id_presentacion,
            'precio_venta' => 12.50,
            'stock_minimo' => 5,
            'estado' => true,
        ]);

        $this->actingAs($user)
            ->put(route('farmacia.medicines.update', $medicinePresentation), [
                'precio_venta' => '18.75',
                'stock_minimo' => 9,
                'estado' => '0',
            ])
            ->assertRedirect(route('farmacia.medicines.index'))
            ->assertSessionHas('status', 'Catalogo actualizado correctamente.');

        $medicinePresentation->refresh();

        $this->assertEquals(18.75, (float) $medicinePresentation->precio_venta);
        $this->assertSame(9, $medicinePresentation->stock_minimo);
        $this->assertFalse((bool) $medicinePresentation->estado);
    }

    public function test_purchases_page_shows_registered_medicine_and_lot(): void
    {
        $user = $this->createPharmacyUser();
        $supplier = Supplier::create([
            'nombre' => 'Proveedor Farmacia',
            'direccion' => 'Zona 3',
            'estado' => true,
        ]);
        $category = MedicineCategory::create([
            'nombre' => 'Antibioticos',
            'descripcion' => 'Categoria de prueba',
        ]);
        $medicine = Medicine::create([
            'id_categoria' => $category->id_categoria,
            'nombre' => 'Amoxicilina',
            'descripcion' => 'Medicamento de prueba',
            'estado' => true,
        ]);
        $presentation = Presentation::create([
            'nombre' => 'Caja',
            'descripcion' => 'Presentacion de prueba',
        ]);
        $medicinePresentation = MedicinePresentation::create([
            'id_medicamento' => $medicine->id_medicamento,
            'id_presentacion' => $presentation->id_presentacion,
            'precio_venta' => 7.50,
            'stock_minimo' => 10,
            'estado' => true,
        ]);
        $purchase = Purchase::create([
            'id_proveedor' => $supplier->id_proveedor,
            'id_usuario' => $user->id_usuario,
            'fecha' => '2026-04-04 10:30:00',
            'total' => 90.00,
            'estado_entrega' => 'entregada',
            'estado' => true,
        ]);
        $detail = PurchaseDetail::create([
            'id_compra' => $purchase->id_compra,
            'id_medicamento_presentacion' => $medicinePresentation->id_medicamento_presentacion,
            'cantidad' => 24,
            'precio_compra' => 3.75,
            'subtotal' => 90.00,
        ]);
        Lot::create([
            'id_detalle_compra' => $detail->id_detalle_compra,
            'numero_lote' => 'L-2026-001',
            'fecha_vencimiento' => '2027-04-04',
            'fecha_ingreso' => '2026-04-04',
            'estado' => true,
        ]);

        $this->actingAs($user)
            ->get('/farmacia/compras')
            ->assertOk()
            ->assertSee('Amoxicilina - Caja')
            ->assertSee('L-2026-001');
    }

    public function test_sales_page_shows_registered_sale_and_lot(): void
    {
        $user = $this->createPharmacyUser();
        $patient = Patient::create([
            'nombres' => 'Ana',
            'apellidos' => 'Lopez',
            'fecha_nacimiento' => '1994-06-15',
            'sexo' => 'Femenino',
            'direccion' => 'Zona 2',
            'estado' => true,
        ]);
        $supplier = Supplier::create([
            'nombre' => 'Proveedor de prueba',
            'direccion' => 'Zona 1',
            'estado' => true,
        ]);
        $category = MedicineCategory::create([
            'nombre' => 'Analgesicos',
            'descripcion' => 'Categoria de prueba',
        ]);
        $medicine = Medicine::create([
            'id_categoria' => $category->id_categoria,
            'nombre' => 'Paracetamol',
            'descripcion' => 'Medicamento de prueba',
            'estado' => true,
        ]);
        $presentation = Presentation::create([
            'nombre' => 'Caja',
            'descripcion' => 'Presentacion de prueba',
        ]);
        $medicinePresentation = MedicinePresentation::create([
            'id_medicamento' => $medicine->id_medicamento,
            'id_presentacion' => $presentation->id_presentacion,
            'precio_venta' => 12.50,
            'stock_minimo' => 5,
            'estado' => true,
        ]);
        $purchase = Purchase::create([
            'id_proveedor' => $supplier->id_proveedor,
            'id_usuario' => $user->id_usuario,
            'fecha' => '2026-04-04 10:30:00',
            'total' => 60.00,
            'estado_entrega' => 'entregada',
            'estado' => true,
        ]);
        $purchaseDetail = PurchaseDetail::create([
            'id_compra' => $purchase->id_compra,
            'id_medicamento_presentacion' => $medicinePresentation->id_medicamento_presentacion,
            'cantidad' => 8,
            'precio_compra' => 7.50,
            'subtotal' => 60.00,
        ]);
        $lot = Lot::create([
            'id_detalle_compra' => $purchaseDetail->id_detalle_compra,
            'numero_lote' => 'LOT-VENTA-01',
            'fecha_vencimiento' => '2027-05-01',
            'fecha_ingreso' => '2026-04-04',
            'estado' => true,
        ]);
        $sale = Sale::create([
            'id_paciente' => $patient->id_paciente,
            'id_usuario' => $user->id_usuario,
            'fecha' => '2026-04-10 09:15:00',
            'total' => 37.50,
            'estado' => true,
        ]);
        SaleDetail::create([
            'id_venta' => $sale->id_venta,
            'id_medicamento_presentacion' => $medicinePresentation->id_medicamento_presentacion,
            'cantidad' => 3,
            'precio_unitario' => 12.50,
            'subtotal' => 37.50,
        ]);

        $this->actingAs($user)
            ->get('/farmacia/ventas')
            ->assertOk()
            ->assertSee('Paracetamol - Caja')
            ->assertSee('Ana Lopez');
    }

    public function test_user_without_farmacia_permission_cannot_access_module(): void
    {
        $role = Role::factory()->create([
            'nombre' => 'Licenciado',
        ]);

        $user = User::factory()->create([
            'id_rol' => $role->id_rol,
        ]);

        $this->actingAs($user)
            ->get('/farmacia')
            ->assertForbidden();
    }

    private function createPharmacyUser(): User
    {
        $role = Role::factory()->create([
            'nombre' => 'Farmacia',
        ]);

        return User::factory()->create([
            'id_rol' => $role->id_rol,
        ]);
    }
}
