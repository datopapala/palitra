<?php 
require_once ('../../../includes/classes/core.php');
$action = $_REQUEST['act'];


switch ($action){
	case 'get_table':
		$page		= GetProductDialog($res='');
		$data		= array('page'	=> $page);
	
		break;
	case 'add_product':
		$notes = $_REQUEST['notes'];
		$shabloni = $_REQUEST['shabloni'];
		$product_id = $_REQUEST['product_id'];
		mysql_query("
						INSERT INTO `shabloni` 
						(`scenar_id`, `quest_id`, `name`, `notes`, `qvota`, `product_id`)
						VALUES
						('1', '4', '$shabloni', '$notes', '', '$product_id');
					");
		
		break;
	case 'get_product_info':
		$name 			= $_REQUEST['name'];
		$res 			= GetProductInfo($name);
		if(!$res){
			$error = 'პროდუქტი ვერ მოიძებნა!';
		}else{
			$data = array(  'genre'	         		=> $res['genre'],
					'category'	     		=> $res['category'],
					'description'	 		=> $res['description'],
					'price'	        		=> $res['price'],
					'id'	    			=> $res['id']);
		}
	
		break;
case 'get_list':
	$count			= $_REQUEST['count'];
	$hidden			= $_REQUEST['hidden'];
	$scenar_name	= $_REQUEST['scenar_name'];
	
	$rResult = mysql_query("
								SELECT 	`production`.`id`,
										`production`.`name`,
										`production`.`price`,
										`production`.`description`,
										`production`.`comment`
								FROM 	`production`
								JOIN 	`shabloni` ON `production`.`id` = `shabloni`.`product_id`
								WHERE 	`production`.`actived`=1 AND shabloni.quest_id = '3' AND shabloni.`name` = '$scenar_name' 
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
				$row[] ='<input type="checkbox" id="' . $aRow[$hidden] . '" name="check_' . $aRow[$hidden] . '" class="check" value="' . $aRow[$hidden] . '" />';
			}
		}
		$data['aaData'][] = $row;
	}

	break;
    case '':
    default:
       $error = 'Action is Null';
}

echo json_encode($data);

///////////////////////////////////////

function GetProductInfo($name)
{
	$res = mysql_query("SELECT  genre.`name` AS `genre`,
			production_category.`name` AS `category`,
			production.description,
			production.price,
			production.id
			FROM    production
			JOIN 	genre ON production.genre_id = genre.id
			JOIN 	production_category ON production.production_category_id = production_category.id
			WHERE   production.`name` = '$name' AND production.actived = 1
			");

	if (mysql_num_rows($res) == 0){
	return false;
	}

	$row = mysql_fetch_assoc($res);
	return $row;
}

function GetProductDialog($res = ''){
	$notes		= $_REQUEST['notes'];
	$data = '
			<div id="dialog-form">
		 	    <fieldset>
					<legend>პროდუქტი</legend>
					<table>
						<tr>
							  <td style="width:120px;">დასახელება</td>
                              <td>
            						<div class="seoy-row" id="goods_name_seoy">
            							<input type="text" id="production_name_gift" class="idle seoy-address" onblur="this.className=\'idle seoy-address\'" onfocus="this.className=\'activeField seoy-address\'" value="' . $res[product_name] . '" />
            							<button id="goods_name_btn" class="combobox">production_name_gift</button>
            						</div>
    				          </td>
				    	</tr>
						<tr>
							<td style="padding-top: 11px;">ჟანრი</td>
							<td style="padding-top: 11px;"><input type="text" style="margin-bottom: 10px;" id="genre_gift" class="idle" disabled onblur="this.className=\'idle\'" value="'.$res[genre_name].'"/></td>
						</tr>
						<tr>
							<td>კატეგორია</td>
							<td><input type="text" style="margin-bottom: 10px;" id="category_gift" class="idle" disabled onblur="this.className=\'idle\'" value="'.$res[category_name].'"/></td>
						</tr>
						<tr>
							<td>აღწერილობა</td>
							<td><input type="text" style="margin-bottom: 10px;" id="description_gift" class="idle" disabled onblur="this.className=\'idle\'" value="'.$res[decription].'"/></td>
						</tr>
						<tr>
							<td>ფასი</td>
							<td><input type="text" style="margin-bottom: 10px;" id="price_gift" class="idle" disabled onblur="this.className=\'idle\'" value="'.$res[price].'"/></td>
						</tr>
					</table>
		        </fieldset>
						<input type="text" id="hidden_notes" class="idle" onblur="this.className=\'idle\'" style="display:none;" value="'.$notes.'"/>
						<input type="text" id="hidden_product_id_gift" class="idle" onblur="this.className=\'idle\'" style="display:none;" value=""/>
		    </div> ';

	return $data;
}

?>