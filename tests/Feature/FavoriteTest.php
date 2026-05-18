<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    /** #15 — Guest es redirigido al intentar ver favoritos. */
    #[Test]
    public function guest_is_redirected_from_favorites_page(): void
    {
        $this->get('/favorites')->assertRedirect('/login');
    }

    /** #16 — Usuario autenticado puede acceder a favoritos (HTTP 200). */
    #[Test]
    public function authenticated_user_can_view_favorites_page(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/favorites')->assertStatus(200);
    }

    /** #17 — El toggle de favorito requiere autenticación. */
    #[Test]
    public function toggle_favorite_requires_authentication(): void
    {
        $this->postJson('/favorites/toggle', [
            'mal_id' => 5114,
            'title'  => 'Fullmetal Alchemist: Brotherhood',
        ])->assertStatus(401);
    }

    /** #18 — Usuario autenticado puede agregar un favorito a la BD. */
    #[Test]
    public function authenticated_user_can_add_favorite(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->postJson('/favorites/toggle', [
            'mal_id'    => 5114,
            'title'     => 'Fullmetal Alchemist: Brotherhood',
            'image_url' => 'https://example.com/fma.jpg',
            'score'     => 9.11,
            'type'      => 'TV',
            'year'      => 2009,
        ])
        ->assertStatus(200)
        ->assertJson(['status' => 'added']);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'mal_id'  => 5114,
            'title'   => 'Fullmetal Alchemist: Brotherhood',
        ]);
    }
}
