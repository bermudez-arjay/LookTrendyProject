<?php

namespace App\Livewire\Category;

use Livewire\Component;
use App\Models\Category;

class CategoryCreate extends Component
{
    public $open = false;
    public $Category_Name, $Category_Description, $Category_Status = 1;
    
    protected $rules = [
        'Category_Name' => [
            'required',
            'string',
            'max:100',
            'min:3',
            'unique:category,Category_Name',
            'regex:/^[\pL\s\-]+$/u',
            'not_regex:/[\d]/'
        ],
        'Category_Description' => [
            'nullable',
            'string',
            'max:255',
        ],
    ];

    public function messages()
    {
        return [
            'Category_Name.required' => 'El nombre de la categoría es obligatorio.',
            'Category_Name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'Category_Name.max' => 'El nombre no debe exceder 100 caracteres.',
            'Category_Name.regex' => 'Solo letras, espacios y guiones.',
            'Category_Name.not_regex' => 'El nombre no puede contener números.',
            'Category_Name.unique' => 'Esta categoría ya está registrada.',
            
            'Category_Description.max' => 'La descripción no debe exceder 255 caracteres.',
        ];
    }

    protected $listeners = ['openCreateCategoryModal' => 'openModal'];

    public function openModal()
    {
        $this->resetForm();
        $this->resetValidation();
        $this->open = true;
    }

    public function closeModal()
    {
        $this->open = false;
    }

    public function resetForm()
    {
        $this->reset([
            'Category_Name',
            'Category_Description',
        ]);

    }

    public function updated($propertyName)
    {
        
        
        // Capitalizar automáticamente el nombre mientras se escribe
        if ($propertyName === 'Category_Name') {
            $this->Category_Name = ucwords(strtolower($this->Category_Name));
        }
    }

    public function save()
    {
        $this->validate();
        
        try {
            $category = Category::create([
                'Category_Name' => ucwords(strtolower($this->Category_Name)),
                'Category_Description' => $this->Category_Description,
                'Removed' => 0
            ]);
            
            $this->dispatch('categoryCreated', $category->Category_ID);
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Categoría creada exitosamente.',
                'timeout' => 3000
            ]);
            $this->closeModal();
            
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al crear la categoría: '.$e->getMessage(),
                'timeout' => 5000
            ]);
        }
    }

    public function render()
    {
        return view('livewire.category.category-create');
    }
}