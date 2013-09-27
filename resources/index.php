<?php

/**
 * Example Index Resource
 *
 * @package Dreamr
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Matt Fiocca <matt.fiocca@gmail.com>
 */
class Index extends DreamResource {

	public $reset_routes = TRUE;

	public function routes() {
		return array(
			'get' => array(
				'/' => '_index'
			)
		);
	}

	public function _index() {
		$this->abort(405);
	}
}