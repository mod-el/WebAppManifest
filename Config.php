<?php namespace Model\WebAppManifest;

use Model\Core\Module_Config;

class Config extends Module_Config
{
	public $configurable = false;

	/**
	 * @throws \Exception
	 */
	protected function assetsList()
	{
		$this->addAsset('config', 'config.php', function () {
			return "<?php\n\$config = [];\n";
		});

		$this->addAsset('config', 'icons');
	}

	/**
	 * @return bool
	 * @throws \Exception
	 */
	public function makeCache(): bool
	{
		$iconFormats = ['16', '32', '48', '64', '72', '96', '144', '168', '192', '256', '512', '1024'];

		$configPath = INCLUDE_PATH . 'app' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'WebAppManifest';

		$config = $this->retrieveConfig();
		foreach ($config as $manifest => &$data) {
			$manifest = str_replace(['/', '\\'], '-', $manifest);
			if (!is_dir($configPath . DIRECTORY_SEPARATOR . 'icons' . DIRECTORY_SEPARATOR . $manifest))
				mkdir($configPath . DIRECTORY_SEPARATOR . 'icons' . DIRECTORY_SEPARATOR . $manifest);

			$data['icons'] = [];

			foreach ($iconFormats as $format) {
				if (file_exists($configPath . DIRECTORY_SEPARATOR . 'icons' . DIRECTORY_SEPARATOR . $manifest . DIRECTORY_SEPARATOR . $format . '.png')) {
					$data['icons'][] = [
						'src' => 'app/config/WebAppManifest/icons/' . $manifest . '/' . $format . '.png',
						'sizes' => $format . 'x' . $format,
						'type' => 'image/png',
					];
				}
			}

			if (!$data['icons'])
				unset($data['icons']);
		}
		unset($data);

		$this->saveConfig('config', $config);

		return true;
	}

	/**
	 * Rules for API actions
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function getRules(): array
	{
		$config = $this->retrieveConfig();

		$rules = [];
		foreach ($config as $manifest => $data) {
			$rules[] = $manifest;
		}

		return [
			'rules' => $rules,
			'controllers' => [
				'WebAppManifest',
			],
		];
	}
}
