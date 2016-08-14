<?php

namespace Mapes\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Mapes\ShopBundle\Utils\Simulator as Simulator;

class SimulatorController extends Controller
{
    public function indexAction(Request $request)
    {
		if($request->getMethod() == 'POST' || $request->isXmlHttpRequest() ) {
			
			$input = explode("\n", $request->request->get('input'));
			
			//Validate shop size
			$size_param =  isset($input[0]) && $input[0] ? trim($input[0]) : '';
			$size = explode(' ', $size_param);
			$m = (int)( isset($size[0])&& $size[0] ? $size[0] : 0 );
			$n = (int)( isset($size[1])&& $size[1] ? $size[1] : 0 );
			
			//var_export($size_param);
			
			$robotsinputs = array();
			$num = 1;
			for($i = 1; $i < count($input); $i = $i + 2) {
				if($input[$i] != '') {
					$pos = explode(' ', isset($input[$i]) ? trim($input[$i]) : '');
					$x = (int)$pos[0];
					$y = (int)$pos[1];
					$heading = strtoupper($pos[2]);

					$commands = isset($input[$i+1]) ? trim($input[$i+1]) :'';	
					$robotsinputs[$num] = array(
						'x' => $x,
						'y' => $y,
						'heading' => $heading,
						'commands' => $commands,
						
					);
					$num++;
				}
			}
			
			$result = Simulator::run($n, $m, $robotsinputs);
			$response = new Response(json_encode($result));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
			
        }
		
        return $this->render('MapesShopBundle:Simulator:index.html.twig', array(
		));
    }
	

}
