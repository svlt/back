<?php

namespace Model\Post;

class Comment extends \Model {

	protected static $requiredFields = ['user_id', 'post_id', 'content', 'signature'];

}
