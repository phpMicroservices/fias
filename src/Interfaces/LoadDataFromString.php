<?php

namespace App\Interfaces;


interface LoadDataFromString
{
    /**
     * @param string $row
     *
     * @return mixed
     */
    public static function loadDataFromString(string $row);
}