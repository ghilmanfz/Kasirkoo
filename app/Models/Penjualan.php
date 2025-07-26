<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';
    protected $primaryKey = 'id_penjualan';
    protected $guarded = [];
    
    protected $casts = [
        'void_requested_at' => 'datetime',
        'void_approved_at' => 'datetime',
    ];

    public function member()
    {
        return $this->hasOne(Member::class, 'id_member', 'id_member');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }
    
    public function voidRequestedBy()
    {
        return $this->belongsTo(User::class, 'void_requested_by');
    }
    
    public function voidApprovedBy()
    {
        return $this->belongsTo(User::class, 'void_approved_by');
    }
     public function detail()
    {

        return $this->hasMany(PenjualanDetail::class,
                              'id_penjualan',
                              'id_penjualan');
    }
}
