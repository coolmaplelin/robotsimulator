
var GLOBAL_steps = 0;   //Maximum steps that robot could move
var GLOBAL_robots = []; //The array of robot status
var GLOBAL_trace = [];  //The array of robot moving tracking
var current_step = 0;   
var movingInterval;


$(function(){
	
	ResetPanel();
	
	$('.form-group .btn-action').click(function(){
		ResetPanel();
		Calculate();
	});
	
	
	$('.btn-play').click(function(){
		startMoving();
		$('.btn-play').hide();
		$('.btn-stop').show();
	});
	$('.btn-stop').click(function(){
		stopMoving();
		$('.btn-play').show();
		$('.btn-stop').hide();
	});
	
	$('.btn-inipos').click(function(){
		stopMoving();
		showInipos();
		$('.btn-play').show();
		$('.btn-stop').hide();
	});
	
	$('.btn-finalpos').click(function(){
		stopMoving();
		showFinalpos();
		$('.btn-play').show();
		$('.btn-stop').hide();
	});
	
}) 

function Calculate()
{
	$.ajax({
		url: '/',
		type: 'post',
		data: {
			input: $("[name='input']").val(),
		},
		dataType: 'JSON',
		success: function (result) {
			var error_msg = '';
			
			var shop = result.shop;
			if(shop.status != 'ok') {
				
				for(var i in shop.errors) {
					if (error_msg != '') {
						error_msg += '<br/>';
					}
					error_msg += shop.errors[i];
				}
				
			}else{
				
				var output_text = '';
				var robots = result.robots;
				for(var key in robots) {
					if (robots[key].status == 'ok' ) {
						output_text += '<div>Robot' + key + ' : ' + robots[key].finalpos.X + ' ' + robots[key].finalpos.Y + ' '+ robots[key].finalpos.H + '<div class="dotWrap"><div class="dot' + key + '"></div></div></div>';
						
					}else{
						output_text += 'Robot' + key + ' : ' + robots[key].errors;
					}
				} 
				$('.outputtext').html(output_text);

				if (result.collision.length > 0) {
					error_msg += 'Robot Collision Detected on Step ';
					for(var i in result.collision) {
						if (i > 0) {
							error_msg += ',';
						}
						error_msg += result.collision[i];
					}
				}

				//set global variable , make sure other function can pick up the result
				GLOBAL_robots = robots;
				GLOBAL_steps = result.maxsteps;
				
				//Draw the table
				drawTable($('#matrix'), shop.m, shop.n); 
				
				$('.btn-play').show();
				$('.btn-stop').hide();
				$('.btn-inipos').show();
				$('.btn-finalpos').show();

			}
			
			if(error_msg != '') {
				$('.error-msg').html('<div class="alert alert-danger">'+ error_msg +'</div>');
			}else{
				$('.error-msg').html('');
			}
			
			
		},
		error: function () {
			//alert('Failed.');
		}
	});
}

/*
* Draw the table using the width and height
* @param $table The table element
* @param m      The height of shop
* @param n      The width of shop
*/
function drawTable($table, m, n)
{
	//The heading indicator
	var str = '	<tr> \
					<td rowspan="' + (m+3) + '" class="heading hw">W</td> \
					<td colspan="' + (n+1) + '" class="text-center heading">N</td> \
					<td rowspan="' + (m+3) + '" class="heading he">E</td> \
				</tr>';

	//First Row with x axis 
	str += '<tr> \
				<td>y/x</td>';
	for(var x = 0; x < n; x++) {
		str += '<td>' + x + '</td>';
	}
	str += '</tr>';

	//Following row with y axis in the first column
	for(var y = 0; y < m; y++) {
		str += '<tr>';
		str += '	<td>' + y + '</td>';
		for(var x = 0; x < n; x++) {
			var pos = 'a(' + x + ',' + y + ')';
			str += '<td data-y="' + y + '" data-x="' + x + '" > \
						<span class="pos">' + pos + '</span> \
						<div class="cell"> \
						</div> \
					</td>';
		}
		str += '</tr>';
	}

	//The heading indicator
	str += '<tr> \
				<td colspan="' + (n+1) + '" class="text-center heading">S</td> \
			</tr>';
	
	$table.html(str);
}

