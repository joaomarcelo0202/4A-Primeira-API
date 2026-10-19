<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProdutoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /* $metodo = $this->isMethod('post');

        if($metodo == true){
            $regra = 'required';
        }else{
            $regra = 'sometimes';
        }*/
        $regra = $this->isMethod('post') ? 'required' : 'sometimes';
    
        return [
            'nome' => [$regra, 'string', 'max:255'], 
            'descricao' => 'nullable|string',
            'preco_custo' => [$regra, 'numeric', 'min:0'],
            'preco' => [$regra, 'numeric', 'min:0'],
            'estoque' => 'sometimes|integer|min:0',
        ];
    }
}
