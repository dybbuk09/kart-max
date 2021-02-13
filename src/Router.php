<?php

namespace KartMax;

use KartMax\Request;

class Router
{

    public $routeHandler = [];
    public $routeMiddlewares = [];
    public $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    // Function to handle GET requests
    public function get($path, $callback, array $middlewares = [])
    {
        $this->routeHandler['GET'][$path] = $callback;
        if(($path===$this->request->getPath()) && $this->request->getMethod()==='GET')
        {
            $this->routeMiddlewares = $middlewares;
        }
    }
    
    // Function to handle POST requests
    public function post($path, $callback, array $middlewares = [])
    {
        $this->routeHandler['POST'][$path] = $callback;
        if(($path===$this->request->getPath()) && ($this->request->getMethod()==='POST'))
        {
            $this->routeMiddlewares = $middlewares;
        }
    }

    /**
	 * Execute Middlewares
	 */
	public function runMiddleware($middlewareClass, $middlewareMethod='handle')
	{
		return call_user_func(
			[new $middlewareClass, $middlewareMethod], $this->request
		);
	}

    /**
     * Function to handle the route request
     */
    public function resolve(array $middlewares = [])
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $callback = $this->routeHandler[$method][$path] ?? false;

        if (!$callback) {
            return jsonResponse(["message" => "Route Not Found"], 404);
        }

        /**
         * Merge all middlewares
         */
        $allMiddlewares = array_merge($middlewares, $this->routeMiddlewares);
        
        /**
         * If middlewares stack is not empty then run through each middleware
         */
        if(!empty($allMiddlewares))
        {
            foreach ($allMiddlewares as $key => $middleware) {
                $middlewareResponse = $this->runMiddleware($middleware);
                if($middlewareResponse)
                {
                    return $middlewareResponse;
                }
            }
        }

        if (is_array($callback)) {
            $classObject = new $callback[0];
            if(!isset($callback[1]))
            {
                return call_user_func($classObject, $this->request);
            }
            return call_user_func([$classObject, $callback[1]], $this->request);
        }

        return call_user_func($callback);
    }

}
