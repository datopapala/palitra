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
	case 'get_product_dialog':
			$page		= GetProductDialog();
			$data		= array('page'	=> $page);
		
		break;
	case 'save_product':
			$page		= SaveProduct();
			$data		= array('page'	=> $page);
		
		break;
	case 'get_product_search':
		$title 	= $_REQUEST['title'];
		$page		= GetProductDialog(GetProductSearch($title));
		$data		= array('page'	=> $page);
		
		break;
		
	case 'get_edit_page':
	    $group_id		= $_REQUEST['id'];
		$page		    = GetGroupPage(GetPage($group_id));
        
        $data		= array('page'	=> $page);
        
	    break;
	case 'save_notes':
		$minishneba 	= $_REQUEST['minishneba'];
		$qvota			= $_REQUEST['qvota'];
		$hidden_id		= $_REQUEST['hidden_id'];
		$group_name		= $_REQUEST['group_name'];
		$scenar_id		= $_REQUEST['scenar_id'];
		$product_id		= $_REQUEST['product_id'];
		
		
		mysql_query("INSERT	INTO `tmp_shabloni`
							(`name`, `quest_id`,`notes`,`scenar_id`,`qvota`,`product_id`)
								VALUES
							('$group_name','$hidden_id','$minishneba','$scenar_id','$qvota','$product_id')");
		
	    break;
	case 'get_notes':
	    $group_id		= $_REQUEST['id'];
	    $page		    = GetNotes($group_id);
	    
	    $data		= array('page'	=> $page);
	    
	    break;
	case 'get_list_product':
		$count	= $_REQUEST['count'];
		$hidden	= $_REQUEST['hidden'];
		$rResult = mysql_query("SELECT 	`production`.`id`,
										`production`.`name`,
										`genre`.`name`,
										`production_category`.`name`,
										`production`.`decription`,
										`production`.`price`
								FROM 	`production`
								LEFT JOIN	`genre` ON 	`production`.`genre_id` = `genre`.`id`
								LEFT JOIN	`production_category` ON `production`.`production_category_id` = `production_category`.`id`
								JOIN tmp_shabloni ON production.id = tmp_shabloni.product_id
								WHERE 	`production`.`actived`=1");
		
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
				
			}
			$data['aaData'][] = $row;
		}
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
		
		$req = mysql_query("SELECT 	qvota,
									notes,
									quest_id,
									scenar_id,
									name,
									product_id
							FROM 	tmp_shabloni");
		$jora = array();
		$gela = array();
		while ($res_row = mysql_fetch_assoc($req)) {
			$gela = array('qvota'=>$res_row[qvota], 'notes'=>$res_row[notes], 'quest_id'=>$res_row[quest_id], 'scenar_id'=>$res_row[scenar_id], 'name'=>$res_row[name], 'product_id'=>$res_row[product_id] );
			$jora[] =$gela;
		}
		
		for ($i = 0; $i < count($jora); $i++) {
			
			$name_a = $jora[$i][name];
			$quest_id_a = $jora[$i][quest_id];
			$notes_a = $jora[$i][notes];
			$qvota_a = $jora[$i][qvota];
			$scenar_id_a = $jora[$i][scenar_id];
			$product_id_a = $jora[$i][product_id];
			 
			mysql_query("INSERT	INTO `shabloni`
						(`name`, `quest_id`, `notes`, `scenar_id`, `qvota`, `product_id`)
							VALUES
						('$name_a','$quest_id_a','$notes_a','$scenar_id_a','$qvota_a', '$product_id_a')");
		}

		mysql_query("DELETE FROM tmp_shabloni
					");
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

function SaveProduct(){
	$quest_id		= $_REQUEST['hidden_id'];
	$name			= $_REQUEST['group_name'];
	$scenar_id      = $_REQUEST['scenar_id'];
	$notes		= $_REQUEST['minishneba'];
	$product_id 	= $_REQUEST['hidden_product_id'];
	
	mysql_query("INSERT	INTO `tmp_shabloni`
							(`name`, `quest_id`,`notes`,`scenar_id`,`product_id`)
								VALUES
							('$name','$quest_id','$notes','$scenar_id','$product_id')");
}

function GetProductSearch($title){
	$res = mysql_fetch_assoc(mysql_query("SELECT 	`production`.`id`,
													`production`.`name` AS `product_name`,
													`genre`.`name` AS `genre_name`,
													`production_category`.`name` AS `category_name`,
													`production`.`decription`,
													`production`.`price`
											FROM 	`production`
											LEFT JOIN	`genre` ON 	`production`.`genre_id` = `genre`.`id`
											LEFT JOIN	`production_category` ON `production`.`production_category_id` = `production_category`.`id`
											WHERE 	`production`.`actived`=1 and `production`.`name` = '$title'"));
	
	return $res;
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
	$req = mysql_query("SELECT id,name FROM `pattern`");
	
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
	    	<legend>კითხვები</legend>									
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

function GetProduct(){
	$req = mysql_query("	SELECT id,`name`
							FROM `production`
							WHERE actived = 1
							");

	$data1 .= '<option value="0" selected="selected">----</option>';
	while( $res = mysql_fetch_assoc($req)){
			$data1 .= '<option value="' . $res['id'] . '">' . $res['name'] . '</option>';
	}

	return $data1;
}

function GetNotes($id){
	if(($id < 8) or ($id == 21)){
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
			<input type="text" id="hidden_id" class="idle" onblur="this.className=\'idle\'" style="display:none;" value="'.$id.'"/>
		    
	    </div>
    ';
	}
	if($id == 3 or $id == 4){
	$data = '
		<div id="dialog-form">
	 	    <fieldset>
				<legend>მინიშნება</legend>
				<table>
				<tr>
		    	<td><textarea  style="width: 640px; height:60px; resize: none;" id="minishneba" class="idle" name="content" cols="300" ></textarea></td>
				</tr>
				</table>
	        </fieldset>
			<fieldset>
				<legend style="">პროდუქტი</legend>
				<div id="button_area" >
        			<button id="add_product">დამატება</button>
        		</div>
				<table class="display" id="example1" >
                    <thead >
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 50%;">პროდუქტი</th>
                            <th style="width: 50%;">ჟანრი</th>
                            <th style="width: 50%;">კატეგორია</th>
                            <th style="width: 50%;">აღწერილობა</th>
                            <th style="width: 80px;">ფასი</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden"></th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                             <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                             <th>
                                <input type="text" name="search_category" value="ფილტრი" class="" />
                            </th>
                        
                        </tr>
                    </thead>
                </table>
	        </fieldset>
	    </div>
			<input type="text" id="hidden_id" class="idle" onblur="this.className=\'idle\'" style="display:none;" value="'.$id.'"/>
					<input type="text" id="checker_id" class="idle" onblur="this.className=\'idle\'" style="display:none;" value="0"/>
    ';
	}
	if($id>7 and $id != 21){
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
	}
	return $data;
}

function GetProductDialog($res = ''){		
	$quset_id		= $_REQUEST['hidden_id'];
	$data = '
			<div id="dialog-form">
		 	    <fieldset>
					<legend>პროდუქტი</legend>
					<table>
						<tr>
							<td style="width:120px;">დასახელება</td>
							<td><input type="text" style="margin-bottom: 10px;" id="title" class="idle" onblur="this.className=\'idle\'" value="'.$res[product_name].'"/></td>
				    	</tr>
						<tr>
							<td>ჟანრი</td>
							<td><input type="text" style="margin-bottom: 10px;" id="ganre" class="idle" disabled onblur="this.className=\'idle\'" value="'.$res[genre_name].'"/></td>
						</tr>
						<tr>
							<td>კატეგორია</td>
							<td><input type="text" style="margin-bottom: 10px;" id="category" class="idle" disabled onblur="this.className=\'idle\'" value="'.$res[category_name].'"/></td>
						</tr>
						<tr>
							<td>აღწერილობა</td>
							<td><input type="text" style="margin-bottom: 10px;" id="description" class="idle" disabled onblur="this.className=\'idle\'" value="'.$res[decription].'"/></td>
						</tr>
						<tr>
							<td>ფასი</td>
							<td><input type="text" style="margin-bottom: 10px;" id="pirce" class="idle" disabled onblur="this.className=\'idle\'" value="'.$res[price].'"/></td>
						</tr>
					</table>
		        </fieldset>
						<input type="text" id="hidden_product_id" class="idle" onblur="this.className=\'idle\'" style="display:none;" value="'.$res[id].'"/>
						<input type="text" id="hidden_id" class="idle" onblur="this.className=\'idle\'" style="display:none;" value="'.$quset_id.'"/>
		    </div>

    ';
	
	return $data;
}

?>