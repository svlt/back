<?php

namespace Controller;

class Post extends \Controller {

	/**
	 * POST /post.json
	 * Create a new post
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
		$detail = \App::model('post/detail')->load($post->id);
		$this->_json($detail->cast());
	}

	/**
	 * GET /post/@id.json
	 * Get a single post
	 * 
	 * @param \Base $fw
	 * @param array $params
	 */
	public function single(\Base $fw, array $params) {
		$userId = self::_requireAuth();
		$post = \App::model('post/detail')->load($params['id']);
		if(!$post->id) {
			\App::error(404);
		}
		$this->_json($post->data());
	}

	/**
	 * DELETE /post/@id.json
	 * Delete a post
	 * 
	 * @param \Base $fw
	 * @param array $params
	 */
	public function delete(\Base $fw, array $params) {
		$userId = self::_requireAuth();
		$post = \App::model('post')->load($params['id']);
		if(!$post->id) {
			\App::error(404);
		}
		if($post->user_id != $userId && $post->page_id != $userId) {
			\App::error(403);
		}
		$post->erase();
		$this->_json(["success" => true]);
	}

}
