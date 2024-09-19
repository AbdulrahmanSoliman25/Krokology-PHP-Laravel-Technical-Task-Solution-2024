<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'image'];
    const INDEX_COLUMNS = ['id', 'title', 'description', 'image'];
    const SEARCH_COLUMNS = ['id', 'title', 'description'];
    
}
