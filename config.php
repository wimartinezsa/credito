<?php

if ($_SERVER['HTTP_HOST'] == 'localhost') {
    define('BASE_URL', 'http://localhost/creditos/');
} else {
    define('BASE_URL', 'http://confianzacontratualsas.com/');
}