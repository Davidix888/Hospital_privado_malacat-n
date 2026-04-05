<?php

namespace Tests\Feature\Farmacia;

use App\Models\Inventory;
use App\Models\Lot;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\MedicinePresentation;
use App\Models\Presentation;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Role;
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
            ->assertSee('Menu operativo de farmacia');
    }

    public function test_farmacia_user_can_access_purchases_page(): void
    {
        $user = $this->createPharmacyUser();

        $this->actingAs($user)
            ->get('/farmacia/compras')
            ->assertOk()
            ->assertSee('Compras de insumos');
    }

    public function test_farmacia_user_can_access_inventory_page(): void
    {
        $user = $this->createPharmacyUser();

        $this->actingAs($user)
            ->get('/farmacia/inventario')
            ->assertOk()
            ->assertSee('Inventario');
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
            ->assertSee('Catalogo de medicamentos');
    }

    public function test_farmacia_user_can_register_medicine_catalog_item(): void
    {
        $user = $this->createPharmacyUser();

        $this->actingAs($user)
            ->post('/farmacia/medicamentos', [
                'nombre' => 'Paracetamol',
                'descripcion' => 'Analgesico de prueba',
                'nueva_categoria' => 'Analgesicos',
                'descripcion_categoria' => 'Categoria de prueba',
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
        $this->assertTrue((bool) $supplier->estado);
        $this->assertDatabaseHas('correo_proveedor', [
            'id_proveedor' => $supplier->id_proveedor,
            'correo' => 'contacto@central.test',
            'estado' => 1,
        ]);
        $this->assertDatabaseHas('telefono_proveedor', [
            'id_proveedor' => $supplier->id_proveedor,
            'numero' => '55554444',
            'tipo' => 'Principal',
            'estado' => 1,
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
                'estado' => '1',
            ])
            ->assertRedirect(route('farmacia.purchases.index'))
            ->assertSessionHas('status', 'Compra registrada correctamente y stock actualizado.');

        $purchase = Purchase::query()->firstOrFail();
        $detail = PurchaseDetail::query()->firstOrFail();
        $lot = Lot::query()->firstOrFail();
        $inventory = Inventory::query()->firstOrFail();

        $this->assertSame($supplier->id_proveedor, $purchase->id_proveedor);
        $this->assertSame($user->id_usuario, $purchase->id_usuario);
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
