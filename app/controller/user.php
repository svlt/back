<?php

namespace Controller;

class User extends \Controller {

	/**
	 * /u/@user.json
	 * @param  Request $request
	 * @param  AbstractResponse $response
	 * @param  ServiceProvider  $service
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
	 * @param  Request $request
	 * @param  AbstractResponse $response
	 * @param  ServiceProvider  $service
	 */
	public function key($request, $response, $service) {

		$key = \QB::table('user_key')
				->select(array('user_key.fingerprint', 'user_key.key'))
				->join('user', 'user.id', '=', 'user_key.user_id')
				->where('user_key.type', 'public')
				->where('user.username', $request->username)
				->first();

		if(!$key) {
			App::error(404);
		}

		$response->json($key);

	}

}
