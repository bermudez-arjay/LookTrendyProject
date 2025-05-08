<?php
namespace App\Livewire\Products;

use Livewire\Component;
use App\Models\Product;

class ProductEdit extends Component
{
    public $open = false;

    public $productId;
    public $Product_Name, $Description, $Unit_Price, $Category;
    public $categories = ['Calzado', 'Bolsos', 'Higiene', 'Ropa de Dama','Ropa de Caballero', 'Ropa Infantil', 'Artículos para el Hogar']; 
    protected $listeners = ['editProductById' => 'loadProductByID'];

    // Reglas de validación
    protected function rules()
    {
        return [
            'Product_Name' => 'required|string|max:100',
            'Description' => 'nullable|string|max:255',
            'Unit_Price' => 'required|numeric|min:0',
            'Category' => 'required|string|max:50'
        ];
    }


    public function loadProductByID($Product_ID)
    {
        $this->open = true; 
        // Buscar usuario por email
        $product = Product::where('Product_ID', $Product_ID)->firstOrFail();

        // Cargar los datos del producto en el modal
        $this->productId= $product->Product_ID;
        $this->Product_Name = $product->Product_Name;
        $this->Description = $product->Description;
        $this->Category = $product->Category;
        $this->Unit_Price = $product->Unit_Price;
       
    }

    // Cerrar el modal y resetear los campos
    public function closeModal()
    {
        $this->resetForm();
        $this->open = false;
    }

    // Resetear los valores del formulario
    public function resetForm()
    {
        $this->productIdId = '';
        $this->Product_Name ='';
        $this->Description = '';
        $this->Category ='';
        $this->Unit_Price = '';
    }

    public function update()
    {
  
        $this->validate();
        $product = Product::findOrFail($this->productId);
        $product->update([
            'Product_Name' => $this->Product_Name,
            'Description' => $this->Description,
            'Unit_Price' => $this->Unit_Price,
            'Category' => $this->Category
            
        ]);

        $this->resetForm();
        $this->dispatch('productUpdated');
        $this->dispatch('product-notify', ['title' => 'Producto actualizado exitosamente.']);
        $this->closeModal();
    }

    public function refreshProducts()
{
    $this->resetPage(); 
}
    public function render()
    {
        return view('livewire.products.product-edit');
    }
}

