<?php

namespace Mapes\ShopBundle\Utils;

use Mapes\ShopBundle\Entity\Shop as Shop;


class Simulator
{
	private static $_allowed_heading = array('E','S','W','N');
	private static $_allowed_cmd = array('L','R','M');
	
	/* This validates the input parameters for shop
    * @param  integer  	$width   		
    * @param  integer  	$height   		
    * @param  boolean  	$haslimit   		
    * @return array		Array of result    
	*/
	public static function validateShop($width, $height, $haslimit = false)
	{
		$status = 'ok';
		
		$m = (int)$height;
		$n = (int)$width;
		
		$errors = array();
		
		if ($m === 0 || $n === 0) {
			$status = 'error';
			$errors[] = 'Shop size is invalid';
		} elseif ($haslimit && (is_numeric($m) && $m > 20) || (is_numeric($n) && $n > 20) ) {
			$status = 'error';
			$errors[] = 'For better visualization, shop size is limited under 20 * 20';
		}
		
		$result = array(
			'status' => $status,
			'm' => $m,
			'n' => $n,
			'errors' => $errors
		);
		
		return $result;
	}
	
	/* This validates the input parameters as per robot
    * @param  integer  	$width   	The width of shop	
    * @param  integer  	$height   	The height of shop	
    * @param  integer  	$x   		The x axis of robot initial position
    * @param  integer  	$y   		The y axis of robot initial position
	* @param  char  	$heading   	The heading of robot initial position
	* @param  string  	$commands   The commands that robot excutes
    * @return array					Array of result 
	*/
    public static function validateRobot($width, $height, $x, $y, $heading, $commands)
    {
		$status = 'ok';
		$heading = strtoupper($heading);
		$commands = strtoupper($commands);
		
		$errors = array();
		
		$m = $height;
		$n = $width;
		
		if (!is_numeric($x) || $x < 0 || !is_numeric($y) || $y < 0) {
			$status = 'error';
		}elseif ($x >= $n || $y >= $m) {
			$status = 'error';
		}elseif (!in_array($heading, self::$_allowed_heading)) {
			$status = 'error';
		}
		
		if($commands == '') {
			$status = 'error';
		}else{
			$allowed_cmd = array('L','R','M');
			$cmd_array = str_split($commands);
			$cmd_count_values = array_count_values($cmd_array);
			foreach($cmd_count_values as $value => $count) {
				if($value === '' || !in_array($value, self::$_allowed_cmd)) {
					$status = 'error';
					break;
				}
			}
		}
		
		if($status == 'error') {
			$errors[] = 'invalid input';
		}

		$result = array(
			'status' => $status,
			'inipos' => array(
				'X' => $x,
				'Y' => $y,
				'H' => $heading
			),
			'cmd' => str_split($commands),
			'errors' => $errors
		);
		
		return $result;
		
    }
        
