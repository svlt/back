<?php

namespace Controller;

class Post extends \Controller {

	/**
	 * /post.json
	 * POST a new post
	 */
	public function post() {
		// TODO: Implement authenticated posting
		\App::error(501);
	}

	/**
	 * /post/@id.json
	 */
	public function single() {
		$post = \App::model('post')->load($request->id);
		if(!$post->get('id')) {
			\App::error(404);
		}
		$this->_json($post->data());
	}

}
