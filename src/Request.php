<?php 

namespace KartMax;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Request 
{

	/**
	 * Dynamic property of a request
	 */
	protected $key;

	/**
	 * Instance of Symfony Request
	 */
	public $symfonyRequest;

	public function __construct()
	{
		$this->symfonyRequest = SymfonyRequest::createFromGlobals();
	}

	/**
	 * Return incoming request route
	 */
	public function getPath()
	{
		return $this->symfonyRequest->getPathInfo();
	}

	/**
	 * Return incoming required method
	 */
	public function getMethod()
	{
		return $this->symfonyRequest->getMethod();
	}

	/**
	 * Return incoming POST request body
	 */
	public function all()
	{
		return $this->symfonyRequest->request->all();
	}

	/**
	 * Return single parameter value of incoming request
	 */
	public function __get(string $key)
	{
		return $this->all()[$key];
	}

	/**
	 * Get all query parameters of a request
	 */
	public function queryAll()
	{
		return $this->symfonyRequest->query->all();
	}

	/**
	 * Get value of single query parameter
	 */
	public function query($param)
	{
		return $this->symfonyRequest->query->get($param);
	}

	/**
	 * merge more parameters to request object
	 */
	public function merge(array $parameters = [])
	{
		$this->symfonyRequest->request->add($parameters);
	}

	/**
	 * Get visitor's ip
	 */
	public function ip()
	{
		$request = SymfonyRequest::createFromGlobals();
		return $request->getClientIp();
	}

}