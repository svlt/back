<?php

namespace Model;

class Post extends \Model {

	protected static $requiredFields = ['user_id', 'page_id', 'content', 'signature'];

}
