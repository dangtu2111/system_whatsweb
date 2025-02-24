<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
