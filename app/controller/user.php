<?php

namespace Controller;

class User extends \Controller {

	/**
	 * /u/@user.json
	 * @param  Pixie\Request $request
	 * @param  Pixie\AbstractResponse $response
	 * @param  Pixie\ServiceProvider  $service
	 */
	public function base($request, $response, $service) {

		$user = \App::model('user')->load($request->username, 'username');

		if(!$user->get('id')) {
			\App::error(404);
		}

		$response->json($user->getFields(['username', 'name', 'fingerprint']));

	}

	/**
	 * /u/@user/key.json
	 * @param  Pixie\Request $request
	 * @param  Pixie\AbstractResponse $response
	 * @param  Pixie\ServiceProvider  $service
	 */
	public function key($request, $response, $service) {

		$key = \QB::table('user_key')
				->select(['user_key.fingerprint', 'user_key.key'])
				->join('user', 'user.id', '=', 'user_key.user_id')
				->where('user.username', $request->username)
				->where('user_key.type', 'public')
				->first();

		if(!$key) {
			\App::error(404);
		}

		$response->json($key);

	}

	/**
	 * /u/@user/posts.json
	 * @param  Pixie\Request $request
	 * @param  Pixie\AbstractResponse $response
	 * @param  Pixie\ServiceProvider  $service
	 */
	public function posts($request, $response, $service) {

		$query = \QB::table('post')
				->select(['post.*'])
				->join('user', 'user.id', '=', 'post.user_id')
				->where('user.username', $request->username);

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

		$response->json($posts);

	}

	/**
	 * POST /u/@user/posts.json
	 * @param  Pixie\Request $request
	 * @param  Pixie\AbstractResponse $response
	 * @param  Pixie\ServiceProvider  $service
	 */
	public function post($request, $response, $service) {
		$userId = self::_requireAuth();
		if(!$userId) {
			\App::error(401);
		}

		$page = \App::model('user')->load($request->username, 'username');
		if(!$page->get('id')) {
			\App::error(404);
		}

		// TODO: Throw 403 if attempting to post to page that is not a buddy

		// Create the post
		$post = \App::model('post');
		$post->set('page_id', $page->get('id'));
		$post->set('user_id', $userId);
		$post->set('content', $_POST['content']);
		$post->save();

		$response->json($post->data());

	}

	/**
	 * POST /register.json
	 * @param  Pixie\Request $request
	 * @param  Pixie\AbstractResponse $response
	 * @param  Pixie\ServiceProvider  $service
	 */
	public function register($request, $response, $service) {
		// TODO: Implement user registration
	}

}
