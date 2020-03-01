<?php

namespace App\Helpers;

/**
 * class DateHelper
 *
 * Define all global utility helpers here
 */
class DateHelper {

    public function __construct() {
        \Moment\Moment::setDefaultTimezone('Asia/Manila');
    }

    public static function _now() {
        $date = new \Moment\Moment('now');
        return $date->format('Y-m-d G:i:s');
    }

}