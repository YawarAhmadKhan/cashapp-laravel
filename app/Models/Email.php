<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;
    protected $fillable = [
        'messageId',
        'appId',
        'recipient',
        'amount',
        'payment_note',
        'identifier',
        'status',
        'from',
        'subject',
        'refund-amount',
        'refund-note',
        'date',
        'sellorderBtc',
        'destination',
        'too',
    ];
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