/*
*  Place all robots in the initial position
*/
function showInipos()
{
	$('#matrix .cell').html('');
	for(var key in GLOBAL_robots) {
		var x = GLOBAL_robots[key].inipos.X;
		var y = GLOBAL_robots[key].inipos.Y;
		var h = GLOBAL_robots[key].inipos.H.toLowerCase();
		$('td[data-x="' + x + '"][data-y="' + y + '"]').find(".cell").append('<div class="arrow-h' + h + ' color' + key + '"></div>');
	}
}

/*
*  Place all robots in the final position
*/
function showFinalpos()
{
	$('#matrix .cell').html('');
	for(var key in GLOBAL_robots) {
		var x = GLOBAL_robots[key].finalpos.X;
		var y = GLOBAL_robots[key].finalpos.Y;
		var h = GLOBAL_robots[key].finalpos.H.toLowerCase();
		$('td[data-x="' + x + '"][data-y="' + y + '"]').find(".cell").append('<div class="arrow-h' + h + ' color' + key + '"></div>');
	}
}

/*
* Reset all the buttons and variables
*/
function ResetPanel()
{
	$('.btn-play').hide();
	$('.btn-stop').hide();
	$('.btn-inipos').hide();
	$('.btn-finalpos').hide();

	$('.outputtext').html('');
	$('.error-msg').html('');
	$('#matrix').html('');
	GLOBAL_steps = 0;
	GLOBAL_robots = 0;
    GLOBAL_trace = [];
    current_step = 0;	
	movingInterval = '';
}

/*
*  Start playing the moving of robots
*/
function startMoving()
{
	showInipos();
	
	GLOBAL_trace = [];
	for(var step = 0; step < GLOBAL_steps; step++) {
		
		GLOBAL_trace[step] = [];
		
		for(var key in GLOBAL_robots) {
			
			if(step == 0) {
				var oldpos = {
					'x' : GLOBAL_robots[key].inipos.X,
					'y' : GLOBAL_robots[key].inipos.Y,
					'h' : GLOBAL_robots[key].inipos.H,
				}
			}else if ((step - 1) in GLOBAL_robots[key].trace) {
				var oldpos = {
					'x' : GLOBAL_robots[key].trace[step-1].X,
					'y' : GLOBAL_robots[key].trace[step-1].Y,
					'h' : GLOBAL_robots[key].trace[step-1].H,
				}
			}

			var newpos = oldpos;
			if (step in GLOBAL_robots[key].trace) {
				newpos = {
					'x' : GLOBAL_robots[key].trace[step].X,
					'y' : GLOBAL_robots[key].trace[step].Y,
					'h' : GLOBAL_robots[key].trace[step].H,
				}
				GLOBAL_trace[step].push({
					'key' : key,
					'oldpos' : oldpos,
					'newpos' : newpos
				});
			}
		}
	}
	current_step = 0;
	
	movingInterval = setInterval(function(){ moveRobot(); }, 2000);
	
}

/*
*  Stop playing the moving of robots
*/
function stopMoving()
{
    clearInterval(movingInterval);
	showFinalpos();
}


/*
*  Move robot
*/
function moveRobot()
{
	if(current_step <= GLOBAL_steps) {
		
		for(var i in GLOBAL_trace[current_step]) {
			var key = GLOBAL_trace[current_step][i].key;
			var oldpos = GLOBAL_trace[current_step][i].oldpos;
			var newpos = GLOBAL_trace[current_step][i].newpos;
			
			var oldX = oldpos.x;
			var oldY = oldpos.y;
			var oldH = oldpos.h.toLowerCase();
			var newX = newpos.x;
			var newY = newpos.y;
			var newH = newpos.h.toLowerCase();
			
			var message = 'step ' + current_step + ' : move robot' + key + ' from (' + oldX + ',' + oldY + ',' + oldH + ') to (' + newX + ',' + newY + ',' + newH + ')'; 
			console.log(message);
			
			//Remove arrow from previous position
			$('td[data-x="' + oldX + '"][data-y="' + oldY + '"]').find(".color" + key).remove();
			
			//Place arrow in the new position
			$('td[data-x="' + newX + '"][data-y="' + newY + '"]').find(".cell").append('<div class="arrow-h' + newH + ' color' + key + '"></div>');
			
		}
		current_step++;
	}else{
		clearInterval(movingInterval);
		$('.btn-play').show();
		$('.btn-stop').hide();
	}
}

