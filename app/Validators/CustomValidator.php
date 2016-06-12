<?php

namespace App\Validators;

use App\StaticData\Currency;

class CustomValidator
{
    /**
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validateFloat($attribute, $value, $parameters, $validator)
    {
        if (0 === preg_match('/\d{1,}\.\d.{1,}/', $value)) {
            return false;
        }

        return true;
    }

    /**
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validateCurrency($attribute, $value, $parameters, $validator)
    {
        return in_array($value, Currency::$all);
    }
}