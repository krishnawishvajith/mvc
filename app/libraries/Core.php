<?php
class Core
{
    //URL Format --> /controller/method/params
    protected $currentController = 'Home';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->getURL();

        if (empty($url) || (isset($url[0]) && empty($url[0]))) {
            // Load default controller (Home)
            require_once '../app/controllers/' . $this->currentController . '.php';
            $this->currentController = new $this->currentController;
            
            // Call default method with no parameters
            call_user_func_array([$this->currentController, $this->currentMethod], []);
            return;
        }

        // Handle controller name - make sure first letter is uppercase
        $controllerName = ucfirst(strtolower($url[0]));
        
        if (file_exists('../app/controllers/' . $controllerName . '.php')) {
            //if the controller exists, then load it
            $this->currentController = $controllerName;

            //unset the controller in the url
            unset($url[0]);

            //Call the controller
            require_once '../app/controllers/' . $this->currentController . '.php';

            //instanciate the controller
            $this->currentController = new $this->currentController;

            //Check whether the method exists in the controller or not
            if (isset($url[1])) {
                if (method_exists($this->currentController, $url[1])) {
                    $this->currentMethod = $url[1];

                    unset($url[1]);
                }
            }

            //Get Parameter list
            $this->params = $url ? array_values($url) : [];

            //Call method and pass the parameter list
            call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
        } else {
            // Controller not found - show 404 or redirect to home
            echo "Controller '$controllerName' not found.";
        }
    }

    public function getURL()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);

            return $url;
        }
        return [];
    }
}