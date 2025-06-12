<?php

namespace App\Livewire\Category;

use Livewire\Component;
use App\Models\Category;
use App\Models\Product;
use App\Models\Inventory;

class CategoryDelete extends Component
{
    public $confirmingCategoryDeletion = false;
    public $categoryToDelete = null;
    public $hasProductsWithStock = false;
    
    protected $listeners = [
        'showDeleteModal' => 'confirmDelete',
    ];

    public function confirmDelete($Category_ID)
    {
        $this->categoryToDelete = Category::find($Category_ID);
        
        if ($this->categoryToDelete) {
            $this->hasProductsWithStock = Inventory::whereHas('product', function($query) use ($Category_ID) {
                $query->where('Category_ID', $Category_ID)
                      ->where('Removed', 0);
            })->where('Current_Stock', '>', 0)->exists();
            
            $this->confirmingCategoryDeletion = true;
        }
    }

    public function deleteCategory()
    {
        if ($this->categoryToDelete) {
            if ($this->hasProductsWithStock) {
                $this->dispatch('category-notify', [
                    'title' => '¡Error al eliminar!',
                    'message' => 'Esta categoría contiene productos con stock y no se puede eliminar.'
                ]);
                return;
            }
            $this->categoryToDelete->update(['Removed' => 1]);
            Product::where('Category_ID', $this->categoryToDelete->Category_ID)
                  ->update(['Removed' => 1]);

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