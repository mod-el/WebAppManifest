<?php namespace Model\WebAppManifest;

use Model\Core\Autoloader;
use Model\Core\Module;
use Model\Form\Form;

class WebAppManifest extends Module {
	/** @var array */
	public $manifestData;

	/**
	 * @param string $path
	 * @return array|null
	 */
	public function getManifest($path){
		$config = $this->retrieveConfig();
		if(isset($config[$path])){
			return $config[$path];
		}else{
			return null;
		}
	}

	/**
	 * @param string $path
	 * @param array $data
	 * @return bool
	 */
	public function setManifest($path, array $data){
		if(!isset($data['name'], $data['start_url']))
			return false;

		$config = $this->retrieveConfig();
		$config[$path] = $data;

		$configPath = INCLUDE_PATH.'app'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'WebAppManifest';
		$configFile = $configPath.DIRECTORY_SEPARATOR.'config.php';

		$write = (bool) file_put_contents($configFile, '<?php
$config = '.var_export($config, true).';
');
		if($write){
			$iconsPath = str_replace(['/', '\\'], '-', $path);
			if(!is_dir($configPath.DIRECTORY_SEPARATOR.'icons'.DIRECTORY_SEPARATOR.$iconsPath))
				mkdir($configPath.DIRECTORY_SEPARATOR.'icons'.DIRECTORY_SEPARATOR.$iconsPath, 0777, true);

			return true;
		}else{
			return false;
		}
	}

	/**
	 * @param array $request
	 * @param string $rule
	 * @return array|bool
	 */
	public function getController(array $request, string $rule){
	    $config = $this->retrieveConfig();
	    $request = implode('/', $request);
	    if(!isset($config[$request]))
	    	return false;

	    $this->manifestData = $config[$request];

		return [
			'controller' => 'WebAppManifest',
		];
	}
}
