<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devi extends Model
{
    protected $table ='devis';
    protected $fillable=[
        'bds_id',
        'user_id',
        'numS',
        'descrip',
        'costs',
        'status'
    ];

    protected $with = ['user','product'];
    public function user(){
        return $this->belongsTo(User::class, 'user_id','id');
    }

    public function product(){
        return $this->belongsTo(User::class, 'numS','id');
    }
    use HasFactory;
}
