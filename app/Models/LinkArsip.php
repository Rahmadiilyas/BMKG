<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinkArsip extends Model
{
    protected $table = 'link_archives';
    protected $guarded = [];

    public function kategori(){
    return $this->belongsTo(Category::class, 'kategori_id');
}

}
