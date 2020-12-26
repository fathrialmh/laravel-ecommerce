<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function getStatusLabelAttribute()
{
    if ($this->status == 0) {
        return '<span class="badge badge-secondary">Menunggu Konfirmasi</span>';
    }
    return '<span class="badge badge-success">Diterima</span>';
}
}
