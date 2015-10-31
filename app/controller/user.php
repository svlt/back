<?php

namespace Controller;

class User extends \Controller {

	/**
	 * /u/@user.json
	 */
	public function base($f3, $params) {
		$user = \App::model('user')->load(array('username = ?', $params['username']));

		if(!$user->get('id')) {
			\App::error(404);
		}

		$this->_json($user->getFields(['id', 'username', 'tagline', 'name', 'fingerprint']));
	}

	/**
	 * /u/@user/key.json
	 */
	public function key($f3, $params) {
		$key = \QB::table('user_key')
				->select(['user_key.fingerprint', 'user_key.key'])
				->join('user', 'user.id', '=', 'user_key.user_id')
				->where('user.username', $params['username'])
				->where('user_key.type', 'public')
				->first();

		if(!$key) {
			\App::error(404);
		}

		$this->_json($key);
	}

	/**
	 * /u/@user/posts.json
	 */
	public function posts($f3, $params) {
		$query = \QB::table('post')
				->select(['post.*'])
				->join('user', 'user.id', '=', 'post.user_id')
				->where('user.username', $params['username']);

		if(isset($_GET['limit']) && intval($_GET['limit'])) {
			$query->limit(intval($_GET['limit']));
		}
		if(isset($_GET['offset']) && intval($_GET['offset'])) {
			$query->offset(intval($_GET['offset']));
		}

		$posts = $query->get();

		if(!$posts) {
			\App::error(404);
		}

		$this->_json($posts);
	}

	/**
	 * POST /u/@user/posts.json
	 */
	public function post($f3, $params) {
		$userId = self::_requireAuth();
		if(!$userId) {
			\App::error(401);
		}

		$page = \App::model('user')->load(array('username = ?', $request->username));
		if(!$page->get('id')) {
			\App::error(404);
		}

		// TODO: Throw 403 if attempting to post to page that is not a buddy

		// Create the post
		$post = \App::model('post');
		$post->page_id = $page->id;
		$post->user_id = $userId;
		$post->content = $_POST['content'];
		$post->save();

		$this->_json($post->data());
	}

	/**
	 * POST /register.json
	 */
	public function register($f3, $params) {
		// TODO: Implement user registration
	}

}
