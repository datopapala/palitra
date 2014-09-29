<html>
<head>
<style type="text/css">
table.display tbody td
{
	word-break: break-all !important;
	word-wrap: break-word !important;
	text-overflow: ellipsis !important;
	overflow: hidden !important;
	-ms-word-break: break-all;
     word-break: break-all;
     word-break: break-word;
	-webkit-hyphens: auto;
   -moz-hyphens: auto;
        hyphens: auto;
	white-space: normal !important;
	vertical-align: middle !important;
}
</style>
	<script type="text/javascript">
		var aJaxURL	= "server-side/info/about.action.php";		//server side folder url
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
			var buttons = {
			        "print": {
			            text: "ბეჭდვა",
			            id: "print-letterB",
			            click: function () {
				            
			            	var link = GetRootDIR();
		        	    	link = link + "server-side/info/about.action1.php?id="+ $("#status_id").val();
		        	    	
		        	    	var newWin = window.open(link, "JSSite", "width=800,height=800,resizable=yes,scrollbars=yes,status=yes");
		        	    	newWin.focus();
		        	    	newWin.onload = function() {
		        	    		newWin.print();
		        	    	}				            
			            }
			        }
			};
			GetDialog(fName, 610, "auto", buttons);
			
		
	}
		
</script>
</head>

<body>
    <div id="dt_example" class="ex_highlight_row" style="width: 1024px; margin: 0 auto;">
        <div id="container">        	
            <div id="dynamic">
            	<h2 align="center">ინფორმაცია</h2>
            	<table class="display" id="example">
                    <thead >
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 40%;">სათაური</th>
                            <th style="width: 100%;">აღწერა</th>
                        	
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
    <!-- jQuery Dialog -->
    <div id="add-edit-form" class="form-dialog" title="ოჯახური სტატუსი">
    	<!-- aJax -->
	</div>
</body>
</html>