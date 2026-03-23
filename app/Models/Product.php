<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Codebyray\ReviewRateable\Traits\ReviewRateable;

class Product extends Model
{
    use ReviewRateable;

    protected $fillable = [
        'name',
        'description'
    ];
}
