<?php

require_once dirname(__FILE__).'/../vendor/autoload.php';

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use App\App;

$app = new App(array(
		'DB_DRIVER' => 'pdo_mysql',
		'DB_HOST' => 'localhost',
		'DB_USER' => 'root',
		'DB_PASS' => '',
		'DB_NAME' => 'slim_twig_demo'
	));

return ConsoleRunner::createHelperSet( $app->getEntityManager() );