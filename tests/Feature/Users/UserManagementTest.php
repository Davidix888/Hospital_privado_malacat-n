<?php

namespace Tests\Feature\Users;

use App\Models\Cargo;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrator_can_view_user_creation_screen(): void
    {
        $admin = $this->createAdministrator();

        $this->actingAs($admin)
            ->get('/usuarios/crear')
            ->assertOk()
            ->assertSeeText('Registrar usuario')
            ->assertSeeText('Volver a usuarios');
    }

    public function test_administrator_can_create_a_user(): void
    {
        $admin = $this->createAdministrator();
        $cargo = Cargo::factory()->create();
        $role = Role::factory()->create([
            'nombre' => 'Farmacia',
        ]);

        $response = $this->actingAs($admin)->post('/usuarios', [
            'id_cargo' => $cargo->id_cargo,
            'id_rol' => $role->id_rol,
            'username' => 'farmacia01',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'estado' => '1',
            'nombres' => 'Ana',
            'apellidos' => 'Lopez',
            'dpi' => '1111111111111',
            'direccion' => 'Malacatan',
        ]);

        $response->assertRedirect('/usuarios/listado');
        $response->assertSessionHas('status_title', 'Usuario creado correctamente');
        $response->assertSessionHas('status', 'El usuario se creó correctamente.');

        $this->assertDatabaseHas('empleado', [
            'nombres' => 'Ana',
            'apellidos' => 'Lopez',
            'dpi' => '1111111111111',
            'id_cargo' => $cargo->id_cargo,
        ]);

        $this->assertDatabaseHas('usuario', [
            'id_rol' => $role->id_rol,
            'username' => 'farmacia01',
            'estado' => true,
        ]);
    }

    public function test_administrator_can_create_user_with_new_employee_and_new_cargo(): void
    {
        $admin = $this->createAdministrator();
        $role = Role::factory()->create([
            'nombre' => 'Laboratorio',
        ]);

        $response = $this->actingAs($admin)->post('/usuarios', [
            'id_rol' => $role->id_rol,
            'username' => 'labnuevo',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'estado' => '1',
            'cargo_nombre' => 'Tecnico de laboratorio',
            'cargo_descripcion' => 'Cargo creado desde el modulo.',
            'nombres' => 'Luis',
            'apellidos' => 'Dixquiac',
            'dpi' => '1234567890123',
            'direccion' => 'Malacatan, San Marcos',
        ]);

        $response->assertRedirect('/usuarios/listado');
        $response->assertSessionHas('status_title', 'Usuario creado correctamente');
        $response->assertSessionHas('status', 'El usuario se creó correctamente.');

        $this->assertDatabaseHas('cargo', [
            'nombre' => 'Tecnico de laboratorio',
        ]);

        $this->assertDatabaseHas('empleado', [
            'nombres' => 'Luis',
            'apellidos' => 'Dixquiac',
            'dpi' => '1234567890123',
        ]);

        $this->assertDatabaseHas('usuario', [
            'username' => 'labnuevo',
            'id_rol' => $role->id_rol,
            'estado' => true,
        ]);
    }

    public function test_administrator_can_deactivate_another_user(): void
    {
        $admin = $this->createAdministrator();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->patch('/usuarios/'.$user->id_usuario.'/desactivar');

        $response->assertRedirect('/usuarios/desactivar');

        $this->assertDatabaseHas('usuario', [
            'id_usuario' => $user->id_usuario,
            'estado' => false,
        ]);
    }

    public function test_administrator_can_reactivate_an_inactive_user(): void
    {
        $admin = $this->createAdministrator();
        $role = Role::factory()->create([
            'nombre' => 'Farmacia',
        ]);
        $user = User::factory()->create([
            'id_rol' => $role->id_rol,
            'estado' => false,
        ]);

        $response = $this->actingAs($admin)->patch('/usuarios/'.$user->id_usuario.'/activar');

        $response->assertRedirect('/usuarios/desactivar');

        $this->assertDatabaseHas('usuario', [
            'id_usuario' => $user->id_usuario,
            'estado' => true,
        ]);
    }

    public function test_administrator_can_update_a_user(): void
    {
        $admin = $this->createAdministrator();
        $role = Role::factory()->create([
            'nombre' => 'Farmacia',
        ]);
        $updatedRole = Role::factory()->create([
            'nombre' => 'Laboratorio',
        ]);
        $user = User::factory()->create([
            'id_rol' => $role->id_rol,
            'username' => 'usuario01',
            'estado' => true,
        ]);

        $response = $this->actingAs($admin)->put('/usuarios/'.$user->id_usuario, [
            'id_rol' => $updatedRole->id_rol,
            'username' => 'usuario-editado',
            'password' => '',
            'password_confirmation' => '',
            'estado' => '0',
        ]);

        $response->assertRedirect('/usuarios/listado');
        $response->assertSessionHas('status_title', 'Usuario editado correctamente');
        $response->assertSessionHas('status', 'El usuario se editó correctamente.');

        $this->assertDatabaseHas('usuario', [
            'id_usuario' => $user->id_usuario,
            'id_rol' => $updatedRole->id_rol,
            'username' => 'usuario-editado',
            'estado' => false,
        ]);
    }

    public function test_user_creation_validation_failure_displays_notification(): void
    {
        $admin = $this->createAdministrator();
        $role = Role::factory()->create([
            'nombre' => 'Farmacia',
        ]);

        $response = $this->actingAs($admin)
            ->from('/usuarios/crear')
            ->followingRedirects()
            ->post('/usuarios', [
                'id_rol' => $role->id_rol,
                'username' => '',
                'password' => 'secret123',
                'password_confirmation' => 'secret123',
                'estado' => '1',
                'nombres' => 'Ana',
                'apellidos' => 'Lopez',
                'dpi' => '1111111111111',
                'direccion' => 'Malacatan',
            ]);

        $response->assertSeeText('No se pudo completar la acción');
        $response->assertSeeText('Revisa los datos del formulario antes de guardar.');
    }

    public function test_user_update_validation_failure_displays_notification(): void
    {
        $admin = $this->createAdministrator();
        $role = Role::factory()->create([
            'nombre' => 'Farmacia',
        ]);
        $duplicatedUser = User::factory()->create([
            'id_rol' => $role->id_rol,
            'username' => 'duplicado',
        ]);
        $user = User::factory()->create([
            'id_rol' => $role->id_rol,
            'username' => 'usuario01',
        ]);

        $response = $this->actingAs($admin)
            ->from('/usuarios/'.$user->id_usuario.'/editar')
            ->followingRedirects()
            ->put('/usuarios/'.$user->id_usuario, [
                'id_rol' => $role->id_rol,
                'username' => $duplicatedUser->username,
                'password' => '',
                'password_confirmation' => '',
                'estado' => '1',
            ]);

        $response->assertSeeText('No se pudo completar la acción');
        $response->assertSeeText('Revisa los datos del formulario antes de guardar.');
    }

    public function test_user_module_options_show_button_to_return_to_menu(): void
    {
        $admin = $this->createAdministrator();
        $role = Role::factory()->create([
            'nombre' => 'Farmacia',
        ]);
        $user = User::factory()->create([
            'id_rol' => $role->id_rol,
        ]);

        $this->actingAs($admin)
            ->get('/usuarios/listado')
            ->assertOk()
            ->assertSeeText('Volver a usuarios');

        $this->actingAs($admin)
            ->get('/usuarios/desactivar')
            ->assertOk()
            ->assertSeeText('Volver a usuarios');

        $this->actingAs($admin)
            ->get('/usuarios/'.$user->id_usuario.'/editar')
            ->assertOk()
            ->assertSeeText('Volver a usuarios');
    }

    public function test_non_administrator_cannot_access_user_module(): void
    {
        $labRole = Role::factory()->create([
            'nombre' => 'Laboratorio',
        ]);

        $user = User::factory()->create([
            'id_rol' => $labRole->id_rol,
        ]);

        $this->actingAs($user)
            ->get('/usuarios')
            ->assertForbidden();
    }

    private function createAdministrator(): User
    {
        $role = Role::factory()->create([
            'nombre' => 'Administrador',
            'descripcion' => 'Rol de prueba para administracion.',
        ]);

        return User::factory()->create([
            'id_rol' => $role->id_rol,
        ]);
    }
}
