<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    protected $fillable = [
        'title',
        'Author',
        'ISBN',
        'Description',
        'archive'
    ];
    
    protected $cast = [
        'archive' => 'integer'
    ];
}