<?php 
$user_id = $_SESSION['USERID'];
?>
<html>
<head>
		<link href="media/css/main/header.css" rel="stylesheet" type="text/css" />
    	<link href="media/css/main/mainpage.css" rel="stylesheet" type="text/css" />
    	<link href="media/css/main/tooltip.css" rel="stylesheet" type="text/css" />
</head>
<script type="text/javascript" src="worker/js/jquery.keyboard.extension-autocomplete.js"></script>
<script type="text/javascript" src="worker/js/jquery.keyboard.extension-typing.js"></script>
<script type="text/javascript" src="worker/js/jquery.keyboard.js"></script>
<script type="text/javascript" src="worker/js/jquery.keyboard.min.js"></script>
<script>
var aJaxURL	  = "server-side/info/tabel.action.php";		    //server side folder url
var l_aJaxURL = "server-side/info/tabel/worker_job_time.php"; //list
var tName	  = "report";											//table name
var fName	  = "add-edit-form";								    //form name
var img_name  = "0.jpg";

$(document).ready(function () {       
	
});

function LoadTable(start, end, person_id, password){
	var total = [9,10];
	/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
	GetDataTable3(tName, l_aJaxURL, "get_list", 11, "start=" + start + "&end=" + end + "&person_id=" + person_id + "&password=" + password, 0, "", 1, "desc", total);
}

$(document).on("click", "#save-dialog", function () {

	param = new Object();

	param.act    = "save_act";
	param.user   = $("#user").val();
	param.pwd    = $("#password").val();
	param.action = $("#action").val();
	
    $.ajax({
        url: aJaxURL,
	    data: param,
	    dataType: "json",
        success: function(data) {
            if (data.error != "") {
                alert(data.error);
            }else {
            	$("#come-in-form").dialog("close"); 
            	alert("ოპერაცია წარმატებით დასრულდა!"); 
            }
	    }
    });	
});


$(document).on("click", "#come_in", function () {
	
    $.ajax({
        url: aJaxURL,
	    data: "act=get_come_in_page&action=" + 1,
	    dataType: "json",
        success: function(data) {
            if (data.error != "") {
                alert(data.error);
            }else {
                $("#come-in-form").html(data.page);
                GetDialog("come-in-form", 450, 210);
          		$("#action").val(1);
            }
	    }
    });	
});

$(document).on("click", "#go_home", function () {
	
    $.ajax({
        url: aJaxURL,
	    data: "act=get_come_in_page&action=" + 2,
	    dataType: "json",
        success: function(data) {
            if (data.error != "") {
                alert(data.error);
            }else {
                $("#come-in-form").html(data.page);
                GetDialog("come-in-form", 450, 210);
          		$("#action").val(2);
            }
	    }
    });	
});


$(document).on("click", "#relax", function () {
	
    $.ajax({
        url: aJaxURL,
	    data: "act=get_come_in_page&action=" + 3,
	    dataType: "json",
        success: function(data) {
            if (data.error != "") {
                alert(data.error);
            }else {
                $("#come-in-form").html(data.page);
                GetDialog("come-in-form", 450, 210);
          		$("#action").val(3);
            }
	    }
    });	
});


$(document).on("click", "#back_relax", function () {
	
    $.ajax({
        url: aJaxURL,
	    data: "act=get_come_in_page&action=" + 4,
	    dataType: "json",
        success: function(data) {
            if (data.error != "") {
                alert(data.error);
            }else {
                $("#come-in-form").html(data.page);
                GetDialog("come-in-form", 450, 210);
          		$("#action").val(4);
            }
	    }
    });	
});


