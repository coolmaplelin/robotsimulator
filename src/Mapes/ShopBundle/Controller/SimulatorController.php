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
			
			//Get shop size
			$size_param =  isset($input[0]) && $input[0] ? trim($input[0]) : '';
			$size = explode(' ', $size_param);
			$m = (int)( isset($size[0])&& $size[0] ? $size[0] : 0 );
			$n = (int)( isset($size[1])&& $size[1] ? $size[1] : 0 );
			
			
			//Get robot input
			$robotsinputs = array();
			$num = 1;
			for($i = 1; $i < count($input); $i = $i + 2) {
				if($input[$i] != '') {
					$pos = explode(' ', isset($input[$i]) ? trim($input[$i]) : '');
					$x = isset($pos[0]) ? (int)$pos[0] : '';
					$y = isset($pos[1]) ?(int)$pos[1] : '';
					$heading = isset($pos[2]) ? strtoupper($pos[2]) : '';

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
			
			if ($num > 20) {
				$result = array(
					'shop' => array(
						'status' => 'error',
						'errors' => array('For better visualization, the number of robot is limited under 20')
					)
				);
			}else{
				$result = Simulator::run($n, $m, $robotsinputs, true);
			}
			
			$response = new Response(json_encode($result));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
			
        }
		
        return $this->render('MapesShopBundle:Simulator:index.html.twig', array(
		));
    }
	

}
