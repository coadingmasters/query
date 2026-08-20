<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleFeedback extends Model
{
    protected $table = 'article_feedback';

    protected $fillable = ['slug', 'helpful'];

    protected function casts(): array
    {
        return ['helpful' => 'boolean'];
    }
}
