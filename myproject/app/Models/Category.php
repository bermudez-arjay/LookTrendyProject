<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Category extends Model
{
    use HasFactory;
    /**
     * The primary key for the model.
     *
     * @var string
     */
      protected $primaryKey = 'Category_ID';
    public $timestamps = false;

    protected $fillable = ['Category_Name', 'Category_Description'];
    
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}