<?php
/* ******************************
 *	Workers aJax actions
 * ******************************
 */
include('../../includes/classes/core.php');



$action 	= $_REQUEST['act'];
$user_id	= $_SESSION['USERID'];
$error 		= '';
$data 		= '';

switch ($action) {
	case 'get_come_in_page':
		$action     = $_REQUEST['action'];

 		$page		= GetComeIn($action);
 		$data		= array('page'	=> $page);
		
		break; 
	case 'save_act':
		$person_id  = $_REQUEST['user'];
		$pwd        = $_REQUEST['pwd'];
		$action     = $_REQUEST['action'];
		$check      = CheckPassword($person_id, $pwd);
		
		if($check){
			 switch ($action){
				case '1' :
					if(CheckHere($person_id)){
						WorkerStart($person_id);
					}else{
						$error = "შეცდომა: " . GetName($person_id) . "  უკვე არის აღრიცხული";
					}			
					break;
				case '2' :
					if(!CheckHere($person_id)){
						WorkerEnd($person_id);
					}else{
						$error = "შეცდომა: " . GetName($person_id) . "  არ  არის აღრიცხული";
					}
					break;
				case '3' :
					GoTimeOut($person_id);
					break;
				case '4' :
					BackTimeOut($person_id);
					break;
				default:
					break;
			}
		}else{
			$error = 'პაროლი არასწორია';
		}
		break;
	case 'get_balance' :
		$page = GetBalance();
 		$data = array('page'	=> $page);
 		
 		break;
	case 'check_password' :
		$person_id = $_REQUEST['user'];
		$pwd       = $_REQUEST['pwd'];
		$check = CheckPassword($person_id, $pwd);
		
		if($check){
			$page = 'true';
			$data = array('page'	=> $page);
		}else{
			$error = "პაროლი არასწორია!";
		}
		break;
    default:
       $error = 'Action is Null';
}

$data['error'] = $error;

echo json_encode($data);


/* ******************************
 *	Tabel Functions
 * ******************************
 */

