<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'id',
        'balance',
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
