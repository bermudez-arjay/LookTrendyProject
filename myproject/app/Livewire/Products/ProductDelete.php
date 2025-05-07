<?php

namespace App\Livewire\Products;

use Livewire\Component;
use App\Models\Product;

class ProductDelete extends Component
{
    public $confirmingProductDeletion = false;
    public $productToDelete = null;

    protected $listeners = [
        'showDeleteModal' => 'confirmDelete'
    ];

    public function confirmDelete($Product_ID)
    {
        $this->productToDelete = Product::find($Product_ID);
        $this->confirmingProductDeletion = true;
    }

    // Método para eliminar el producto
    public function deleteProduct()
    {
        if ($this->productToDelete) {
            $this->productToDelete->update(['Removed' => 1]);

         

            $this->confirmingProductDeletion = false;
            $this->productToDelete = null;
            $this->dispatch('productDeleted'); 
            $this->dispatch('product-notify', [
                'title' => '¡Eliminación exitosa!',
                'message' => 'Producto eliminado correctamente.'
            ]);
            
           $this->reset();
           
        }
    }
    public function render()
    {
        return view('livewire.products.product-delete');
    }
}
