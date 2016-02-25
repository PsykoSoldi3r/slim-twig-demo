<?php

	use App\App;

	require dirname(__FILE__)."/vendor/autoload.php";

	$app = new App( array(
		'DB_DRIVER' => 'pdo_mysql',
		'DB_HOST' => 'localhost',
		'DB_USER' => 'root',
		'DB_PASS' => '',
		'DB_NAME' => 'slim_twig_demo'
	) );
	$app->run();
?>