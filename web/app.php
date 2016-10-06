<?php

use Symfony\Component\HttpFoundation\Request;

/**
 * @var Composer\Autoload\ClassLoader
 */
$loader = require __DIR__.'/../app/autoload.php';
include_once __DIR__.'/../var/bootstrap.php.cache';

switch($_SERVER["HTTP_HOST"])
{
	case "heyyou":
		$env = "dev"; $debug = true; break;
	case "ec2-52-62-172-4.ap-southeast-2.compute.amazonaws.com":
		$env = "aws"; $debug = true; break;
	case "robot.maplelin.net":
		$env = "prod"; $debug = true; break;
	default:
		$env = "prod"; $debug = true; break;
		
}
$kernel = new AppKernel($env, $debug);
$kernel->loadClassCache();

//$kernel = new AppCache($kernel);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
//Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
