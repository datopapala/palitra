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
		$page		    = GetGroupPage($group_id);
        
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
		$group_pages	= json_decode(stripslashes($_REQUEST['pag']));
		$group_id       = $_REQUEST['group_id'];	
		$scenar_id       = $_REQUEST['scenar_id'];

		if(empty($group_id)){
			SaveGroup($group_name, $group_pages, $scenar_id);
		}else{
			ClearForUpdate($group_id);
			UpdateGroup($group_id, $group_pages, $group_name);
		}
  		
		break;        
    case 'disable':
		$group_id = $_REQUEST['id'];
		DisableGroup($group_id);
				
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


function SaveGroup($group_name, $group_pages, $scenar_id){
	
	foreach($group_pages as $group_page) {
		mysql_query("INSERT	INTO `shabloni`
						(`shabloni`.`name`, `shabloni`.`quest_id`, `shabloni`.`scenar_id`)
					VALUES
						('$group_name','$group_page','$scenar_id')");
	}

		
}

function UpdateGroup($group_id, $group_pages, $group_name){
	

	
}



function DisableGroup($group_id)
{
    mysql_query("DELETE FROM `group`
				 WHERE  `id` = '$group_id'");
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

function Getscenari($group_id){
	$req = mysql_query("SELECT id,name FROM `pattern`");
	
	$data .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
		
			$data .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
		
	}
	return $data;
}

function GetGroupPage($res = ''){
	
	$data = '
	<div id="dialog-form">
 	    <fieldset style="width: 400px;">
	    	<legend>შაბლონი</legend>
			<div style=" margin-top: 2px; ">
				<div style="width: 170px; display: inline;">
					<label for="group_name">შაბლონის სახელი :</label>
					<input type="text" id="group_name" class="idle" onblur="this.className=\'idle\'" onfocus="this.className=\'activeField\'" style="display: inline; margin-left: 25px;" value="'.GetGroupNameById($res).'"/>
					<BR><BR><label for="">სცენარის სახელი :</label>
					<select style="display: inline; margin-left: 30px;" id="scenar_id" class="idls object">'.Getscenari().'</select>					
				</div>
			</div>
        </fieldset>	
 	    <fieldset>
	    	<legend>კინხვები</legend>									
            <div id="dynamic">
                <table class="display" id="pages" style="width: 380px !important; ">
                    <thead>
                        <tr style=" white-space: no-wrap;" id="datatable_header">
                            <th >ID</th> 
                            <th style="width: 315px  !important;">კითხვები</th>
                            <th style="width: 65px !important;">#</th>   
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

?>