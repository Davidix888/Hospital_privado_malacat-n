<?php

namespace Tests\Feature\Authorization;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrator_can_access_all_modules(): void
    {
        $user = $this->createUserWithRole('Administrador');

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSeeText('Gestión de usuarios')
            ->assertSeeText('Módulo de laboratorio')
            ->assertSeeText('Módulo de farmacia')
            ->assertSeeText('Resúmenes generales');

        $this->actingAs($user)->get('/usuarios')->assertOk();
        $this->actingAs($user)->get('/laboratorio')->assertOk();
        $this->actingAs($user)->get('/farmacia')->assertOk();
        $this->actingAs($user)->get('/resumenes')->assertOk();
        $this->actingAs($user)->get('/resumenes/exportar')->assertOk();
    }

    public function test_laboratory_user_can_only_access_laboratory_module(): void
    {
        $user = $this->createUserWithRole('Laboratorio');

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSeeText('Módulo de laboratorio')
            ->assertDontSeeText('Gestión de usuarios')
            ->assertDontSeeText('Módulo de farmacia')
            ->assertDontSeeText('Resúmenes generales');

        $this->actingAs($user)->get('/laboratorio')->assertOk();
        $this->actingAs($user)->get('/usuarios')->assertForbidden();
        $this->actingAs($user)->get('/farmacia')->assertForbidden();
        $this->actingAs($user)->get('/resumenes')->assertForbidden();
    }

    public function test_pharmacy_user_can_only_access_pharmacy_module(): void
    {
        $user = $this->createUserWithRole('Farmacia');

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSeeText('Módulo de farmacia')
            ->assertDontSeeText('Gestión de usuarios')
            ->assertDontSeeText('Módulo de laboratorio')
            ->assertDontSeeText('Resúmenes generales');

        $this->actingAs($user)->get('/farmacia')->assertOk();
        $this->actingAs($user)->get('/usuarios')->assertForbidden();
        $this->actingAs($user)->get('/laboratorio')->assertForbidden();
        $this->actingAs($user)->get('/resumenes')->assertForbidden();
    }

    public function test_licensed_user_can_access_summaries_and_export(): void
    {
        $user = $this->createUserWithRole('Licenciado');

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSeeText('Resúmenes generales')
            ->assertDontSeeText('Gestión de usuarios')
            ->assertDontSeeText('Módulo de laboratorio')
            ->assertDontSeeText('Módulo de farmacia');

        $this->actingAs($user)->get('/resumenes')->assertOk();
        $this->actingAs($user)->get('/resumenes/exportar')->assertOk();
        $this->actingAs($user)->get('/usuarios')->assertForbidden();
        $this->actingAs($user)->get('/laboratorio')->assertForbidden();
        $this->actingAs($user)->get('/farmacia')->assertForbidden();
    }

    private function createUserWithRole(string $roleName): User
    {
        $role = Role::factory()->create([
            'nombre' => $roleName,
            'descripcion' => 'Rol de prueba para autorizacion.',
        ]);

        return User::factory()->create([
            'id_rol' => $role->id_rol,
        ]);
    }
}
