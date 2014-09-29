<?php
require_once('../../includes/classes/core.php');
header('Content-Type: text/html; charset=utf-8');
$info_id 		= $_REQUEST['id'];

	$res1 = mysql_fetch_assoc(mysql_query("	SELECT  `id`,
													`name`,
													`body`
											FROM    `info_base`
											WHERE   `id` = $info_id" ));


	$data ='

	  	 	<p>' . $res1['name'] . '</p>
			<textarea style="height:666px; width: 550px; resize: none; border:none;">' . $res1['body'] . '</textarea>';
	echo $data;
	
	?>