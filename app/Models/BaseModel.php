<?php

namespace App\Models;

use ORM;
use App\Helpers\CryptoHelper as Crypto;

class BaseModel extends ORM{

    public function __construct() {
        ORM::configure(\App\App\Config\Config::db());
    }

}