$(document).on("click", "#balance", function () {

    $.ajax({
        url: aJaxURL,
	    data: "act=get_balance",
	    dataType: "json",
        success: function(data) {
            if (data.error != "") {
                alert(data.error);
            }else {
                
                $("#balance-form").html(data.page);  
    			$("#check").button({
    	            icons: {
    	                primary: "ui-icon-circle-check"
    	            }
            	});

    			GetDateTimes("search_start");
    			GetDateTimes("search_end");
    			
     			$("#search_start").val(GetDateTime(2) + " 00:00");
     			$("#search_end").val(GetDateTime(2) + " 23:59");
    			
    			var start	= $("#search_start").val();
    			var end		= $("#search_end").val();
    			LoadTable(start, end, 0, 0);

    			
            	
                $(document).on("click", "#check", function () {
                	var user   = $("#user").val();
                	var pwd    = $("#password").val();

                    $.ajax({
                        url: aJaxURL,
                	    data: "act=check_password&user=" + user + "&pwd=" + pwd,
                	    dataType: "json",
                        success: function(data) {
                            if (data.error != "") {
                                alert(data.error);
                            }else {
                            	LoadTable(start, end, user, pwd);

                        	    $(document).on("change", "#search_start", function () {
                        	    	var start	= $(this).val();
                        	    	var end		= $("#search_end").val();
                        	    	LoadTable(start, end, user, pwd);
                        	    });
                        	    
                        	    $(document).on("change", "#search_end", function () {
                        	    	var start	= $("#search_start").val();
                        	    	var end		= $(this).val();
                        	    	LoadTable(start, end, user, pwd);
                        	    });
                            }
                	    }
                    });         	
                });
        	    
        		var dialogButton = {
        		        "cancel": {
        		            text: "დახურვა",
        		            id: "cancel-dialog",
        		            click: function () {
        		            	$(this).dialog("close");
        		            }
        		        }
        		};
                GetDialog("balance-form", 1320, 550, dialogButton);
            }
	    }
    });
	 
});




</script>
<body onselectstart='return false;'>
    <div id="ContentHolder">  
    <div class="content"> 
        <table class="tiles">
            <tbody>
                <tr>
                    <td style="padding: 30px 100px 50px 90px;">
                        <div  class="tile_large" id="come_in"  style="background: #A0C64B;" >
										<div class="tile_icon" style="margin-top: 10px;">
											<img src="media/images/w_come_in.png" alt="" style="background-position: -116px -18px; width: 50px; height: 50px;" />
										</div><p style="margin-top: 22px; margin-left: 80px">მოსვლა</p>
						</div>
                    </td>
                    
                    <td style="padding: 30px 100px 50px 50px;">
                        <div  class="tile_large" id="go_home" style="background: #A0C64B;" >
										<div class="tile_icon" style="margin-top: 10px;">
											<img src="media/images/w_go_home.png" alt="" style="background-position: -116px -18px; width: 50px; height: 50px;" />
										</div><p style="margin-top: 22px; margin-left: 80px">გასვლა</p>
						</div>
                    </td>
                    
                </tr>
                  <tr>
                    <td style="padding: 30px 100px 50px 90px;">
                        <div  class="tile_large" id="relax" style="background: #A0C64B;" >
										<div class="tile_icon" style="margin-top: 10px;">
											<img src="media/images/w_relax.png" alt="" style="background-position: -116px -18px; width: 50px; height: 50px;" />
										</div><p style="margin-top: 22px; margin-left: 80px">შესვენებაზე გასვლა</p>
						</div>
                    </td>
                    <td style="padding: 30px 100px 50px 50px;">
                        <div  class="tile_large" id="back_relax" style="background: #A0C64B;" >
										<div class="tile_icon" style="margin-top: 10px;">
											<img src="media/images/w_back.png" alt="" style="background-position: -116px -18px; width: 50px; height: 50px;" />
										</div><p style="margin-top: 22px; margin-left: 80px"> შესვენებიდან მოსვლა</p>
						</div>
                    </td>
                    
                </tr>
                <tr>
                   <td style="padding: 30px 100px 50px 280px;" colspan="2">
                        <div  class="tile_large" id="balance" style="background: #A0C64B;" >
										<div class="tile_icon" style="margin-top: 10px;">
											<img src="media/images/w_balance.png" alt="" style="background-position: -116px -18px; width: 50px; height: 50px;" />
										</div><p style="margin-top: 22px; margin-left: 80px">ბალანსი</p>
						</div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
        </div>
        
        
    <div id="come-in-form" class="form-dialog" title="ინფორმაცია">
    	<!-- aJax -->
	</div>
	
	<div id="balance-form" class="form-dialog" title="ინფორმაცია">
    	<!-- aJax -->
	</div>	
	
</body>
</html>
