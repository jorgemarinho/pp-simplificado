<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class People extends Model
{

    public $incrementing = false;

    protected $fillable = [
        'id',
        'full_name',
        'cpf',
        'phone',
        'user_id',
    ];

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
