<?php

namespace Controller;

class Post extends \Controller {

	/**
	 * /post.json
	 * POST a new post
	 * @param  Pixie\Request $request
	 * @param  Pixie\AbstractResponse $response
	 * @param  Pixie\ServiceProvider  $service
	 */
	public function post($request, $response, $service) {
		// TODO: Implement authenticated posting
		\App::error(501);
	}

	/**
	 * /post/@id.json
	 * @param  Pixie\Request $request
	 * @param  Pixie\AbstractResponse $response
	 * @param  Pixie\ServiceProvider  $service
	 */
	public function single($request, $response, $service) {
		$post = \App::model('post')->load($request->id);
		if(!$post->get('id')) {
			\App::error(404);
		}
		$response->json($post->data());
	}

}
