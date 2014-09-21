<html>
<head>
	<style type="text/css">
	.hidden{
		display : none;
	}
	</style>
	<script type="text/javascript">
		var aJaxURL	= "server-side/view/template.action.php";		//server side folder url
		var tName	= "example";											//table name
		var fName	= "add-edit-form";										//form name
		var img_name		= "0.jpg";

		$(document).ready(function () {
			LoadTable();

			/* Add Button ID, Delete Button ID */
			GetButtons("add_button", "delete_button");

			SetEvents("add_button", "delete_button", "check-all", tName, fName, aJaxURL);
		});

		function LoadTable(){
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable(tName, aJaxURL, "get_list", 2, "", 0, "");
		}

		function LoadDialog(){

			/* Dialog Form Selector Name, Buttons Array */
			GetDialog(fName, 750, "auto", "");

			var group_id = $("#group_id").val();
			
			GetDataTable1("pages", aJaxURL, "get_pages_list&group_id=" + group_id, 3, "", 0, "", "", "", "", "280px", "true");
			
			$("#pages tbody").on("dblclick", "tr", function () {
		        var nTds = $("td", this);
		        var empty = $(nTds[0]).attr("class");
				
		        if (empty != "dataTables_empty") {
		            var rID = $(nTds[0]).text();
		            $("#pages INPUT[name='check_"+rID+"']").prop('checked', true);
		            $.ajax({
		                url: aJaxURL,
		                type: "POST",
		                data: "act=get_notes&id=" + rID,
		                dataType: "json",
		                success: function (data) {
		                    if (typeof (data.error) != "undefined") {
		                        if (data.error != "") {
		                            alert(data.error);
		                        } else {
		                            $("#add-group-form").html(data.page);
		                            if ($.isFunction(window.LoadDialog)) {
		                                //execute it
		                            	var buttons = {
		                						"save": {
		                				            text: "შენახვა",
		                				            id: "save_notes"
		                				        }, 
		                			        	"cancel": {
		                				            text: "დახურვა",
		                				            id: "cancel-dialog",
		                				            click: function () {
		                				            	$(this).dialog("close");
		                				            }
		                				        } 
		                				    };
		                            	GetDialog("add-group-form", 450, "auto", buttons);
		                            }
		                        }
		                    }
		                }
		            });
		        }
		    });
		}
		

		
	    // Add - Save
		$(document).on("click", "#save_notes", function () {


     		param = new Object();
     	    //Action
     		param.act			= "save_notes";
 			param.minishneba	= $("#minishneba").val();
 			param.qvota 		= $("#qvota").val();
 			param.hidden_id 	= $("#hidden_id").val();
 			param.group_name 	= $("#group_name").val();
 			param.scenar_id 	= $("#scenar_id").val();
 			param.product_id	= $("#product_id").val();

 	    	    $.ajax({
 	    	        url: aJaxURL,
 	    		    data: param,
 	    	        success: function(data) {
 	    				if(typeof(data.error) != "undefined"){
 	    					if(data.error != ""){
 	    						alert(data.error);
 	    					}else{
 	    						$("#add-group-form").dialog("close");
 	    						
 	    					}
 	    				}
 	    		    }
 	    	    });
 			
		});

		$(document).on("click", "#save-dialog", function () {

		    var data = $(".check1:checked").map(function () { //Get Checked checkbox array
		        return this.value;
		    }).get();

			var pages = [];
			
			var val1;

 		    for (var i = 0; i < data.length; i++) {
 	 		    
 		    	val1 = $("#" + data[i]).val();
 		    	pages.push([ id = data[i], val= val1 ])
 		    	
 		    }

     		param = new Object();
     	    //Action
     		param.act	   = "save_group";
 			param.nam	   = $("#group_name").val();
 			param.pag	   = JSON.stringify(pages);
 			param.group_id = $("#group_id").val();
 			param.scenar_id = $("#scenar_id").val();

 			//var link	=  GetAjaxData(param);

 			if( param.nam == "" ){
 				alert("შეიყვანეთ ჯგუფის სახელი!");
 			}else{
 	    	    $.ajax({
 	    	        url: aJaxURL,
 	    		    data: param,
 	    	        success: function(data) {
 	    				if(typeof(data.error) != "undefined"){
 	    					if(data.error != ""){
 	    						alert(data.error);
 	    					}else{
 	    						$("#add-edit-form").dialog("close");
 	    						LoadTable();
 	    					}
 	    				}
 	    		    }
 	    	    });
 			}


		});
		

		$(document).on("change", "#scenar_id",function(){
			var scenar_id = $("#scenar_id").val();
			GetDataTable1("pages", aJaxURL, "get_pages_list&scenar_id=" + scenar_id, 2, "", 0, "", "", "", "", "280px", "true");
		});
	    
    </script>
</head>

<body>
    <div id="dt_example" class="ex_highlight_row">
        <div id="container">
            <div id="dynamic">
                <h2 align="center">სცენარები</h2>
	        	<div id="button_area">
	        		<button id="add_button">დამატება</button>
	        		<button id="delete_button">წაშლა</button>
	        	</div>
                <table class="display" id="example">
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 100%">შაბლონის სახელი</th>
                            <th class="check">#</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden">
                            	<input type="text" name="search_id" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_address" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                            	<input type="checkbox" name="check-all" id="check-all">
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="spacer">
            </div>
        </div>
    </div>

    <!-- jQuery Dialog -->
    <div id="add-edit-form" class="form-dialog" title="სცენარი">
    	<!-- aJax -->
	</div>
    <!-- jQuery Dialog -->
    <div id="image-form" class="form-dialog" title="პროდუქციის სურათი">
    	<img id="view_img" src="media/uploads/images/worker/0.jpg">
	</div>
	 <!-- jQuery Dialog -->
    <div id="add-group-form" class="form-dialog" title="მინიშნება / ქვოტა">
	</div>
</body>
</html>