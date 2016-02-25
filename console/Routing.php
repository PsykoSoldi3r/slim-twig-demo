<?php
	require_once dirname(__FILE__).'/../vendor/autoload.php';
	
	//ini_set('display_errors', 0);

	use App\App;

	$app = new App( array(
		'DB_DRIVER' => 'pdo_mysql',
		'DB_HOST' => 'localhost',
		'DB_USER' => 'root',
		'DB_PASS' => 'root',
		'DB_NAME' => 'slim_doctrine'
	));
	$routingManager = $app->getRoutingManager();

	foreach( $argv as $arg ){
		if( $arg === '--cache-clean' ){
			$routingManager->clearCache( true );
		}
	}
?>