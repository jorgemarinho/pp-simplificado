<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class People extends Model
{

    protected $fillable = [
        'full_name',
        'cpf',
        'phone',
        'user_id',
    ];

    protected $casts = [
        'id' => 'uuid',
        'user_id' => 'uuid',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
