<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];
        public function links()
    {
        return $this->hasMany(Link::class, 'kategori_id');
    }
}
