<html>
<head>
	<style type="text/css">
	.hidden{
		display : none;
	}
	</style>
	<script type="text/javascript">
		var aJaxURL	= "server-side/view/template.action.php";		//server side folder url
		var seoyURL	= "server-side/seoy/seoy.action.php";							//server side folder url
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
		                            	GetButtons("add_product");
		                            	GetDialog("add-group-form", 700, "auto", buttons);
		                            	var filter 	= $("#hidden_id").val();
		                            	GetDataTable("example1", aJaxURL, "get_list_product&filter="+filter, 6, "", 0, "", 1, "desc");
		                            }
		                        }
		                    }
		                }
		            });
		        }
		    });
		}
		
		$(document).on("click", "#add_product", function () {
			
			
			var buttons = {
					"save": {
			            text: "შენახვა",
			            id: "save_product"
			        }, 
		        	"cancel": {
			            text: "დახურვა",
			            id: "cancel-dialog",
			            click: function () {
			            	$(this).dialog("close");
			            }
			        } 
			    };
		    
			GetDialog("add_product_dialog", 400, "auto", buttons);
			//alert("test");
			//SeoY("production_name", seoyURL, "production_name", "", 0);
			
			var minishneba	= $("#minishneba").val();
 			var qvota 		= $("#qvota").val();
 			var hidden_id 	= $("#hidden_id").val();
 			var group_name 	= $("#group_name").val();
 			var scenar_id 	= $("#scenar_id").val();
			$.ajax({
                url: aJaxURL,
                type: "POST",
                data: "act=get_product_dialog&hidden_id="+hidden_id+"&group_name="+group_name+"&scenar_id="+scenar_id+"&minishneba="+minishneba,
                dataType: "json",
                success: function (data) {
                    if (typeof (data.error) != "undefined") {
                        if (data.error != "") {
                            alert(data.error);
                        } else {
                            $("#add_product_dialog").html(data.page);
                            SeoY("production_name", seoyURL, "production_name", "", 0);
                            
                            $("#title").keypress(function(event) {
                    		    if (event.which === 13) {
                    		    	var title = $("#title").val();
                        		    	
	                   		    	$.ajax({
	              		                url: aJaxURL,
	              		                type: "POST",
	              		                data: "act=get_product_search&title="+title,
	              		                dataType: "json",
	              		                success: function (data) {
	              		                    if (typeof (data.error) != "undefined") {
	              		                        if (data.error != "") {
	              		                            alert(data.error);
	              		                        } else {
	              		                            $("#add_product_dialog").html(data.page);
	              		                        }
	              		                    }
	              		                }
	              		            });
                    		    }
                    		});
                            SeoY("production_name", seoyURL, "production_name", "", 0);
                        }
                    }
                }
            });
		});

		
		$(document).on("click", "#check-all", function () {
			
			if($("#example1 #check-all").is(':checked')){
				 $("#example1 .check").prop('checked', true);
			}else{
				$("#example1 .check").prop('checked', false);
			}
		});

		$(document).on("click", "#save_product", function () {
			var minishneba				= $("#minishneba").val();
 			var hidden_product_id 		= $("#hidden_product_id").val();
 			var hidden_id 				= $("#hidden_id").val();
 			var group_name 				= $("#group_name").val();
 			var scenar_id 				= $("#scenar_id").val();
 			if(hidden_product_id == ''){
 	 			alert('ჯერ ამოირჩიეთ პროდუქტი')
 			}else{
			$.ajax({
                url: aJaxURL,
                type: "POST",
                data: "act=save_product&hidden_id="+hidden_id+"&group_name="+group_name+"&scenar_id="+scenar_id+"&minishneba="+minishneba+"&hidden_product_id="+hidden_product_id,
                dataType: "json",
                success: function (data) {
                    if (typeof (data.error) != "undefined") {
                        if (data.error != "") {
                            alert(data.error);
                        } else {
                        	$("#add_product_dialog").dialog("close");
                        	var filter 	= $("#hidden_id").val();
                        	var group_name = $("#group_name").val();
                        	GetDataTable("example1", aJaxURL, "get_list_product&filter="+filter+"&group_name="+group_name, 6, "", 0, "", 1, "desc");
                        }
                    }
                }
            });
 			}
		});
		
	    // Add - Save
		$(document).on("click", "#save_notes", function () {
			var checker_id 	= $("#checker_id").val();
			if(checker_id == 0){
				$("#add-group-form").dialog("close");
			
	        }else{
	        	var data		= '';
	        	var qvota		= '';
	        	//Action
	     		var act			= "save_notes";
	 			var minishneba	= $("#minishneba").val();
	 			qvota 			= $("#qvota").val();
	 			var hidden_id 	= $("#hidden_id").val();
	 			var group_name 	= $("#group_name").val();
	 			var scenar_id 	= $("#scenar_id").val();

	 	    	    $.ajax({
	 	    	        url: aJaxURL,
	 	    		    data: "act="+act+"&minishneba="+minishneba+"&qvota="+qvota+"&hidden_id="+hidden_id+"&group_name="+group_name+"&scenar_id="+scenar_id,
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
	        }
 			
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

 			if( param.name == "" ){
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

		 $(document).on("click", ".combobox", function(event) {
	            var i = $(this).text();
	            $("#" + i).autocomplete("search", "");
	        });

		$(document).on("keydown", "#production_name", function(event) {
            if (event.keyCode == $.ui.keyCode.ENTER) {
            	GetProductionInfo(this.value);
                event.preventDefault();
            }
        });


		 function GetProductionInfo(name) {
	            $.ajax({
	                url: aJaxURL,
	                data: "act=get_product_info&name=" + name,
	                success: function(data) {
	                    if (typeof(data.error) != "undefined") {
	                        if (data.error != "") {
	                            alert(data.error);
	                        } else {
	                            $("#genre").val(data.genre);
	                            $("#category").val(data.category);
	                            $("#description").val(data.description);
	                            $("#price").val(data.price);
	                            $("#hidden_product_id").val(data.id);
	                        }
	                    }
	                }
	            });
	        }
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
	 <!-- jQuery Dialog -->
    <div id="add_product_dialog" class="form-dialog" title="პროდუქტის დამატება">
	</div>
</body>
</html>