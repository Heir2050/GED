<?php 

namespace Controller;

use Core\FileHelper;

defined('ROOTPATH') OR exit('Access Denied!');

Trait MainController
{

	public function view($name, $data = [])
	{
		if (!empty($data))
			$data['fileHelper'] = new FileHelper(); // Ensure FileHelper is available in the view
			extract($data);

		$filename = "../app/views/".$name.".view.php";
		if(file_exists($filename))
		{
			require $filename;
		}else{

			$filename = "../app/views/404.view.php";
			require $filename;
		}
	}
}