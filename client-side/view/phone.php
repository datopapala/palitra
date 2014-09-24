<html>
<head>
	<script type="text/javascript">
		var aJaxURL	= "server-side/view/phone.action.php";		//server side folder url
		var tName	= "example";													//table name
		var fName	= "add-edit-form";												//form name
		    	
		$(document).ready(function () {        	
			LoadTable();	
						
			/* Add Button ID, Delete Button ID */
			SetEvents("", "", "", tName, fName, aJaxURL);
		});
        
		function LoadTable(){
			
			/* Table ID, aJaxURL, Action, Colum Number, Custom Request, Hidden Colum, Menu Array */
			GetDataTable(tName, aJaxURL, "get_list", 3, "", 0, "", 1, "desc");
    		
		}
		
		function LoadDialog(){
			var id		= $("#status_id").val();
			
			/* Dialog Form Selector Name, Buttons Array */
			GetDialog(fName, 600, "auto", "");
		}
		
	    // Add - Save
	    $(document).on("click", "#save-dialog", function () {
		    param 			= new Object();

		    param.act			="save_status";
	    	param.id			= $("#status_id").val();
	    	param.name			= $("#name").val();
	    	param.call_status	= $("#call_status").val();
	    	
			if(param.name == ""){
				alert("შეავსეთ ველი!");
			}else {
			    $.ajax({
			        url: aJaxURL,
				    data: param,
			        success: function(data) {			        
						if(typeof(data.error) != 'undefined'){
							if(data.error != ''){
								alert(data.error);
							}else{
								LoadTable();
				        		CloseDialog(fName);
							}
						}
				    }
			    });
			}
		});

	    $(document).on("click", "#choose_button", function () {
		    $("#choose_file").click();
		});

	    $(document).on("change", "#choose_file", function () {
	    	var file		= $(this).val();
		    var name		= uniqid();
		    var path		= "../../media/uploads/images/client/";

		    var ext = file.split('.').pop().toLowerCase();
	        if($.inArray(ext, ['xls']) == -1) { //echeck file type
	        	alert('This is not an allowed file type.');
                this.value = '';
	        }else{
	        	img_name = name + "." + ext;
	        //	$("#choose_button").button("disable");
	        	$.ajaxFileUpload({
	    			url: 'server-side/view/import/import.php',
	    			secureuri: false,
	    			fileElementId: "choose_file",
	    			dataType: 'json',
	    			data:{
	    				task_id:$("#id").val(),
						act: "upload_file",
						path: path,
						file_name: name,
						type: ext
					},
	    			success: function(data){alert("ghj");
	    				if(typeof(data.error) != 'undefined'){
    						if(data.error != ''){
    							alert(data.error);
    						}else{
    							alert("ghj");
    						}
    					}
    				},

    			});

	        }
		});
	   
    </script>
</head>

<body>
    <div id="dt_example" class="ex_highlight_row" style="width: 1024px; margin: 0 auto;">
        <div id="container">        	
            <div id="dynamic">
            	<h2 align="center">სტატუსები</h2>
            	<div id="button_area">
	        		<div class="file-uploader">
    					 <input id="choose_file" type="file" name="choose_file" class="input" style="display: none;">
    					 <button style="margin-right: 0px !important;" id="choose_button" class="center">აირჩიეთ ფაილი</button>
    				</div>
	        	</div>
            	<table class="display" id="example">
                    <thead >
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 50%;">სახელი</th>
                             <th style="width: 50%;">ზარის სტატუსი</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden">
                            <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" />
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</body>
</html>