function GetName($person_id){
	
	$res = mysql_fetch_assoc(mysql_query("SELECT   name AS `name`
											FROM   persons
										   WHERE   id =$person_id"));
	
	return $res['name'];
}

function WorkerStart($person_id){
	
	$date = date('Y-m-d H:i:s');
	
	mysql_query("INSERT INTO  worker_action
				     (person_id, start_date, actived)
				 VALUES
				     ($person_id, '$date', 1)
			    ");
}

function WorkerEnd($person_id){
	
	$date = date('Y-m-d H:i:s');
	
	$res = mysql_fetch_assoc(mysql_query("SELECT  MAX(id) AS `id`
											FROM  worker_action
											WHERE person_id = $person_id"));
	
	mysql_query("UPDATE worker_action
				SET
				end_date = '$date',
				actived  = 0
				WHERE    person_id = $person_id AND id = $res[id]");
}

function GoTimeOut($person_id){
	
	$date = date('Y-m-d H:i:s');
	
	$res = mysql_fetch_assoc(mysql_query("SELECT  MAX(id) AS `id`
											FROM  worker_action
											WHERE person_id = $person_id"));
	
	mysql_query("UPDATE worker_action
				 SET
				 timeout_start_date = '$date',
				 actived            = 2
				 WHERE person_id    = $person_id AND id = $res[id]");
}

function BackTimeOut($person_id){
	$date = date('Y-m-d H:i:s');
	
	$res = mysql_fetch_assoc(mysql_query("SELECT  MAX(id) AS `id`
			FROM  worker_action
			WHERE person_id = $person_id"));
	
	mysql_query("UPDATE worker_action
	SET
	timeout_end_date = '$date',
	actived          = 3
	WHERE person_id  = $person_id AND id = $res[id]");
}

function CheckPassword($person_id, $pwd){
	$check = false;
	$res = mysql_fetch_assoc(mysql_query("SELECT `password` AS `pwd`
											FROM   persons
											WHERE  id = $person_id"));
	
	if($res['pwd'] == $pwd){
		$check = true;
	}
	
	
	return $check;
}

function GetWorkers($action)
{
	$data = '';
	
	switch ($action){
		case '1' :
		$req = mysql_query("SELECT	        DISTINCT `persons`.`id`,
										    `persons`.`name`
								FROM		`persons`
								LEFT JOIN	`worker_action` ON `worker_action`.`person_id` = `persons`.`id`
								WHERE      ISNULL(worker_action.actived) OR (SELECT actived FROM worker_action WHERE person_id = `persons`.`id` ORDER BY id DESC LIMIT 1) = 0
							");
			break;
		case '2' :
			
			$req = mysql_query("SELECT	    DISTINCT `persons`.`id`,
										    `persons`.`name`
								FROM		`persons`
								LEFT JOIN	`worker_action` ON `worker_action`.`person_id` = `persons`.`id`
							    WHERE       worker_action.actived = 1 OR worker_action.actived = 3
								");
			
			break;
			
		case '3' :
			
			$req = mysql_query("SELECT	     DISTINCT `persons`.`id`,
										    `persons`.`name`
								FROM		`persons`
								LEFT JOIN	`worker_action` ON `worker_action`.`person_id` = `persons`.`id`
							    WHERE        worker_action.actived = 1
								");
			
			break;
			
		case '4' :
			
			$req = mysql_query("SELECT     DISTINCT `persons`.`id`,
										  `persons`.`name`
								FROM	  `persons`
								LEFT JOIN `worker_action` ON `worker_action`.`person_id` = `persons`.`id`
								WHERE      worker_action.actived = 2
								");
			
			break;
			
		default:
			$req = mysql_query("SELECT	        DISTINCT `persons`.`id`,
										    `persons`.`name`
								FROM		`persons`
								LEFT JOIN	`worker_action` ON `worker_action`.`person_id` = `persons`.`id`
								");
			
			break;	
	}


		$data = '<option value="0" selected="selected"></option>';


	while( $res = mysql_fetch_assoc($req)){
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}

	return $data;
}

function CheckHere($person_id){
	$check = true;
	$res = mysql_query("SELECT  id
						  FROM  worker_action
						 WHERE  person_id = $person_id AND ISNULL(end_date)");
	
	if(mysql_num_rows($res) > 0){
		$check = false;
	}
	
	return $check;
}

function GetComeIn($action){	
	$data = '
	<div id="dialog-form">
 	    <fieldset style="width: 400px;">
	    	<legend>ძირითადი ინფორმაცია</legend>
			<div style=" margin-top: 2px; ">
				<div style="width: 170px; display: inline;">
			<table width="80%" class="dialog-form-table" cellpadding="10px" >
				<tr	style="float: left">
			
					<td style="width: 170px;"><label for="user">მომხმარებელი</label></td>
					<td>
						<select id="user" class="idls">' . GetWorkers($action) . '</select>
					</td>		
																			
				</tr>
				<tr style="float: left">
					<td style="width: 170px;>
						<label for="password">პაროლი&emsp;&emsp;&emsp;&nbsp;</label>
					</td>
					<td>
						<input type="password" id="password" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" style="display: inline; margin-left: 25px;" value=""/>
      				</td>																										

				</tr>									
			</table>
						</div>				
					</th>	
				</div>
			</div>
        </fieldset>						
    </div>

	<input type="hidden" id="action" value="0"/>
			
    ';
	return $data;
}

function GetBalance(){
	$data = '
	<div id="dialog-form">
 	    <fieldset style="width: 400px;">
	    	<legend>ძირითადი ინფორმაცია</legend>
			<div style=" margin-top: 2px; ">
				<div style="width: 170px; display: inline;">
			<table width="80%" class="dialog-form-table" cellpadding="10px" >
				<tr	style="float: left">
		
					<td style="width: 170px;"><label for="user">მომხმარებელი</label></td>
					<td>
						<select id="user" class="idls">' . GetWorkers(10) . '</select>
					</td>
										
				</tr>
				<tr style="float: left">
					<td style="width: 170px;>
						<label for="password">პაროლი&emsp;&emsp;&emsp;&nbsp;</label>
					</td>
					<td>
						<input type="password" id="password" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" style="display: inline; margin-left: 25px;" value=""/>
      				</td>

				</tr>
			</table>		
				</div>
			</th>

		<div style="margin-left:180px;"><input type="button" id="check" value="შემოწმება" /></div>
	 </div>
        </fieldset>
								<br><br>
	<fieldset>	
		<legend>ბალანსი</legend>									
														
		<div id="button_area">
	            	<div class="left" style="width: 250px;">
	            		<label for="search_start" class="left" style="margin: 5px 0 0 9px;">დასაწყისი</label>
	            		<input type="text" name="search_start" id="search_start" class="inpt right"/>
	            	</div>
	            	<div class="right" style="width: 250px;">
	            		<label for="search_end" class="left" style="margin:5px 0 0 3px">დასასრული</label>
	            		<input type="text" name="search_end" id="search_end" class="inpt right" />
            		</div>
           </div>							
								
		   <table class="display" id="report">
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 120px !important;">თარიღი</th>
                            <th style="width: 100% !important;">პიროვნება</th>
                            <th style="width: 120px !important;">მოსვლა</th>
                            <th style="width: 120px !important;">შეს. დაწყება</th>
                            <th style="width: 120px !important;">შეს. დასრულება</th>
                            <th style="width: 120px !important;">წასვლა</th>
                            <th style="width: 120px !important;">შესვენების დრო</th>
                            <th style="width: 120px !important;">მუშაობის  დრო</th>
                            <th style="width: 120px !important;">+</th>
                            <th style="width: 120px !important;">-</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden">
                            	<input type="text" name="search_id" value="ფილტრი" class="search_init" style="width: 80px !important;"/>
                            </th>
                            <th>
                            	<input type="text" name="search_number" value="ფილტრი" class="search_init" style="width: 80px !important;">
                            </th>
                            <th>
                            	<input type="text" name="search_method" value="ფილტრი" class="search_init">
                            </th>
                            <th>
                            	<input type="text" name="search_method" value="ფილტრი" class="search_init" style="width: 80px !important;">
                            </th>
                            <th>
                            	<input type="text" name="search_method" value="ფილტრი" class="search_init" style="width: 80px !important;">
                            </th>
                            <th>
                            	<input type="text" name="search_method" value="ფილტრი" class="search_init" style="width: 80px !important;">
                            </th>
                            <th>
                            	<input type="text" name="search_method" value="ფილტრი" class="search_init" style="width: 80px !important;">
                            </th>
                            <th>
                            	<input type="text" name="search_method" value="ფილტრი" class="search_init" style="width: 80px !important;">
                            </th>
                            <th>
                            	<input type="text" name="search_method" value="ფილტრი" class="search_init" style="width: 80px !important;">
                            </th>
                        </tr>
                   </thead>
                   <tfoot>
                        <tr>
                            <th>&nbsp;</th>                         
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                    </tfoot>
                </table>
		</fieldset>
    </div>


		
    ';
	return $data;
}

?>