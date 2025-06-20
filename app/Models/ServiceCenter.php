<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCenter extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_id','name','description',
        'address','phone','email','lat','lng'
    ];

    public function region() {
        return $this->belongsTo(Region::class);
    }

}
