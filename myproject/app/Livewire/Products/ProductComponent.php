<?php

namespace App\Livewire\Products;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;

class ProductComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';
    protected $listeners = ['productCreated','productUpdated','productDeleted','llamarFuncion'];

    public function llamarFuncion()
    {
        // Código que quieres ejecutar
        info("¡Función llamada desde otro componente!");
    }
    public $keyWord;
    public function clearFilter()
{
    $this->searchName = '';
    $this->resetPage();
}
public function someMethod()
{
    $this->dispatch('productCreated');
    $this->dispatch('productUpdated');
    $this->dispatch('productDeleted');
}

public function filteredProducts()
{
    $keyWord = '%' . $this->keyWord . '%';

    return Product::where('Removed', 0)
        ->where(function ($query) use ($keyWord) {
            $query->where('Product_ID', 'LIKE', $keyWord)
            ->orWhere('Product_Name', 'LIKE', $keyWord)
            ->orWhere('Description', 'LIKE', $keyWord)
            ->orWhere('Category', 'LIKE', $keyWord)
            ->orWhere('Unit_Price', 'LIKE', $keyWord);
        })
        ->paginate(10);
}

    public function render()

    {
        return view('livewire.products.product-component', [
        'products' => $this->filteredProducts()
    ])->layout('layouts.app');
    }
}
