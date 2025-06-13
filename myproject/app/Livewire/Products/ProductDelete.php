<?php

namespace App\Livewire\Products;

use Livewire\Component;
use App\Models\Product;
use App\Models\Inventory; 

class ProductDelete extends Component
{
    public $confirmingProductDeletion = false;
    public $productToDelete = null;
    public $hasInventory = false;

    protected $listeners = [
        'showDeleteModal' => 'confirmDelete'
    ];

    public function confirmDelete($Product_ID)
    {
        $this->productToDelete = Product::find($Product_ID);
        
        $this->hasInventory = $this->checkProductInventory($Product_ID);
        
        $this->confirmingProductDeletion = true;
    }
    protected function checkProductInventory($productId)
    {
        return Inventory::where('Product_ID', $productId)
                       ->where('Current_Stock', '>', 0)
                       ->exists();
    }

    public function deleteProduct()
    {
        if ($this->productToDelete) {
            if ($this->checkProductInventory($this->productToDelete->Product_ID)) {
                $this->dispatch('product-notify', [
                    'type' => 'error',
                    'title' => '¡Error al eliminar!',
                    'message' => 'Este producto tiene stock en inventario y no se puede eliminar.'
                ]);
                return;
            }

            $this->productToDelete->update(['Removed' => 1]);

            $this->confirmingProductDeletion = false;
            $this->productToDelete = null;
            $this->dispatch('productDeleted'); 
            $this->dispatch('product-notify', [
                'type' => 'success',
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