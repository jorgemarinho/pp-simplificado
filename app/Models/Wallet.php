<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{

    protected $fillable = [
        'balance',
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
