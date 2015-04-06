<?php

class Filter {
    
    /**
     * Strips HTM tags from string
     * 
     * @param String $string
     * @return String
     */
    public static function stripHTML($string) {
        return preg_replace('/(<.*?>)|(&.*?;)/', '', $string);
    }
    
}