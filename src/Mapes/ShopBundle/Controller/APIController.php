<?php

namespace Mapes\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Mapes\ShopBundle\Entity\Shop as Shop;
use Mapes\ShopBundle\Entity\Robot as Robot;
use Mapes\ShopBundle\Utils\Simulator as Simulator;

class APIController extends Controller
{
    public function create_shopAction(Request $request)
    {
		$input = json_decode(file_get_contents('php://input'), true);
		
		$status = '200';
		
		//Bad request
		if(!isset($input['width']) || !isset($input['height'])) {
			$status = '400 Bad Request';
		}elseif(!is_numeric($input['width']) || $input['width'] <= 0) {
			$status = '400 Bad Request';
		}elseif(!is_numeric($input['height']) || $input['height'] <= 0) {
			$status = '400 Bad Request';
		}
		
		if ($status == '200') {
			$width = $input['width'];
			$height = $input['height'];
			$em = $this->getDoctrine()->getManager();
			$Shop = new Shop();
            $Shop->setWidth($width);
            $Shop->setHeight($height);
            $em->persist($Shop);
            $em->flush();  
			$rtn = array(
				'id' => $Shop->getId(),
				'width' => $width,
				'height' => $height,
			);
			$response = new Response(json_encode($rtn));
		}else{
			$response = new Response(json_encode(array('status' => $status, 'input' => $input)));
		}
		
		$response->headers->set('Content-Type', 'application/json');
		

		return $response;		
    }
	
	public function get_shopAction(Request $request, $id)
    {
		
		$status = '200';

		$em = $this->getDoctrine()->getManager();
		$Shop = $em->getRepository('MapesShopBundle:Shop')->find($id);
		
		if ($Shop) {
			$Robots = $Shop->getRobots();
			$rtnR = array();
			foreach($Robots as $Robot) {
				$rtnR[] = array(
					'x' => $Robot->getPosX(),
					'y' => $Robot->getPosY(),
					'heading' => $Robot->getHeading(),
					'commands' => $Robot->getCommands(),
				);
			}
			
			$rtnS = array(
				'id' => $Shop->getId(),
				'width' => $Shop->getWidth(),
				'height' => $Shop->getHeight(),
				'robots' => $rtnR
			);
			
			$response = new Response(json_encode($rtnS));
		}else{
			$status = '404 Shop not found';
			$response = new Response(json_encode(array('status' => $status)));
		}
		
		$response->headers->set('Content-Type', 'application/json');

		return $response;		
    }
	
	public function delete_shopAction(Request $request, $id)
    {

		$em = $this->getDoctrine()->getManager();
		$Shop = $em->getRepository('MapesShopBundle:Shop')->find($id);
		
		$status = 'ok';
		if ($Shop) {
			$em->remove($Shop);
			$em->flush();
			$response = new Response(json_encode(array('status' => $status)));
		}else{
			$status = '404 Shop not found';
			$response = new Response(json_encode(array('status' => $status)));
		}
		
		$response->headers->set('Content-Type', 'application/json');

		return $response;		
    }
	
    public function create_robotAction(Request $request, $id)
    {
		$input = json_decode(file_get_contents('php://input'), true);
		
		$em = $this->getDoctrine()->getManager();
		$Shop = $em->getRepository('MapesShopBundle:Shop')->find($id);
		
		$status = '200';
		
		if ($Shop) {
			
			$x = isset($input['x']) ? $input['x'] : '';
			$y = isset($input['y']) ? $input['y'] : '';
			$heading = isset($input['heading']) ? $input['heading'] : '';
			$commands = isset($input['commands']) ? $input['commands'] : '';
			
			$validate_result =  Simulator::validateRobot($Shop->getWidth(), $Shop->getHeight(), $x, $y, $heading, $commands);
			if ($validate_result['status'] == 'ok') {
				
				$Robot = new Robot();
				$Robot->setShop($Shop);
				$Robot->setPosX($x);
				$Robot->setPosY($y);
				$Robot->setHeading($heading);
				$Robot->setCommands($commands);
				$em->persist($Robot);
				$em->flush();  
				
				$rtn = array(
					'id' => $Robot->getId(),
					'x' => $x,
					'y' => $y,
					'heading' => $heading,
					'commands' => $commands
				);
				$response = new Response(json_encode($rtn));
			}else{
				
				$status = '400 Bad robot input';
				$response = new Response(json_encode(array('status' => $status)));
				
			}
		}else{
			
			$status = '404 Shop not found';
			$response = new Response(json_encode(array('status' => $status)));
		}
		
		$response->headers->set('Content-Type', 'application/json');

		return $response;		
    }

