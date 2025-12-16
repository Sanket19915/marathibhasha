<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name_mr', 'slug'];

    public function entries()
    {
        return $this->hasMany(DictionaryEntry::class);
    }
}

