<?php

namespace App\Helpers;

/**
 * class UtilityHelper
 *
 * Define all global utility helpers here
 */
class UtilityHelper {

    public static function _hello() {
      echo "hello world";
    }

    public static function _regex_keyboard_symbols() {
      // \w matches any word character (equal to [a-zA-Z0-9_])
      // \W matches any non-word character (equal to [^a-zA-Z0-9_])
      return "/\w|\W/";
    }

    public static function _sanitize($args) {
      return filter_var(trim($args), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    public static function _sanitize_array($args, $exempted = null, $cast_object = false) {
      if (!is_array($args)) {
        if (gettype($args) === 'object')
          $args = (array) $args;
        else
          throw new Exception('Sanitize data must be of type object/array');
      }

      foreach ($args as $key => $value) {
        if($exempted){
          if(is_array($exempted)){
            if(in_array($key, $exempted)) continue;
          }else{
            if($key == $exempted) continue;
          }
            }
        if(!is_array($value)){
          $value = Self::_sanitize($value);
          $args[$key] = ($value ? $value : NULL);
        }else{
          foreach ($value as $k => $v) {
            $v = Self::_sanitize($v);
            $args[$key][$k] = ($v ? $v : NULL);
          }
        }
      }

      return $cast_object ? (object) $args : $args;
    }
}