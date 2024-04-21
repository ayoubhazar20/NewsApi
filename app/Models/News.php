<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'category_id', 'subcategory_id', 'start_date', 'end_date'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

 
}