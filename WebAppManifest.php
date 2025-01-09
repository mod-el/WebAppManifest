<?php namespace Model\WebAppManifest;

use Model\Core\Autoloader;
use Model\Core\Module;
use Model\Form\Form;

class WebAppManifest extends Module
{
	/** @var array */
	public array $manifestData;

	/**
	 * @param string $path
	 * @return array|null
	 */
	public function getManifest(string $path): ?array
	{
		$config = $this->retrieveConfig();
		return $config[$path] ?? null;
	}

	/**
	 * @param string $path
	 * @param array $data
	 */
	public function setManifest(string $path, array $data): void
	{
		if (!isset($data['name'], $data['start_url']))
			return;

		$config = $this->retrieveConfig();
		$config[$path] = $data;

		$configPath = INCLUDE_PATH . 'app' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'WebAppManifest';
		$configFile = $configPath . DIRECTORY_SEPARATOR . 'config.php';

		$write = (bool)file_put_contents($configFile, '<?php
$config = ' . var_export($config, true) . ';
');
		if ($write) {
			$iconsPath = str_replace(['/', '\\'], '-', $path);
			if (!is_dir($configPath . DIRECTORY_SEPARATOR . 'icons' . DIRECTORY_SEPARATOR . $iconsPath))
				mkdir($configPath . DIRECTORY_SEPARATOR . 'icons' . DIRECTORY_SEPARATOR . $iconsPath, 0777, true);
		} else {
			throw new \Exception('Cannot write to ' . $configFile);
		}
	}

	/**
	 * @param array $request
	 * @param string $rule
	 * @return array|null
	 */
	public function getController(array $request, string $rule): ?array
	{
		$config = $this->retrieveConfig();
		$request = implode('/', $request);
		if (!isset($config[$request]))
			return null;

		$this->manifestData = $config[$request];

		return [
			'controller' => 'WebAppManifest',
		];
	}
}
