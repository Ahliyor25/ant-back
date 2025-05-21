<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{

    protected $fillable = [
        'name',
        'code',
        'is_default',
    ];

    public $casts = [
        'is_default' => 'boolean',
    ];

    public function banners()
    {
        return $this->hasMany(Banner::class);
    }

}
