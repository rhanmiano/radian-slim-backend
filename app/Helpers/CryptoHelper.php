<?php

namespace App\Helpers;

/**
 * class CryptoHelper
 *
 * Encrypt and decrypt strings using open_ssl encrypt library
 */
class CryptoHelper {
    private $encrypt_method;
    private $secret_key;
    private $secret_pass;

    private $key;
    private $iv;

    public function __construct() {
      $this->encrypt_method = 'aes-256-cbc';
      $this->secret_key = getenv("ENC_SECRET");
      $this->secret_pass = getenv("ENC_PASS");
      
      $this->key = hash('sha256', $this->secret_key);

      // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning        
      $this->iv = substr(hash('sha256', $this->secret_pass), 0, 16);
    }

    public function _encrypt($str) {
      $encoded = openssl_encrypt(
        $str,
        $this->encrypt_method,
        $this->key,
        0,
        $this->iv
      );

      $encoded = base64_encode($encoded);
      
      return $encoded;
    }

    public function _decrypt($str) {
      $decoded = openssl_decrypt(
        base64_decode($str),
        $this->encrypt_method,
        $this->key,
        0,
        $this->iv
    );

    return $decoded;
    }

    public function _cryptIds($args, $method, $cast_object = false) {
      
      $crypt = new CryptoHelper;
      if (!preg_match("/^_(?:en|de)crypt$/i", $method)) {
        return false;
      }  

      if (!is_array($args)) {
        if (gettype($args) === 'object')
          $args = (array) $args;
        else
          throw new Exception('Data must be of type object/array');
      }

      if ($args && sizeof($args) > 0) {
        foreach($args as $key => $value) {
          if (is_array($value)) {
            $row_keys = array_keys($value);
            $with_ids = preg_grep("/(?:^id$|_id$)/i", $row_keys);
            
            foreach($with_ids as $row_index) {
              $args[$key][$row_index] = $crypt->$method($value[$row_index]);
            }
          } else {
            $try_crypt = $crypt->$method($value);

            if ($try_crypt) {
              $args[$key] = $try_crypt;
            }
          }
        }
      }

      return $cast_object ? (object) $args : $args;

    }

}