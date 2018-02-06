<?php

namespace App\Classes;

class Router
{
    private $front = 'index.php';

    private $homeRoute = '/home.php';

    private $notFoundRoute = '/not-found.php';

    private $pagesDirectory;

    private $routes = [
        '/test' => 'test.php',
    ];

    public function __construct(?string $pagesDirectory = null)
    {
        if ($pagesDirectory) {
            $pagesDirectory = str_replace(DIRECTORY_SEPARATOR, '/', $pagesDirectory);
            $this->pagesDirectory = rtrim($pagesDirectory, '/');
        }
    }

    public function match(string $path)
    {
        $path = $this->fixPath($path);
        $route = $this->getRoute($path);

        return $this->fixRoute($route);
    }

    /**
     * Fixes the incoming URL path to strip the front controller script
     * name.
     *
     * @param string $path The incoming URL path.
     * 
     * @return string The fixed path.
     */
    private function fixPath($path)
    {
        $len = strlen($this->front);
        if (substr($path, 0, $len) == $this->front) {
            $path = substr($path, $len);
        }

        return '/' . ltrim($path, '/');
    }

    /**
     * Returns the route value for a given URL path; uses the home route value
     * if the URL path is `/`.
     *
     * @param string $path The incoming URL path.
     * 
     * @return string The route value.
     */
    private function getRoute($path)
    {
        if (isset($this->routes[$path])) {

            return $this->routes[$path];
        }

        if ($path == '/') {
            return $this->homeRoute;
        }
        
        return $path;
    }

    /**
     * Fixes a route specification to make sure it is found.
     *
     * @param string $route The matched route.
     * 
     * @return string The "fixed" route.
     * 
     * @throws RuntimeException when the route is a file but no pages directory
     * is specified.
     */
    private function fixRoute($route)
    {
        if ($this->isFileRoute($route)) {
            
            return $this->fixFileRoute($route);
        }

        return sprintf('%s/%s', $this->pagesDirectory, $route);
    }

    /**
     * Is the matched route a file name?
     *
     * @param string $route The matched route.
     * 
     * @return bool
     */
    protected function isFileRoute($route)
    {
        return substr($route, 0, 1) == '/';
    }

    /**
     * Fixes a file route specification by finding the real path to see if it
     * exists in the pages directory and is readable.
     *
     * @param string $route The matched route.
     * 
     * @return string The real path if it exists, or the not-found route if it
     * does not.
     * 
     * @throws RuntimeException when the route is a file but no pages directory
     * is specified.
     */
    protected function fixFileRoute($route)
    {
        if (! $this->pagesDirectory) {
            throw new RuntimeException('No pages directory specified.');
        }

        $page = realpath($this->pagesDirectory . $route);
        if ($this->pageExists($page)) {
            return $page;
        }

        if ($this->isFileRoute($this->notFoundRoute)) {
            return $this->pagesDirectory . $this->notFoundRoute;
        }

        return $this->notFoundRoute;
    }

    /**
     * Does the pages directory have a matching readable file?
     *
     * @param string $file The file to check.
     * @return bool
     */
    protected function pageExists($file)
    {
        $file = str_replace(DIRECTORY_SEPARATOR, '/', $file);

        return $file != ''
            && substr($file, 0, strlen($this->pagesDirectory)) == $this->pagesDirectory
            && file_exists($file)
            && is_readable($file);
    }
}