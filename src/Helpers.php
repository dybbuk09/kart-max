<?php 

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Egulias\EmailValidator\Validation\RFCValidation;
use Egulias\EmailValidator\EmailValidator;
use KartMax\Application;


if (!function_exists('basepath')) 
{
	
	/**
	 * This function return the basepath of the app
	 */

	function basepath()
	{
		return Application::getBasePath();
	}
}


if (!function_exists('fine')) 
{
	
	/**
	 * This function return an output which shows your app is running
	 */

	function fine()
	{
		return jsonResponse(['message' => 'App is running']);
	}
}


if (!function_exists('env')) 
{
	
	/**
	 * Gets the value of an environment variable.
	 * @param string $key
	 * @return string
	 */

	function env($value)
	{
		return $_ENV[$value];
	}
}


if (!function_exists('jsonRepsonse')) 
{
	
	/**
	 * Return Data in form of json
	 * @param array $data
	 * @param int $statusCode
	 * @return mixed
	 */

	function jsonResponse($data, $statusCode=200)
	{
		http_response_code($statusCode);
	    header('Content-Type: application/json');
	    return json_encode($data, JSON_PRETTY_PRINT);
	}
}


if (!function_exists('cache')) 
{
	/**
	 * Return instance of Symfony cache filesystemAdapter;
	 * @param
	 * @return object
	 */

	function cache()
	{
		return new FilesystemAdapter(
			// a string used as the subdirectory of the root cache directory, where cache
			// items will be stored
			$namespace = 'cache',

			// the default lifetime (in seconds) for cache items that do not define their
			// own lifetime, with a value 0 causing items to be stored indefinitely (i.e.
			// until the files are deleted)
			$defaultLifetime = (int)env('CACHE_LIFETIME'),

			// the main cache directory (the application needs read-write permissions on it)
			// if none is specified, a directory is created inside the system temporary directory
			$directory = Application::getBasePath().'/storage/' //Cache::getCacheDirectory()
		);
	}
}


if (!function_exists('pluck')) 
{
	
	/**
	 * Return all rows from a set of data for a particular key or column
	 * @param string $columnName
	 * @param array $data
	 * @return array $keysValue
	 */

	function pluck($columnName, $data)
	{
		$keysValue = [];
		foreach ($data as $key => $value) {
			array_push($keysValue, $value[$columnName]);
		}
		return $keysValue;
	}
}


if (!function_exists('isValidEmail')) 
{
	
	/**
	 * Check if given email address is valid or not using Egulias email validator
	 * @param string $email
	 * @return boolean
	 */
	function isValidEmail($email)
	{
		$validator = new EmailValidator();
		if ($validator->isValid($email, new RFCValidation())) {
			return true;
		}
		return false;
	}
}


if (!function_exists('getCsv')) {
	
	/**
	 * This function converts your given data into a csv file
	 * @param string $filename
	 * @param array $csvHeaders
	 * @param array $csvRows
	 */
	function getCsv($filename, $csvHeaders, $csvRows, $saveInStorage=true, $delimiter=';')
	{
		$fileName = time()."_{$filename}.csv";
        header('Content-Type: text/csv');
    	header("Content-Disposition: attachment; filename={$fileName}");

		if($saveInStorage)
		{
			$csvPath = '/storage/csv';
			if(!is_dir(basepath().$csvPath))
			{
				mkdir(basepath().$csvPath, 0755);
			}
			$f = fopen(basepath()."{$csvPath}/{$fileName}", 'w+');
		}
		else
		{
			$f = fopen('php://output', 'w+');
		}

		//Add headers in CSV
		fputcsv($f, $csvHeaders, $delimiter);
		
		//Add data rows in CSV
		foreach ($csvRows as $key => $row) {
			fputcsv($f, $row, $delimiter);
		}

		fclose($f);
	}
	
}


if(!function_exists('getCurl'))
{
	/**
	 * This function perform a curl request with GET method
	 * @param string $url
	 * @return mixed
	 */
	function getCurl($url) {
		$curl = curl_init();
	
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
		));
	
		$response = curl_exec($curl);
	
		curl_close($curl);
		return $response;
	}
}