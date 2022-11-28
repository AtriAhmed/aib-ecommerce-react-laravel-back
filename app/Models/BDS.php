<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BDS extends Model
{
    protected $table ='bdsheets';
    protected $fillable=[
        'user_id',
        'numS',
        'descrip',
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
