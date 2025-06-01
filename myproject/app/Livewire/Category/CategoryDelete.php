<?php

namespace App\Livewire\Category;

use Livewire\Component;
use App\Models\Category;

class CategoryDelete extends Component
{
    public $confirmingCategoryDeletion = false;
    public $categoryToDelete = null;
    
    protected $listeners = [
        'showDeleteModal' => 'confirmDelete',
    ];

    /**
     * Carga la categoría a eliminar y muestra el modal
     *
     * @param int $Category_ID
     */
    public function confirmDelete($Category_ID)
    {
        $this->categoryToDelete = Category::find($Category_ID);
        if ($this->categoryToDelete) {
            $this->confirmingCategoryDeletion = true;
        }
    }

    public function deleteCategory()
    {
        if ($this->categoryToDelete) {
            $this->categoryToDelete->update(['Removed' => 1]);

            $this->confirmingCategoryDeletion = false;
            $this->categoryToDelete = null;
            $this->dispatch('category-notify', [
                'title' => '¡Eliminación exitosa!',
                'message' => 'Categoría eliminada correctamente.'
            ]);
            
            $this->dispatch('categoryDeleted');
            $this->reset();
        }
    }

    public function render()
    {
        return view('livewire.category.category-delete');
    }
}