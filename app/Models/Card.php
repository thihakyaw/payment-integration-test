<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'buyer_id',
        'name_on_card',
        'card_brand',
        'card_last_four_number',
        'card_expiry_month',
        'card_expiry_year',
        'stripe_charge_id'
    ];
}
