<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    use HasFactory;

    protected $fillable = ['section_key', 'title', 'subtitle', 'is_visible', 'display_order'];

    protected function casts(): array
    {
        return ['is_visible' => 'boolean'];
    }
}