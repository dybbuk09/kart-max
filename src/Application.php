<?php 

namespace KartMax;

use KartMax\Router;
use KartMax\Request;
use KartMax\Database;
use Dotenv\Dotenv;

class Application
{

	/**
	 * Instance of router class
	 */
	public $router;

	/**
	 * Holds the value of base path of the application
	 */
	public static $basePath;
	
	public $middlewares = [];
	
	
	public function __construct($basePath)
	{
		$this->router 	= new Router();
		
		self::$basePath = $basePath;
		
		/**
		 * Set base path of the project to Dotenv class, 
		 * so that it can access environment variables of .env file from base directory
		 */
		$dotenv = Dotenv::createImmutable($basePath);
		$dotenv->load();
	}

	/**	
	 * Get the base path of application
	 */
	public static function getBasePath()
	{
		return self::$basePath;
	}

	/**
	 * Set the common middlewares
	 */
	public function setMiddlewares(array $middlewares = [])
	{
		$this->middlewares = $middlewares;
	}

	/**
	 * Run the application
	 */
	public function run()
	{
		/**
		 * Create database connection
		 */
		$capsuleInstance = new Database(defaultDB());
		$capsuleInstance->capsule->setAsGlobal();
        $capsuleInstance->capsule->bootEloquent();

		echo $this->router->resolve($this->middlewares);
	}

}