	/* This runs a simulation in the shop using the Robots
    * @param  integer	$width 			The width of shop
    * @param  integer	$height			The height of shop
    * @param  array  	$robotsinput	Array of robot's input to be passed
    * @param  boolean  	$haslimit		If limit the shop size
    * @return array          			Array of result
	*/
	public static function run($width, $height, $robotsinput, $haslimit = false)
	{
		$shop = self::validateShop($width, $height, $haslimit);
		
		$m = $height;
		$n = $width;
		
		$robots = array();
		//Get validation info
		foreach($robotsinput as $key => $rbinput) {
			$x = $rbinput['x'];
			$y = $rbinput['y'];
			$heading = $rbinput['heading'];
			$commands = $rbinput['commands'];
			$robots[$key] = self::validateRobot($width, $height, $x, $y, $heading, $commands );
		}
		
		//Initialize result
		$maxSteps = 0;
		foreach($robots as $key => $robot) {
			$robots[$key]['finalpos'] = $robots[$key]['inipos'];
			$robots[$key]['trace'] = array();
			if ($robots[$key]['status'] == 'ok' && $maxSteps < count($robots[$key]['cmd']) ) {
				$maxSteps = count($robots[$key]['cmd']);
			}
		}
		
		//var_dump($robots);die();
		//Parallelly tracking each robot
		for ($i = 0; $i < $maxSteps; $i++) {
			foreach($robots as $key => $robot) {
				if($robot['status'] == 'ok' && isset($robot['cmd'][$i])) {
					$cmd = $robot['cmd'][$i];
					$oldX = $i === 0 ? $robots[$key]['inipos']['X'] : $robots[$key]['trace'][$i-1]['X'];
					$oldY = $i === 0 ? $robots[$key]['inipos']['Y'] : $robots[$key]['trace'][$i-1]['Y'];
					$oldH = $i === 0 ? $robots[$key]['inipos']['H'] : $robots[$key]['trace'][$i-1]['H'];
					
					if($cmd == 'L' || $cmd == 'R') {
						
						$newX = $oldX;
						$newY = $oldY;
						if($cmd == 'L') {
							switch($oldH) {
								case 'E':
									$newH = 'N';
									break;
								case 'S':
									$newH = 'E';
									break;
								case 'W':
									$newH = 'S';
									break;
								case 'N':
									$newH = 'W';
									break;
							}
						}
						if($cmd == 'R') {
							switch($oldH) {
								case 'E':
									$newH = 'S';
									break;
								case 'S':
									$newH = 'W';
									break;
								case 'W':
									$newH = 'N';
									break;
								case 'N':
									$newH = 'E';
									break;
							}
						}
						
					}elseif( $cmd == 'M') {
						
						$newH = $oldH;
						/*switch($oldH) {
							case 'E':
								$newX = $oldX;
								$newY = ($oldY + 1) == $n ? $oldY : ($oldY + 1) ;
								break;
							case 'S':
								$newX = ($oldX + 1) == $m ? $oldX : ($oldX + 1);
								$newY = $oldY;
								break;
							case 'W':
								$newX = $oldX;
								$newY = $oldY === 0 ? $oldY : ($oldY - 1);
								break;
							case 'N':
								$newX = $oldX === 0 ? $oldX : ($oldX - 1);
								$newY = $oldY;
								break;
						}*/
						switch($oldH) {
							case 'E':
								$newX = ($oldX + 1) == $n ? $oldX : ($oldX + 1);
								$newY = $oldY ;
								break;
							case 'S':
								$newX = $oldX;
								$newY = ($oldY + 1) == $m ? $oldY : ($oldY + 1);
								break;
							case 'W':
								$newX = $oldX === 0 ? $oldX : ($oldX - 1);
								$newY = $oldY;
								break;
							case 'N':
								$newX = $oldX;
								$newY = $oldY === 0 ? $oldY : ($oldY - 1);;
								break;
						}
					}
					
					$robots[$key]['trace'][] = array(
						'X' => $newX,
						'Y' => $newY,
						'H' => $newH
					);
					$robots[$key]['finalpos'] = array(
						'X' => $newX,
						'Y' => $newY,
						'H' => $newH
					);
				}
			}
		}
		
		//Detected if there is any collision
		$collision = array(); //The value in this array are the step when there is a collision
		for ($step = 0; $step < $maxSteps; $step++) {
			$pos_array = array();
			foreach($robots as $key => $robot) {
				if($robot['status'] == 'ok' && isset($robot['trace'][$step])) {
					$pos = $robot['trace'][$step]['X'] . ',' .$robot['trace'][$step]['Y'];
					if (in_array($pos, $pos_array) && !in_array($step+1, $collision) ) {
						$collision[] = $step + 1;
					}else{
						$pos_array[] = $pos;
					}
				}
			}
		}
		
		$result = array(
			'shop' => $shop,
			'robots' => $robots, 
			'maxsteps' => $maxSteps,
			'collision' => $collision
		);
		
		return $result;		
	}
}

?>