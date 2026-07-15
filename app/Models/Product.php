<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['catalog', 'title', 'description', 'category', 'sub_category', 'sector', 'image'];
}