    public function update_robotAction(Request $request, $id, $rid)
    {
		$input = json_decode(file_get_contents('php://input'), true);
		
		$em = $this->getDoctrine()->getManager();
		$Shop = $em->getRepository('MapesShopBundle:Shop')->find($id);
		$Robot = $em->getRepository('MapesShopBundle:Robot')->find($rid);
		
		$status = '200';
		
		if ($Shop && $Robot && $Robot->getShopId() == $Shop->getId() ) {
			$x = isset($input['x']) ? $input['x'] : '';
			$y = isset($input['y']) ? $input['y'] : '';
			$heading = isset($input['heading']) ? $input['heading'] : '';
			$commands = isset($input['commands']) ? $input['commands'] : '';
			$validate_result = Simulator::validateRobot($Shop->getWidth(), $Shop->getHeight(), $x, $y, $heading, $commands);
			if ($validate_result['status'] == 'ok') {
				
				$Robot->setPosX($x);
				$Robot->setPosY($y);
				$Robot->setHeading($heading);
				$Robot->setCommands($commands);
				$em->persist($Robot);
				$em->flush();  
				
				$rtn = array(
					'x' => $x,
					'y' => $y,
					'heading' => $heading,
					'commands' => $commands
				);
				$response = new Response(json_encode($rtn));
			}else{
				
				$status = '400 Bad robot input';
				$response = new Response(json_encode(array('status' => $status)));
				
			}
		}else{
			
			$status = '404 Shop/Robot not found or robot does not belong to this shop';
			$response = new Response(json_encode(array('status' => $status)));
		}
		
		$response->headers->set('Content-Type', 'application/json');

		return $response;		
    }

	public function delete_robotAction(Request $request, $id, $rid)
    {

		$em = $this->getDoctrine()->getManager();
		$Shop = $em->getRepository('MapesShopBundle:Shop')->find($id);
		$Robot = $em->getRepository('MapesShopBundle:Robot')->find($rid);
		
		if ($Shop && $Robot && $Robot->getShopId() == $Shop->getId() ) {
			
			$em->remove($Robot);
			$em->flush();
			
			$status = 'ok';
			$response = new Response(json_encode(array('status' => $status)));
		}else{
			$status = '404 Shop/Robot not found or robot does not belong to this shop';
			$response = new Response(json_encode(array('status' => $status)));
		}
		
		$response->headers->set('Content-Type', 'application/json');

		return $response;		
    }

	public function run_simulatorAction(Request $request, $id)
    {

		$em = $this->getDoctrine()->getManager();
		$Shop = $em->getRepository('MapesShopBundle:Shop')->find($id);
		
		$status = 'ok';
		
		if ($Shop) {
			
			$Robots = $Shop->getRobots();
			
			//Generate the robot input that to be passed to class simulator
			$robotsinput = array();
			
			foreach ($Robots as $key => $Robot) {
				$robotsinput[$key+1] = array(
					'x' => $Robot->getPosX(),
					'y' => $Robot->getPosY(),
					'heading' => $Robot->getHeading(),
					'commands' => $Robot->getCommands(),
				);
			}
			
			$result = Simulator::run($Shop->getWidth(), $Shop->getHeight(), $robotsinput);
			$errors = array();
			
			if($result['shop']['status'] != 'ok') {
				$errors = $result['shop']['errors'];
			}
			$robots = array();
			foreach($result['robots'] as $rb) {
				if($rb['status'] == 'ok' ) {
					$robots[] = $rb['finalpos']['X']. ' '.$rb['finalpos']['Y']. ' '.$rb['finalpos']['H'];
				}else{
					$robots[] = $rb['errors'];
				}
			}
			if(!empty($result['collision']) ) {
				$errors[] = 'Robot collision detected';
			}
			
			$rtn = array(
				'status' => !empty($errors) ? 'error' : 'ok',
				'errors' => $errors,
				'id' => $Shop->getId(),
				'width' => $Shop->getWidth(),
				'height' => $Shop->getHeight(),
				'robots' => $robots,
			);
			
			$response = new Response(json_encode($rtn));
			
		}else{
			$status = '404 Shop not found';
			$response = new Response(json_encode(array('status' => $status)));
		}
		
		$response->headers->set('Content-Type', 'application/json');

		return $response;		
    }
	
}
