<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetaSetting extends Model
{
    use HasFactory;
   
     protected $fillable = [
        'title',
        'description',
        'keywords',
        'url',
        'image',
        'site_name',
        'canonical'
     ];
}
