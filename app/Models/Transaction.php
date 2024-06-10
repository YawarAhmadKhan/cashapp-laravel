<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'email_id',
        'transaction_type',
        'amount',
        'status',
        'notes',
        'btcdetails'
    ];
    public function emails()
    {
        return $this->belongsTo(Email::class);
    }
}
