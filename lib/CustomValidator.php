<?php

namespace app\lib;

class CustomValidator extends \Illuminate\Validation\Validator {

    public function validateFacebook($attribute, $value, $parameters) {
        if (str_contains($value, 'facebook')) {
            return true;
        } else {
            return false;
        }
    }

    public function validateGooglePlus($attribute, $value, $parameters) {
        if (str_contains($value, 'plus.google')) {
            return true;
        } else {
            return false;
        }
    }

    public function validateTwitter($attribute, $value, $parameters) {
        if (str_contains($value, 'twitter')) {
            return true;
        } else {
            return false;
        }
    }

    public function validateAlphaCyr($attribute, $value, $parameters) {
        if (mb_ereg_match('^[явертъуиопшщюлкйхгфдсазьцжбнмчЯВЕРТЪУИОПШЩЮЛКЙХГФДСАЗЬЦЖБНМЧ-–A-z]*$', $value)) {
            return true;
        } else {
            return false;
        }
    }

    public function validatePhone($attribute, $value, $parameters) {
        if (preg_match('/^[0-9\+]*$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    public function validateTime($attribute, $value, $parameters) {
        if (preg_match('/\d\d:\d\d/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    public function validateDateAhead($attribute, $value, $parameters) {
        $now = strtotime(date('Y-m-d'));
        $date = strtotime($value);

        if ($now <= $date) {
            return true;
        } else {
            return false;
        }
    }

    public function validatePosition($attribute, $value, $parameters) {
        if ((int) $value < 1 && (int) $value > 99) {
            return false;
        } else {
            return true;
        }
    }
    
    public function validateFavicon($attribute, $value, $parameters) {
        $mime = $value->getMimeType();
        if (in_array(str_replace('image/', '', $mime), $parameters)) {
            return true;
        } else {
            return false;
        }
    }

}
