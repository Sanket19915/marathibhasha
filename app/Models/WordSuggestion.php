<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WordSuggestion extends Model
{
    protected $fillable = [
        'name',
        'email',
        'word',
        'meaning',
        'source_reference',
        'status',
        'admin_notes',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
