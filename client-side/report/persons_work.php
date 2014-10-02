<html>
<head>
<script src="js/jquery.weekpicker.js" type="text/javascript"></script>
 <link href="css/week-picker-view.css" rel="stylesheet"/>
<script type="text/javascript">
var aJaxURL	= "server-side/report/persons_work.action.php";
var dey=1;
$(document).ready(function(){
	GetButtons("add", "dis");
	LoadTable();
	SetEvents("add", "dis", "", "example", "add-edit-form", aJaxURL);
  	$(document).on("change", "#date", function () 	{
  		$("#example").show();
  		LoadTable();
  	  	});
$("#example").hide();
	});
function LoadDialog(f){
	GetDialog(f,800);
	GetDataTable("inline",aJaxURL+'?id='+$("#id").val()+'&date='+($('#date').val()).split("-")[0],"get_list1",6,'',0)
	$('.time').timepicker({
		stepMinute: 15,
		timeFormat: 'HH:mm',
		minuteGrid: 15

	});
	$("#save-dialog").click(function(){
		if($('input[name=check]:checked').val()==undefined){ alert("აირჩიეთ გრაფიკი"); return 0;}
		var param= new Object()
			param.act 			= "save_dialog";
			param.work 			= $('input[name=check]:checked').val();
			param.date 				= ($('#date').val()).split("-")[0];
			param.dey           = $("#id").val();
			$.getJSON(aJaxURL, param, function(json) {
				LoadTable();
				$("#add-edit-form").dialog("close");
		});
	});
};

function LoadTable(){
	GetDataTable("example",aJaxURL+'?date='+($('#date').val()).split("-")[0],"get_list",7,'',0)

}


</script>
<script>

</script>

<style type="text/css">


.menun{
		cursor:pointer;
		padding: 6px 10px;
		width: 100px !important;
		display: block;
		margin: -3px;
}</style>
</head>
<body>

	<div id="dt_example" class="ex_highlight_row">
       	 <div id="container" style="width:90%">
            	<h2 align="center"style="">ჩემი  გრაფიკები</h2>
            	<div id="get-info" style="float : left; margin-left: 30px; margin-top: 50px;"></div>
				<input id='date' data-weekpicker="weekpicker" placeholder="აირჩიეთ დრო" data-months="1"/>

                <table class="display" id="example" >
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th >კვირის დღე</th>
                            <th >მუშაობის დასაწყისი</th>
                            <th >შესვენების დასაწყისი</th>
                            <th >შესვენების დასასრული</th>
                            <th >სამუშაოს დასასრული</th>
                            <th style="width: 40px">#</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="search_header">
                            <th class="colum_hidden">
                            	<input type="text" name="search_id" value="ფილტრი" class="search_init" style=""/>
                            </th>

                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                             <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                            <th>
                                <input type="text" name="search_date" value="ფილტრი" class="search_init" style="width: 100px;"/>
                            </th>
                            <th>
                                <input type="text" name="search_category" value="ფილტრი" class="search_init" style="width: 80px;" />
                            </th>

                    </thead>
                </table>
		</div>
  </div>
    <div  id="add-edit-form" class="form-dialog" title="თავისუფალი გრაფიკები">
	</div>
</body>
</html>
