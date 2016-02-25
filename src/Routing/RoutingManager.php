<?php

namespace Routing;

use Slim\Middleware;

class RoutingManager extends Middleware{
    
    private $cacheDir = "";
    private $routes = array();
    private $controllers = array();
    
    public function __construct( $options ){
        $this->cacheDir = dirname(__FILE__).'\\..\\..\\'.$options['cache'];
        $this->controllers = $options['controllers_directory'];
        $this->controller_namespace = $options['controllers_namespace'];
    }    
    
    public function call(){
        $this->loadMapping();

        $this->next->call();
    }

    public function clearCache( $outputOnScreen = false ){
        $this->createRoutes( $outputOnScreen );
    }

    private function loadMapping(){
        if( !file_exists( $this->cacheDir.'\mapping.php' ) ){
            var_dump( $this->cacheDir.'\mapping.php');
            throw new \Exception("RoutingManager No Mapping File Found");
        }

        require_once $this->cacheDir.'/mapping.php';
    }
    
    private function createRoutes( $outputOnScreen = false ){
        $controllers = $this->loadControllers();

        if( $outputOnScreen == true ){
            echo "\r\n\r\nCreating Mapping File...\r\n\r\n";
            $routes = array();
        }

        foreach( $controllers as $controller ){
            foreach( $controller as $annotation ){
                $route = new Route();

                $route->class = $annotation->class;
                $route->method = $annotation->method;
                $route->route = $annotation->getValue( "Route" );
                $route->httpMethod = $annotation->getValue( "Method" );
                $route->name = $annotation->getValue("Name");
                $route->baseRoute = "";

                if( $outputOnScreen == true ){
                    array_push( $routes, "({$route->httpMethod})\t{$route->route}");
                }

                array_push( $this->routes, $route );
            }
        }

        if( $outputOnScreen == true ){
            foreach( $routes as $line ){
                echo $line."\r\n";
            }

            echo "\r\nTotal routes: \033[32m" . count ( $routes ) . "\033[37m\r\n\r\n";
        }
        

        $this->mapAnnotations();
    }

    
    private function loadControllers(){
        $annotations = array();
        
        foreach( glob( $this->controllers."/*.php" ) as $filename ){
            array_push( $annotations, $this->getAnnotationsOfFile( $filename, $this->controller_namespace ) );
        }        
        return $annotations;
    }
    
    private function getAnnotationsOfFile( $filename, $namespace ){
        require_once $filename;
        
        $className = explode("/", $filename );
        $className = $className[ count( $className ) - 1 ];
        $className = str_replace(".php", "", $className);
        $className = $namespace."\\".$className;
        
        $class = new $className();
        return $class->getAnnotations();
    }
    
    private function mapAnnotations(){
        $this->createCachingFile();
    }
    
    private function createCachingFile(){
        $cacheFile = $this->cacheDir . "/mapping.php";
        
        if( !file_exists( $cacheFile ) ){
            if( !file_exists( $this->cacheDir ) ){
                mkdir( $this->cacheDir, 0700, true );
            }
        }
        
        $fp = fopen( $cacheFile, "w" );
        fwrite($fp, "<?php \r\n\r\n");
        fwrite($fp, 'use Slim\Slim;');
        fwrite($fp, "\r\n");
        fwrite($fp, '$app = Slim::getInstance();');
        fwrite($fp, "\r\n\r\n");
        foreach( $this->routes as $route ){
            $map = $this->map( $route );
            if( $map !== null ){
                fwrite($fp, $map);
                fwrite($fp,"\r\n");
            }
        }
        
        fclose( $fp );
    }
    
    private function map( $route, $applicationRoute = "" ){
        if( $route->route !== null ){
            $mapping = '$app->map("{%applicationRoute%}{%route%}", "{%execution%}" )->via("{%httpMethod%}")->name("{%name%}");';
            $mapping = str_replace("{%applicationRoute%}", $route->baseRoute, $mapping );
            
            $mapping = str_replace("{%httpMethod%}", $route->httpMethod, $mapping );
            $mapping = str_replace("{%route%}", $route->route, $mapping );
            $mapping = str_replace("{%httpMethod%}", $route->httpMethod, $mapping );
            $mapping = str_replace("{%name%}", $route->name, $mapping );
            $mapping = str_replace("{%execution%}", $this->execution( $route ) , $mapping );
            
            return $mapping;
        }

        return null;
    }
    
    private function execution( $route ){
        return $route->class."::___".$route->method;
    }
}