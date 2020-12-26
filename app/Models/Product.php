<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function setSlugAttribute($value){
        $this->attributes['slug'] = Str::slug($value);
    }

    public function getStatusLabelAttribute(){
        
        if($this->status == 0){
            return '<span class="badge badge-secondary">Draft</span>';
        }
            return'<span class="badge badge-success">Aktif</span>';
    }

    public function category(){
        return $this->belongsTo(category::class);
    }
}
