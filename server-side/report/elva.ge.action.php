<?php

require_once ('../../includes/classes/core.php');


$action 	= $_REQUEST['act'];
$error		= '';
$data		= '';

switch ($action) {
	case 'get_list' :
		$count = 		$_REQUEST['count'];
		$hidden = 		$_REQUEST['hidden'];
	  	$rResult = mysql_query("

	  			SELECT elva_sale.id,
								elva_sale.person_id,
								elva_sale.name_surname,
								elva_sale.mail,
								elva_sale.address,
								elva_sale.phone,
								shipping.`name` AS `period`,
								elva_sale.books,
								elva_sale.call_date,
								elva_sale.sum_price,
								elva_sale.callceenter_comment,
								persons.`name` as operator_id,
								elva_sale.oder_send_date,
								elva_sale.`status`,
								elva_sale.coordinator_id,
								elva_sale.coordinator_comment,
								elva_sale.elva_status
								FROM `elva_sale`
								left JOIN shipping ON elva_sale.period = shipping.id
								JOIN users ON elva_sale.operator_id = users.id
								JOIN persons ON users.person_id = persons.id

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
			}
			$data['aaData'][] = $row;
		}

		break;
	case 'get_edit_page' :
		$rResult = mysql_query(	"SELECT elva_sale.id,
								elva_sale.person_id,
								elva_sale.name_surname,
								elva_sale.mail,
								elva_sale.address,
								elva_sale.phone,
								shipping.`name` AS `period`,
								elva_sale.books,
								elva_sale.call_date,
								elva_sale.sum_price,
								elva_sale.callceenter_comment,
								persons.`name` as operator_id,
								elva_sale.oder_send_date,
								elva_sale.`status`,
								elva_sale.coordinator_id,
								elva_sale.coordinator_comment,
								elva_sale.elva_status
								FROM `elva_sale`
								left JOIN shipping ON elva_sale.period = shipping.id
								JOIN users ON elva_sale.operator_id = users.id
								JOIN persons ON users.person_id = persons.id
								WHERE elva_sale.id='$_REQUEST[id]'");
		$res = mysql_fetch_array( $rResult );
		$data['page'][0] = '';
		$data['page'][0] .= '<div id="dialog-form">
								<div style="float: left; width: 710px;" disabled>
								<fieldset >
							    	<legend>ძირითადი ინფორმაცია</legend>
									<fieldset >
							    	<table class="dialog-form-table">
										<tr>
											<td style="width: 280px;"><label for="">პირადი №</label></td>
											<td style="width: 280px;"><label for="">სახელი გვარი</label></td>
											<td style="width: 280px;"><label for="">მეილი</label></td>
										</tr>
										<tr>
											<td><input id="person_id" 	 class="idle" style="width: 200px;" disabled="disabled" type="text" value="'.$res[person_id]. 	'" /></td>
											<td><input id="name_surname" class="idle" style="width: 200px;" disabled="disabled" type="text" value="'.$res[name_surname].'" /></td>
											<td><input id="c_date" 		 class="idle" style="width: 200px;" disabled="disabled" type="text" value="'.$res[mail].       	'" /></td>
										</tr>
										<tr>
											<td style="width: 280px;"><label for="">ტელეფონი</label></td>
											<td style="" colspan="2"><label for="">მისმართი</label></td>
										</tr>
										<tr>
											<td><input id="phone" class="idle" style="width: 200px;" disabled="disabled" type="text" value="'.$res[phone].'" /></td>
											<td colspan="2"><input id="c_date" class="idle" style="width: 444px;" disabled="disabled" type="text" value="'.$res[address].'" /></td>
										</tr>
									</table>
								</fieldset >
									<fieldset style="margin-top: 5px;">
							    		<legend>დავალების ტიპი</legend>
											<table class="dialog-form-table" >
											<tr>
												<td style="width: 280px;"><label for="period">პერიოდი</label></td>
												<td colspan="2" style="width: 280px;"><label for="book">გამოცემა</label></td>
											</tr>
								    		<tr>
												<td><input style="width: 200px;" id="period" 				value="'.$res[period].'"	class="idls object" disabled></td>
												<td colspan="2"><input style="width: 444px;" id="book" 		value="'.$res[books]. '" 	class="idls object" disabled></td>
											</tr>
											<tr>
												<td style="width: 280px;"><label for="date">ქოლ-ცენტრის დარეკვის თარიღი</label></td>
												<td style="width: 280px;"><label for="op_id">ოპერატორი</label></td>
												<td style="width: 280px;"><label for="oder_date">ქვითრის გაგზავნის დღე</label></td>
											</tr>
								    		<tr>
												<td><input style="width: 200px;" id="date" 		value="'.$res[call_date].'" 		class="idls object" disabled></td>
												<td><input style="width: 200px;" id="op_id" 	value="'.$res[operator_id].'" 		class="idls object" disabled></td>
												<td><input style="width: 200px;" id="oder_date" value="'.$res[oder_send_date].'" 	class="idls object" disabled></td>
											</tr>
											</table>
											<table class="dialog-form-table" style="width: 720px;">
											<tr>
												<td style="width: 150px;"><label> გადასახდელი თანხა  </label></td>
												<td style="width: 150px;"><label>ქოლცენტრის კომენტარი</label></td>
											</tr>
											<tr>
												<td><input style="width: 200px;" id="oder_date" value="'.$res[sum_price].  '" class="idls object" disabled></td>
												<td><textarea  style="width: 270px; resize: none;" id="c_coment" class="idle" name="content" cols="300" disabled>'.$res[callceenter_comment].'</textarea></tr>

										</table>
									</fieldset>
								<fieldset style="margin-top: 5px;">
								    	<legend>ელვა.ჯი</legend>
								    	<table class="dialog-form-table" >
											<tr>
												<td style="width: 280px;"><label for="status">სტატუსი</label></td>
												<td style="width: 280px;"><label for="cooradinator">კოორდინატორი</label></td>
												<td style="width: 280px;"><label for="elva">ნინო (ელვა)</label></td>
											</tr>
								    		<tr>
												<td><input style="width: 200px;" id="status" 		value="'.$res[status].'" 			class="idls object"></td>
												<td><input style="width: 200px;" id="cooradinator"  value="'.$res[coordinator_id].'" 	class="idls object"></td>
												<td><input style="width: 200px;" id="elva"          value="'.$res[elva_status].'" 		class="idls object"></td>
											</tr>
											</table>
											<table class="dialog-form-table" style="width: 720px;">
											<tr>
												<td style="width: 150px;"><label>კოოდინატორის შენიშვნა</label></td>
											</tr>
											<tr>
												<td><textarea  style="width: 270px; resize: none;" id="k_coment" class="idle" name="content" cols="300">'.$res[coordinator_comment].'</textarea></tr>

										</table>
							        </fieldset>
							</fieldset>
							</div>
					<div style="float: right;  width: 355px;">
					<fieldset>
					<legend>გაყიდვა</legend>					
		                <table style="border:2px solid #85B1DE; width:100%;">
		                   		<tr style="background:#F2F2F2; ">	
									<th style="width:7%; padding:5px; border:1px solid #85B1DE;">#</th>
									<th style="border:1px solid #85B1DE; padding:5px;">თარიღი</th>
									<th style="border:1px solid #85B1DE; padding:5px;">მომხმარებელი</th>
									<th style="width:12%; padding:5px; border:1px solid #85B1DE;">ფასი</th>
									<th style="border:1px solid #85B1DE; padding:5px;">წიგნები</th>
								</tr>';
		
		$query_list = mysql_fetch_row(mysql_query("SELECT 	product_ids,
															gift_ids
												  FROM 		`task_scenar`
												  WHERE 	id = '$_REQUEST[id]'"));
		$query1 = mysql_query("SELECT 	`name`,`price`,`id`
							  FROM 		`production`
							  WHERE 	`id` in ($query_list[0])");
		
		while ($row_prod = mysql_fetch_row($query1)) {
			$number = $row_prod[2];
			$book_name = $row_prod[0];
			$book_price = $row_prod[1];
		
			$data['page'][0] .= '<tr style="background: #FEFEFE">
									<td style="border:1px solid #85B1DE; padding:2px;">'.$number.'</td>
									<td style="border:1px solid #85B1DE; padding:2px;">'.$res[call_date].'</td>
									<td style="border:1px solid #85B1DE; padding:2px;">'.$res[name_surname].'</td>
									<td style="border:1px solid #85B1DE; padding:2px;">'.$book_price.'</td>
									<td style="border:1px solid #85B1DE; padding:2px;">'.$book_name.'</td>
								</tr>';	
		}			
	 $data['page'][0] .= '</table>
					</fieldset>	
	 		
			 		<fieldset>
							<legend>საჩუქარი</legend>					
				                <table style="border:2px solid #85B1DE; width:100%;">
				                   		<tr style="background:#F2F2F2; ">	
											<th style="width:7%; padding:5px; border:1px solid #85B1DE;">#</th>
											<th style="border:1px solid #85B1DE; padding:5px;">თარიღი</th>
											<th style="border:1px solid #85B1DE; padding:5px;">მომხმარებელი</th>
											<th style="width:12%; padding:5px; border:1px solid #85B1DE;">ფასი</th>
											<th style="border:1px solid #85B1DE; padding:5px;">წიგნები</th>
										</tr>';
				
				$query_list = mysql_fetch_row(mysql_query("SELECT 	product_ids,
																	gift_ids
														  FROM 		`task_scenar`
														  WHERE 	id = '$_REQUEST[id]'"));
				$query1 = mysql_query("SELECT 	`name`,`price`,`id`
									  FROM 		`production`
									  WHERE 	`id` in ($query_list[1])");
				
				while ($row_prod = mysql_fetch_row($query1)) {
					$number = $row_prod[2];
					$book_name = $row_prod[0];
					$book_price = $row_prod[1];
				
					$data['page'][0] .= '<tr style="background: #FEFEFE">
											<td style="border:1px solid #85B1DE; padding:2px;">'.$number.'</td>
											<td style="border:1px solid #85B1DE; padding:2px;">'.$res[call_date].'</td>
											<td style="border:1px solid #85B1DE; padding:2px;">'.$res[name_surname].'</td>
											<td style="border:1px solid #85B1DE; padding:2px;">'.$book_price.'</td>
											<td style="border:1px solid #85B1DE; padding:2px;">'.$book_name.'</td>
										</tr>';	
				}			
			 $data['page'][0] .= '</table>
					</fieldset>	
					</div>
				    </div>
								<input type="hidden" id="id" value="'.$_REQUEST[id].'" />
			</div>
		</fieldset></div>';
			   	break;
   	case 'save_dialog' :
		mysql_query("UPDATE `elva_sale` SET `status`='$_REQUEST[status]', `coordinator_id`='$_REQUEST[cooradinator]', `coordinator_comment`='$_REQUEST[k_coment]', `elva_status`='$_REQUEST[elva]'
					WHERE (`id`='$_REQUEST[id]')");
   		break;
	default:
		$error = 'Action is Null';
}


$data['error'] = $error;

echo json_encode($data);

?>