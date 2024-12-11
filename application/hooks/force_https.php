<?php defined('BASEPATH') OR exit('No direct script access allowed');

function force_https() {
    $CI =& get_instance();

    // Check if the current request is not secure (not using HTTPS)
    if ($_SERVER['HTTPS'] !== 'on') {
        // Redirect to the HTTPS version of the current URL
        $url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        redirect($url, 'location', 301);
        exit();
    }
}
