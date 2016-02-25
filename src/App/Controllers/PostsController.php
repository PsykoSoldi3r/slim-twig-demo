<?php

namespace App\Controllers;

use Routing\Controller;

class PostsController extends Controller {

	/**
	 * @Route('/:id')
	 * @Method('GET')
	 * @Name('show.post')
	 */
	public function showPost( $id ){
		$em = $this->getApp()->getEntityManager();
		$post = $em->find('App\\Entity\\Post', $id );

		echo "<h1>".$post->getTitle()."</h1>";
		echo "<article>".$post->getContent()."</article>";
	}

}