<?php

namespace App\Livewire\Category;

use Livewire\Component;
use App\Models\Category;

class CategoryEdit extends Component
{
    public $open = false;
    public $Category_ID;
    public $Category_Name, $Category_Description, $Category_Status;
    
    protected $rules = [
        'Category_Name' => [
            'required',
            'string',
            'max:100',
            'min:3',
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
            
            'Category_Description.max' => 'La descripción no debe exceder 255 caracteres.',
        ];
    }

    protected $listeners = ['editCategoryById' => 'loadCategoryById'];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        
        // Capitalizar automáticamente el nombre mientras se escribe
        if ($propertyName === 'Category_Name') {
            $this->Category_Name = ucwords(strtolower($this->Category_Name));
        }
    }

    public function loadCategoryById($Category_ID)
    {
        $this->open = true; 
        $category = Category::where('Category_ID', $Category_ID)->firstOrFail();
        
        $this->Category_ID = $category->Category_ID;
        $this->Category_Name = $category->Category_Name;
        $this->Category_Description = $category->Category_Description;
    }

    public function closeModal()
    {
        $this->resetForm();
        $this->open = false;
    }

    public function resetForm()
    {   
        $this->reset([
            'Category_ID',
            'Category_Name',
            'Category_Description',
        ]);
        $this->resetValidation();
    }

    public function update()
    {
        // Agregar regla de validación única ignorando el registro actual
        $this->rules['Category_Name'] = [
            'required',
            'string',
            'max:100',
            'min:3',
            'regex:/^[\pL\s\-]+$/u',
            'not_regex:/[\d]/',
            'unique:category,Category_Name,'.$this->Category_ID.',Category_ID'
        ];

        $this->validate();

        $category = Category::findOrFail($this->Category_ID);
        
        $category->update([
            'Category_Name' => ucwords(strtolower($this->Category_Name)),
            'Category_Description' => $this->Category_Description,
        ]);
       
        $this->resetForm();
        $this->dispatch('categoryUpdated');
        $this->dispatch('category-notify', [
            'message' => 'Categoría actualizada exitosamente.',
            'type' => 'success'
        ]);
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.category.category-edit');
    }
}