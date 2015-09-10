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

		$user = \QB::table('user')
				->select(array('username', 'name', 'fingerprint'))
				->find($request->username, 'username');

		if(!$user) {
			App::error(404);
		}

		$response->json($user);

	}

	/**
	 * /u/@user/key.json
	 * @param  Pixie\Request $request
	 * @param  Pixie\AbstractResponse $response
	 * @param  Pixie\ServiceProvider  $service
	 */
	public function key($request, $response, $service) {

		$key = \QB::table('user_key')
				->select(array('user_key.fingerprint', 'user_key.key'))
				->join('user', 'user.id', '=', 'user_key.user_id')
				->where('user.username', $request->username)
				->where('user_key.type', 'public')
				->first();

		if(!$key) {
			App::error(404);
		}

		$response->json($key);

	}

	/**
	 * /u/@user/posts.json
	 * @param  Pixfie\Request $request
	 * @param  Pixie\AbstractResponse $response
	 * @param  Pixie\ServiceProvider  $service
	 */
	public function posts($request, $response, $service) {

		$query = \QB::table('post')
				->select(array('post.*'))
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
			App::error(404);
		}

		$response->json($posts);

	}

}
