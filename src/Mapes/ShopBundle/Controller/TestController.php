<?php

namespace Mapes\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TestController extends Controller
{
    public function indexAction(Request $request)
    {
		/* Test shop creation */
		/*$url = 'http://heyyou/shop';
		$http_method = 'POST';
		$args = array(
			'width' => 7,
			'height' => 8,
		);*/
		
		/* Test get shop */
		/*$url = 'http://heyyou/shop/2';
		$http_method = 'GET';
		$args = array();*/

		/* Test DELETE shop */
		/*$url = 'http://heyyou/shop/2';
		$http_method = 'DELETE';
		$args = array();*/
		
		/* Test CREATE robot */
		/*$url = 'http://heyyou/shop/3/robot';
		$http_method = 'POST';
		$args = array(
			'x' => 3,
			'y' => 3,
			'heading' => 'E', 
			'commands' => 'MLMLMRMRMRRM',
		);*/
		
		/* Test UPDATE robot */
		/*$url = 'http://heyyou/shop/1/robot/4';
		$http_method = 'PUT';
		$args = array(
			'x' => 1,
			'y' => 2,
			'heading' => 'N', 
			'commands' => 'LMLMLMLMM',
		);*/
		
		/* Test DELETE robot */
		/*$url = 'http://heyyou/shop/3/robot/4';
		$http_method = 'DELETE';
		$args = array();*/
		
		/* Test Simulator */
		$url = 'http://heyyou/shop/1/execute';
		$http_method = 'POST';
		$args = array();
		
		$timeout = 30;
		$verify_ssl = false;
		/*if($http_method == 'GET' && !empty($args)) {
			$url .= '?'.http_build_query($args);
		}*/
		//echo $url;die();
        $json_data = json_encode($args);

        if (function_exists('curl_init') && function_exists('curl_setopt')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
			if($http_method == 'POST') {
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
			}elseif($http_method == 'GET'){
				curl_setopt($ch, CURLOPT_HTTPGET, true);
			}elseif($http_method == 'PUT') {
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
			}elseif($http_method == 'DELETE') {
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
			}
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $verify_ssl);
            
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
            $result = curl_exec($ch);
            curl_close($ch);
        } else {
            $result    = file_get_contents($url, null, stream_context_create(array(
                'http' => array(
                    'protocol_version' => 1.1,
                    'user_agent'       => 'PHP-MCAPI/2.0',
                    'method'           => 'POST',
                    'header'           => "Content-type: application/json\r\n".
                                          "Connection: close\r\n" .
                                          "Content-length: " . strlen($json_data) . "\r\n",
                    'content'          => $json_data,
                ),
            )));
        }

        echo $result ? $result : false;
		
		exit;
    }
}
