<?php

use Core\Application;

require __DIR__ . '/vendor/autoload.php';

$oApp = new Application(Application::CONTENT_MODE);
$oApp->run();