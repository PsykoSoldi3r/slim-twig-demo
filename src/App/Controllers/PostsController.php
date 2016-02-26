<?php

namespace App\Controllers;

use Routing\Controller;
use App\Core\TwigFactory;

class PostsController extends Controller {

	/**
	 * @Route('/:id')
	 * @Method('GET')
	 * @Name('show.post')
	 */
	public function showPost( $id ){
		$em = $this->getApp()->getEntityManager();
		$post = $em->find('App\\Entity\\Post', $id );

		$twig = TwigFactory::create();
		$template = $twig->loadTemplate('single.twig');

		$this->getApp()->renderTemplate( $template, array(
			'title' => $post->getTitle(),
			'content' => $post->getContent()
		) );

	}

}