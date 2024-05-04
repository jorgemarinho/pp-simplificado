<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';
    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'cnpj',
        'people_id',
    ];
    
    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
    ];
    
    public function people()
    {
        return $this->belongsTo(People::class, 'people_id', 'id');
    }
}
