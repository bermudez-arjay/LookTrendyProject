<?php

namespace App\Livewire\Category;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;

class CategoryComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';
    protected $listeners = ['categoryCreated', 'categoryUpdated', 'categoryDeleted', 'llamarFuncion'];

    public $keyWord;

    public function llamarFuncion()
    {
        info("¡Función llamada desde otro componente!");
    }

    public function clearFilter()
    {
        $this->keyWord = '';
        $this->resetPage();
    }

    public function someMethod()
    {
        $this->dispatch('categoryCreated');
        $this->dispatch('categoryUpdated');
        $this->dispatch('categoryDeleted');
    }

    public function filteredCategories()
    {
        $keyWord = '%' . $this->keyWord . '%';

        return Category::where('Removed', 0)
            ->where(function ($query) use ($keyWord) {
                $query->orWhere('Category_ID', 'LIKE', $keyWord)
                      ->orWhere('Category_Name', 'LIKE', $keyWord)
                      ->orWhere('Category_Description', 'LIKE', $keyWord);
            })
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.category.category-component', [
            'categories' => $this->filteredCategories()
        ])->layout('layouts.app');
    }
}