<?php namespace Model\WebAppManifest\Controllers;

use Model\Core\Controller;

class WebAppManifestController extends Controller
{
	public function init()
	{
		header('Content-Type: text/json');
	}

	public function index()
	{
		$manifest = $this->model->_WebAppManifest->manifestData;
		if (!$manifest)
			die('{}');

		$manifest = array_merge([
			'short_name' => $manifest['name'],
			'display' => 'standalone',
		], $manifest);
		echo json_encode($manifest, JSON_PRETTY_PRINT);
		die();
	}
}
