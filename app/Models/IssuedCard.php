<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssuedCard extends Model
{
    use HasFactory;
    public $timestamps = false;

    public $fillable = [
        'card_number', 'card_type', 'is_valid'
    ];

    const CARD_TYPE_SILVER = 1;
    const CARD_TYPE_GOLD = 2;
    const CARD_TYPE_PLATINUM = 3;

    public function getDiscountRatesByCard()
    {
        return [
            self::CARD_TYPE_SILVER   => config('app.silver_discount'),
            self::CARD_TYPE_GOLD     => config('app.gold_discount'),
            self::CARD_TYPE_PLATINUM => config('app.platinum_discount'),
        ];
    }

    public function getCardTypeNames()
    {
        return [
            self::CARD_TYPE_SILVER   => 'Silver card',
            self::CARD_TYPE_GOLD     => 'Gold card',
            self::CARD_TYPE_PLATINUM => 'Platinum card',
        ];
    }

    public function getCardTypeAttribute($value)
    {
       return $this->getCardTypeNames()[$value];
    }
}
