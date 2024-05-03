<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'company';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'cnpj',
        'people_id',
    ];
    
    public function people()
    {
        return $this->belongsTo(People::class, 'people_id', 'id');
    }
}
