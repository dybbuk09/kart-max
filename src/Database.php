<?php 

namespace KartMax;

use Illuminate\Database\Capsule\Manager as Capsule;

class Database
{
	public $capsule;

	public function __construct($configuration)
	{
		try 
		{
			$this->capsule = new Capsule;
			$this->capsule->addConnection($configuration);
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