<?php namespace Model\WebAppManifest\Providers;

use Model\Router\AbstractRouterProvider;

class RouterProvider extends AbstractRouterProvider
{
	public static function getRoutes(): array
	{
		$configClass = new \Model\WebAppManifest\Config();
		$config = $configClass->retrieveConfig();

		$routes = [];
		foreach ($config as $path => $data) {
			$routes[] = [
				'pattern' => $path,
				'controller' => 'WebAppManifest',
			];
		}

		return $routes;
	}
}
