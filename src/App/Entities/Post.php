<?php

namespace App\Entity;

use Doctinre\ORM\Mapping;

/**
 * @Entity
 * @Table(name="posts")
 */
class Post {

	/**
	 * @var integer
	 * @Id
	 * @Column(name="id",type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @var string
	 * @Column(name="title", type="string",length=255)
	 */
	protected $title;

	/**
	 * @var string
	 * @Column(name="content", type="text")
	 */
	protected $content;

	public function getId(){
		return $this->id;
	}

	public function getTitle(){
		return $this->title;
	}

	public function setTitle( $title ){
		$this->title = $title;
	}

	public function getContent(){
		return $this->content;
	}

	public function setContent( $content ){
		$this->content = $content;
	}
}