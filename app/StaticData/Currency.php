<?php

namespace App\StaticData;

/**
 * Class Currency
 * @package App\StaticData
 */
class Currency
{
    const EUR = 'EUR';
    const BGN = 'BGN';
    const USD = 'USD';
    const GBP = 'GBP';

    /**
     * @var array
     */
    public static $all = [
        self::BGN,
        self::EUR,
        self::USD,
        self::GBP,
    ];
}