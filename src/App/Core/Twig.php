<?php

namespace App\Core;

use Twig_Loader_Filesystem;
use Twig_Environment;

class Twig {

	private $twig; //Twig_Environment
	private $loader;  //Twig_Load_FileSystem

	public function __construct(){
		$this->loadTwig();
	}

	private function loadTwig(){
		if( $this->twig === null ){
			$this->loader = new Twig_Loader_Filesystem('templates');
			$this->twig = new Twig_Environment( $this->loader );
		}
	}

	public function loadTemplate( $filename ){
		return $this->twig->loadtemplate( $filename );
	}
}