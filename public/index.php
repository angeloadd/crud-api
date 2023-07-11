<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    // hack to not install another package -> add headers and make CORS preflight fail
    header('Access-Control-Allow-Origin:*');
    header('Access-Control-Allow-Headers:X-Requested-With, Content-Type, withCredentials');
    header('Access-Control-Allow-Methods:*');

    if ('OPTIONS' === $_SERVER['REQUEST_METHOD']) {
        exit;
    }

    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
