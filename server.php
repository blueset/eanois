<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylorotwell@gmail.com>
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// This file allows us to emulate Apache's "mod_rewrite" functionality from the
// built-in PHP web server. This provides a convenient way to test a Laravel
// application without having installed a "real" web server software here.


if (preg_match('/^\/eanois_themes\//', $uri) && !is_file(__DIR__.'/public'.$uri)){
    $protocol = $_SERVER['SERVER_PORT'] == "443" ? "https://" : "http://";
    preg_match('/^\/eanois_themes\/.*\/(.*)/', $uri, $addr);
    $redirect = $protocol . $_SERVER['HTTP_HOST'] . '/' . $addr[1];
    header("Location: ".$redirect);
    exit;
}

if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

require_once __DIR__.'/public/index.php';
