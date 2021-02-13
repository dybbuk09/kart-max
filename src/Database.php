<?php 

namespace KartMax;

use Illuminate\Database\Capsule\Manager as Capsule;

class Database
{

	protected $connection;
	protected $query;

	public function __construct()
	{
		try 
		{
			$capsule = new Capsule;
			$capsule->addConnection([
				'driver'    => env('DB_CONNECTION'),
				'host'      => env('DB_HOST'),
				'database'  => env('DB_NAME'),
				'username'  => env('DB_USERNAME'),
				'password'  => env('DB_PASSWORD'),
				'charset'   => 'utf8',
				'collation' => 'utf8_unicode_ci',
				'prefix'    => '',
			]);
		} 
		catch (Exeception $e) 
		{
		    echo 'Connection failed: ' . $e->getMessage();
		}
	}


	// Return database connection to Modal class
	protected function db()
	{
		return $this->connection;
	}

}