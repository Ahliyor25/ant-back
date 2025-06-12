<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'icon',
        'text',
        'order',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

}
