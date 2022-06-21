<?php

namespace App\Controllers;

class Session {

    public function __construct() {
    }

    public function set($name, $value) {
        $_SESSION[$name] = $value;
    }

    public function get($name) {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }
        return $_SESSION[$name];
    }

    public function kill($name) {
        unset($_SESSION[$name]);
    }

    public function killAll() {
        session_destroy();
    }


}