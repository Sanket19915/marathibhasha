<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DictionaryEntry extends Model
{
    protected $fillable = ['category_id', 'word_en', 'meaning_mr'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
