<?php

namespace App\Models;

/**
 * Trait with checks related methods
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package App\Models
 */
trait ChecksTrait
{
    /**
     * Checks if record exists on edit
     *
     * @param int $id
     * @param array $params
     * @return bool
     */
    public static function checkOnEdit($id, array $params)
    {
        $query = self::where('id', '!=', $id)
            ->where($params)
            ->get();

        if (count($query) > 0) {
            return true;
        } else {
            return false;
        }
    }
}