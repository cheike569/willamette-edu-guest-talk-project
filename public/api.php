<?php
require '../vendor/autoload.php';

use Src\Kernel;

$application = new Kernel();
$application->bootstrap();

$application->handleRequest();
