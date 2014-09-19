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
	case 'get_add_page':
		$page		= GetGroupPage();
		$data		= array('page'	=> $page);
		
		break;
	case 'get_edit_page':
	    $group_id		= $_REQUEST['id'];
		$page		    = GetGroupPage(GetPage($group_id));
        
        $data		= array('page'	=> $page);
        
	    break;
	case 'get_notes':
	    $group_id		= $_REQUEST['id'];
	    $page		    = GetNotes($group_id);
	    
	    $data		= array('page'	=> $page);
	    
	    break;
	    
	case 'get_list':
	    $count = $_REQUEST['count'];
	    $hidden = $_REQUEST['hidden'];
		$rResult = mysql_query("SELECT    	`shabloni`.`id`,
								          	`shabloni`.`name`
								FROM      	`shabloni`
								GROUP BY 	`shabloni`.`name`");
		
		$data = array(
			"aaData"	=> array()
		);
		
		while ( $aRow = mysql_fetch_array( $rResult ) )
		{
			$row = array();
			for ( $i = 0 ; $i < $count ; $i++ )
			{
				/* General output */
				$row[] = $aRow[$i];
				if($i == ($count - 1)){
					$row[] = '<input type="checkbox" name="check_' . $aRow[$hidden] . '" class="check" value="' . $aRow[$hidden] . '" />';
				}
			}
			$data['aaData'][] = $row;
		}

        break;
	case 'get_pages_list':
		$count    = $_REQUEST['count'];
		$hidden   = $_REQUEST['hidden'];
		$group_id = $_REQUEST['group_id'];
		$scenar_id = $_REQUEST['scenar_id'];
		

		
			$rResult = mysql_query("SELECT    	`quest`.`id`,
								          		`quest`.`name`
									FROM      	`quest`
									WHERE 		`quest`.`scenar_id` = '$scenar_id'
									");
										
		$data = array(
				"aaData"	=> array()
		);
		
		while ( $aRow = mysql_fetch_array( $rResult ) )
		{
			$row = array();
			for ( $i = 0 ; $i < $count ; $i++ )
			{
				/* General output */
				$row[] = $aRow[$i];
				if($i == ($count - 1)){
					$check = "";
					if($aRow['check'] != 0){
						$check.="checked";
					}
					
					$row[] = '<input type="checkbox" name="check_' . $aRow[$hidden] . '" class="check1" value="' . $aRow[$hidden] . '" '.$check.'/>';
				}
			}
			$data['aaData'][] = $row;
		}
						
		break;
	case 'save_group':
		$group_name		= $_REQUEST['nam'];
		$group_pages	= json_decode($_REQUEST['pag']);
		$group_id       = $_REQUEST['group_id'];	
		$scenar_id       = $_REQUEST['scenar_id'];
		
		for ($i = 0; $i < count($group_pages); $i++) {
			$rr = $group_pages[$i][0];
			$gg = $group_pages[$i][1];
			mysql_query("INSERT	INTO `shabloni`
						(`shabloni`.`name`, `shabloni`.`quest_id`,`shabloni`.`notes`, `shabloni`.`scenar_id`)
						VALUES
						('$group_name','$rr','$gg','$scenar_id')");
			
		}

		
		break;        
    case 'disable':
		$group_id = $_REQUEST['id'];
		$delete = mysql_fetch_assoc(mysql_query("SELECT   `name`
										     	FROM   `shabloni`
												WHERE   `id` = $group_id"));
		$delete_row = $delete['name'];
		
		DisableGroup($delete_row);
				
        break;  
    default:
       $error = 'Action is Null';
}

$data['error'] = $error;

echo json_encode($data);


/* ******************************
 *	Workers Functions
 * ******************************
 */


function UpdateGroup($group_id, $group_pages, $group_name){
	

	
}



function DisableGroup($delete_row)
{
    mysql_query("DELETE FROM shabloni
				WHERE `name` = '$delete_row'");
}	


function GetGroupNameById($group_id){
	$res = mysql_fetch_assoc(mysql_query("SELECT   `name`
							     FROM   `group`
								WHERE   `id` = $group_id"));
	
	return $res['name'];
}


function ClearForUpdate($group_id){
	mysql_query("DELETE FROM group_permission
				       WHERE group_id = $group_id");
}

function Getscenari($scenar_id){
	$req = mysql_query("SELECT id,name FROM `task_type`");
	
	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		if($res[id] == $scenar_id){
			$data .= '<option value="' . $res['id'] . '" selected="selected">' . $res['name'] . '</option>';
		}else{
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		}
	}
	return $data;
}

function GetPage($group_id){
	$res = mysql_fetch_assoc(mysql_query("
										SELECT 	scenar_id,
												`name`
										FROM shabloni
										WHERE id = '$group_id'
										"));
	return $res;
}

function GetGroupPage($res = ''){
	
	$data = '
	<div id="dialog-form">
 	    <fieldset style="width: 97% !important;">
	    	<legend>სცენარი</legend>
			<div style=" margin-top: 2px; ">
				<div style="width: 170px; display: inline;">
					<label for="group_name">სცენარის სახელი :</label>
					<input type="text" id="group_name" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" style="display: inline; margin-left: 30px;" value="'.$res[name].'"/>
					<BR><BR><label for="">შაბლონის სახელი :</label>
					<select style="display: inline; margin-left: 24px;" id="scenar_id" class="idls object">'.Getscenari($res[scenar_id]).'</select>					
				</div>
			</div>
        </fieldset>	
 	    <fieldset>
	    	<legend>კინხვები</legend>									
            <div id="dynamic">
                <table class="display" id="pages" style="width: 99% !important; ">
                    <thead>
                        <tr style=" white-space: no-wrap;" id="datatable_header">
                            <th >ID</th> 
                            <th style="width: 180px!important;">კითხვები</th>
                            <th style="width: 30px !important;">#</th>   
                        </tr>
                    </thead>
                </table>
            </div>
        </fieldset>						
    </div>

	<input type="hidden" id="group_id" value="' . $res . '" />
			
    ';
	return $data;
}

function GetNotes($id){

	$data = '
	<div id="dialog-form">
 	    <fieldset>
			<legend>მინიშნება</legend>
			<table>
			<tr>
	    	<td><textarea  style="width: 400px; height:60px; resize: none;" id="minishneba" class="idle" name="content" cols="300" ></textarea></td>
			</tr>
			</table>
        </fieldset>
		<fieldset>
			<legend>ქვოტა</legend>
			<table>
				<tr>
		    		<td>
						<input type="text" id="qvota" class="idle" onblur="this.className=\'idle\'"/>
					</td>
				</tr>
			</table>
        </fieldset>
    </div>
		<input type="text" id="hidden_id" class="idle" onblur="this.className=\'idle\'" style="display:none;" value="'.$id.'"/>
    ';
	return $data;
}

?>