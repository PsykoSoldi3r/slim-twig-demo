<?php

namespace App\Controllers;

use Routing\Controller;
use App\Core\TwigFactory;

class HomeController extends Controller {

	/**
	 * @Route('/*')
	 * @Method('GET')
	 * @Name('show.homepage')
	 */
	public function showHomepage(){
		$posts = $this->getApp()->getEntityManager()->getRepository('App\\Entity\\Post')->findBy(
			array(
			'status' => 'published'
		), array(
			'publish_date' => 'DESC'
		));
		$postsData = array();

		foreach( $posts as $post ){
			array_push( $postsData, array(
				'id' => $post->getId(),
				'title' => $post->getTitle()
			));
		}

		$twig = TwigFactory::create();
		$template = $twig->loadTemplate('home.twig');

		$this->getApp()->renderTemplate( $template, array('posts' => $postsData));
	}

}