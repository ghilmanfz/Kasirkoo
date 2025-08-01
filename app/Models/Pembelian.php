<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table      = 'pembelian';
    protected $primaryKey = 'id_pembelian';
    protected $guarded    = [];   // sudah ada

    public function detail()   
    {
        return $this->hasMany(
            PembelianDetail::class,
            'id_pembelian',   
            'id_pembelian'    
        );
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id_supplier');
    }
}
