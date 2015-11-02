<?php

namespace Model;

class Photo extends \Model {

	protected static $requiredFields = ['user_id', 'post_id', 'content', 'signature'];

}
