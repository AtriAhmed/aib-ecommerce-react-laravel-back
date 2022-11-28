<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BDT extends Model
{
    protected $table ='bdtype';
    protected $fillable=[
        'type',
        'descrip',
        'costs',
    ];
    use HasFactory;
}
