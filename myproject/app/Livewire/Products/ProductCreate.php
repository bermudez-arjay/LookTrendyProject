<?php

namespace App\Livewire\Products;

use Livewire\Component;
use App\Models\Product;

class ProductCreate extends Component
{
    public $open = false;

    // Propiedades alineadas con los campos del producto
    public $Product_Name, $Description, $Unit_Price, $Category;
    public $categories = ['Calzado', 'Bolsos', 'Higiene', 'Ropa de Dama','Ropa de Caballero', 'Ropa Infantil', 'Artículos para el Hogar, ']; 

    protected $rules = [
        'Product_Name' => 'required|string|max:100',
        'Description' => 'nullable|string|max:255',
        'Unit_Price' => 'required|numeric|min:0',
        'Category' => 'required|string|max:50'
    ];

    protected $listeners = ['openCreateProductModal' => 'openModal'];

    public function openModal()
    {
        $this->resetForm();
        $this->open = true;
    }

    public function closeModal()
    {
        $this->open = false;
    }

    public function resetForm()
    {
        $this->reset([
            'Product_Name',
            'Description',
            'Unit_Price',
            'Category'
        ]);
    }

    public function save()
    {
        $this->validate();

        Product::create([
            'Product_Name' => $this->Product_Name,
            'Description' => $this->Description,
            'Unit_Price' => $this->Unit_Price,
            'Category' => $this->Category,
            'Removed' => 0 
        ]);

        $this->resetForm();
        $this->dispatch('productCreated');
        $this->dispatch('product-notify', ['title' => 'Producto creado exitosamente.']);
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.products.product-create');
    }
}
