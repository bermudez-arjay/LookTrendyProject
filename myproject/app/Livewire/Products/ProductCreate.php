<?php

namespace App\Livewire\Products;

use Livewire\Component;
use App\Models\Product;

class ProductCreate extends Component
{
    public $open = false;
    public $Product_Name, $Description, $Unit_Price, $Category;
    
    public $categories = [
        'Calzado', 
        'Bolsos', 
        'Higiene', 
        'Ropa de Dama',
        'Ropa de Caballero', 
        'Ropa Infantil', 
        'Artículos para el Hogar'
    ]; 

    protected $rules = [
        'Product_Name' => [
            'required',
            'string',
            'max:100',
            'regex:/^[\pL\s\-]+$/u', 
            'unique:products,Product_Name' 
        ],
        'Description' => [
            'nullable',
            'string',
            'max:255',
            'regex:/^[\pL\s\-.,;:()\d]+$/u' 
        ],
        'Unit_Price' => [
            'required',
            'numeric',
            'min:0',
            'max:999999.99',
            'regex:/^\d+(\.\d{1,2})?$/' 
        ],
        'Category' => [
            'required',
            'string',
            'max:50',
            'in:Calzado,Bolsos,Higiene,Ropa de Dama,Ropa de Caballero,Ropa Infantil,Artículos para el Hogar'
        ]
    ];

    protected function messages()
    {
        return  [
            'Product_Name.required' => 'El nombre del producto es obligatorio.',
            'Product_Name.max' => 'El nombre no debe exceder 100 caracteres.',
            'Product_Name.regex' => 'El nombre solo puede contener letras, espacios y guiones.',
            'Product_Name.unique' => 'Este nombre de producto ya existe.',
            'Description.required' => 'La descripción es obligatoria.',
            'Description.string' => 'La descripción debe ser una cadena de texto.',
            'Description.max' => 'La descripción no debe exceder 255 caracteres.',
            'Description.regex' => 'La descripción contiene caracteres no permitidos.',
            'Unit_Price.required' => 'El precio unitario es obligatorio.',
            'Unit_Price.numeric' => 'El precio debe ser un valor numérico.',
            'Unit_Price.min' => 'El precio no puede ser negativo.',
            'Unit_Price.max' => 'El precio no puede exceder 999,999.99.',
            'Unit_Price.regex' => 'El precio debe tener máximo 2 decimales.',
            'Category.required' => 'La categoría es obligatoria.',
            'Category.in' => 'Seleccione una categoría válida.'
        ];
    }

    protected $listeners = ['openCreateProductModal' => 'openModal'];

    public function openModal()
    {
        $this->resetForm();
        $this->resetValidation(); 
        $this->open = true;
    }

    public function closeModal()
    {
        $this->open = false;
        $this->resetValidation();
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

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $validatedData = $this->validate();
        try {
            Product::create([
                'Product_Name' => trim($validatedData['Product_Name']),
                'Description' => $validatedData['Description'] ? trim($validatedData['Description']) : null,
                'Unit_Price' => round($validatedData['Unit_Price'], 2),
                'Category' => $validatedData['Category'],
                'Removed' => 0 
            ]);

            $this->resetForm();
            $this->dispatch('productCreated');
            $this->dispatch('product-notify', [
                'title' => 'Producto creado exitosamente.',
                'type' => 'success'
            ]);
            $this->closeModal();

        } catch (\Exception $e) {
            $this->dispatch('product-notify', [
                'title' => 'Error al crear el producto: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.products.product-create');
    }
}