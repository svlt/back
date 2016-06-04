<?php

namespace Controller;

class User extends \Controller {

	/**
	 * /u/@user.json
	 */
	public function base($f3, $params) {
		$user = \App::model('user');
		if($params['username'] == 'me') {
			$user->loadByToken($f3->get('GET._token'));
		} else {
			$user->load(array('username = ?', $params['username']));
		}

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
		$pd = \App::model('post/detail');
		$posts = $pd->find(['page_username = ?', $params['username']], $_GET + ['limit' => 10, 'order' => 'created_at DESC']);

		$return = [];
		foreach($posts as $post) {
			$return[] = $post->cast();
		}

		$this->_json($return);
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
		// Create user
		$data = [
			'username' => $f3->get('POST.username'),
			'fingerprint' => $f3->get('POST.keypair-fingerprint'),
			'name' => $f3->get('POST.name'),
			'password_salt' => $f3->get('POST.password-salt'),
			'password_hash' => password_hash($f3->get('POST.password-hash'), PASSWORD_DEFAULT, ['cost' => \App::config()['security']['bcrypt_cost']]),
			'private_key' => $f3->get('POST.keypair-private'),
			'public_key' => $f3->get('POST.keypair-public'),
			'symmetric_key' => $f3->get('POST.symmkey'),
		];
		$user = \Model\User::create($data);

		// Log in user and return stssion token
		$token = \Helper\Security::generateToken($user->id);
		$this->_json(['user_id' => $user->id, 'token' => $token]);
	}

	/**
	 * POST /auth.json
	 */
	public function auth($f3) {
		switch($f3->get('POST.action')) {
			case 'salt':
				$user = new \Model\User;
				$user->load(['username = ?', $f3->get('POST.username')]);
				if($user->id) {
					$this->_json(['salt' => $user->password_salt]);
				} else {
					$this->_json(['salt' => null, 'error' => 'User does not exist.']);
				}
				break;
			case 'auth':
				// Verify login
				$user = new \Model\User;
				$user->load(['username = ?', $f3->get('POST.username')]);
				if($user->id && password_verify($f3->get('POST.password_hash'), $user->password_hash)) {

					// Re-hash passphrase if it doesn't meet the current security settings
					if(password_needs_rehash($user->password_hash, PASSWORD_DEFAULT, ['cost' => \App::config()['security']['bcrypt_cost']])) {
						$user->password_hash = password_hash($f3->get('POST.password_hash'), PASSWORD_DEFAULT, ['cost' => \App::config()['security']['bcrypt_cost']]);
						$user->save();
					}

					// Generate and return session token
					$token = \Helper\Security::generateToken($user->id);
					$this->_json(['user_id' => $user->id, 'token' => $token]);

				} else {
					$this->_json(['error' => 'Invalid username or password.']);
				}
				break;
		}
	}

	/**
	 * GET /keystore.json
	 */
	public function keystore($f3) {
		$userId = self::_requireAuth();
		if(!$userId) {
			\App::error(401);
		}

		$key = new \Model\User\Key;
		$keys = $key->find(['user_id = ?', $userId]);
		$return = ['symmetric' => []];
		foreach($keys as $k) {
			switch($k->type) {
				case 'private':
				case 'public':
					$return[$k->type] = $k->key;
					break;
				default:
					$return['symmetric'][$k->buddy_id] = $k->key;
			}
		}
		$this->_json($return);
	}

	/**
	 * POST /logout.json
	 */
	public function logout($f3) {
		$token = new \Model\User\Token;
		$token->load(['token = ?', $f3->get('REQUEST._token')]);
		if(!$token->id) {
			$this->_json(['error' => 'Token not found.']);
			return;
		}
		$token->erase();
		$this->_json(['success' => true]);
	}

}
