<?php

namespace Controller;

class Post extends \Controller {

	/**
	 * /post.json
	 * POST a new post
	 * 
	 * @todo Allow posting to a buddy's page
	 * @param \Base $fw
	 */
	public function post(\Base $fw) {
		$userId = self::_requireAuth();
		if($fw->get('POST.user_id') != $userId) {
			\App::error(403);
		}
		$post = \Model\Post::create([
			'user_id' => $userId,
			'page_id' => $fw->get('POST.user_id'),
			'content' => $fw->get('POST.content')
		]);
		$this->_json($post->cast());
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
