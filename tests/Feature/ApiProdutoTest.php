<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiProdutoTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_cria_produto_com_margem_correta(): void
    {
        $this->postJson('/api/produtos', [
            'nome' => 'Lajota',
            'preco_custo' => 100,
            'preco' => 110,
        ])->assertCreated();

        $this->assertDatabaseHas('produtos', ['nome' => 'Lajota']);
    }

    public function test_nao_cria_produto_com_margem_abaixo_da_minima(): void
    {
        // Custo 100 -> preço mínimo 110. Com 105 deve ser recusado.
        $this->postJson('/api/produtos', [
            'nome' => 'Tijolo',
            'preco_custo' => 100,
            'preco' => 105,
        ])->assertStatus(422)
          ->assertJsonValidationErrors('preco');

        $this->assertDatabaseMissing('produtos', ['nome' => 'Tijolo']);
    }
}
