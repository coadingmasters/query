<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = ['email'];

    protected function casts(): array
    {
        return ['unsubscribed_at' => 'datetime'];
    }
}
