<?php
namespace App\Commands;
require __DIR__.'/../../vendor/autoload.php';

use Src\Database\Database;
use Src\Kernel;

$application = new Kernel();
$application->bootstrap();

Database::getDatabase();
Database::resetDatabase();