<?php

namespace Tests\Feature;

use App\Models\Produto;
use App\Services\ProdutoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ProdutoServiceTest extends TestCase
{
    use RefreshDatabase;

    private function criarProduto(): Produto
    {
        return Produto::create([
            'nome' => 'Cimento',
            'preco_custo' => 100,
            'preco' => 120,
            'estoque' => 5,
        ]);
    }

    public function test_update_atualiza_produto_com_margem_correta(): void
    {
        $produto = $this->criarProduto();
        $service = new ProdutoService();

        $atualizado = $service->update($produto, [
            'nome' => 'Cimento CP II',
            'preco_custo' => 200,
            'preco' => 250,
        ]);

        $this->assertEquals('Cimento CP II', $atualizado->nome);
        $this->assertDatabaseHas('produtos', [
            'id' => $produto->id,
            'nome' => 'Cimento CP II',
            'preco_custo' => 200,
            'preco' => 250,
        ]);
    }

    public function test_update_nao_atualiza_produto_com_margem_abaixo_da_minima(): void
    {
        $produto = $this->criarProduto();
        $service = new ProdutoService();

        try {
            $service->update($produto, ['preco' => 105]);
            $this->fail('Era esperado uma ValidationException.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('preco', $e->errors());
        }

        $this->assertDatabaseHas('produtos', [
            'id' => $produto->id,
            'preco' => 120,
        ]);
    }
}
