<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferHistory extends Model
{
    protected $table = 'transfer_history';
    protected $primaryKey = 'id';

    public $incrementing = false;

    
    protected $fillable = [
        'id',
        'amount',
        'payee_user_id',
        'payer_user_id',
    ];
    
    protected $casts = [
        'id' => 'string',
        'payee_user_id' => 'string',
        'payer_user_id' => 'string',
    ];

    public function payeeUser()
    {
        return $this->belongsTo(User::class, 'payee_user_id', 'id');
    }
    
    public function payerUser()
    {
        return $this->belongsTo(User::class, 'payer_user_id', 'id');
    }
}