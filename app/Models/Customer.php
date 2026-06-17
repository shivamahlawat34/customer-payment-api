<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone_number',
        'email',
        'payment_amount',
        'payment_status'
    ];

    public function logs()
    {
        return $this->hasMany(CommunicationLog::class);
    }
}
