<html>
<head>

<script type="text/javascript">
var aJaxURL	= "server-side/report/work_graphic.action.php";
var dey=1;
$(document).ready(function(){

	GetButtons("add", "dis");
	LoadTable();


	$(".menun").click(function(){
		dey=this.id;
		LoadTable();
		title=$(this).html();
		$("#add-edit-form").attr('title', title);
		$("#wek_h").html("'"+title+"'");


});
	SetEvents("add", "dis", "check-all", "example", "add-edit-form", aJaxURL);
	});
function LoadDialog(f){
	GetDialog(f,500);
	$('.time').timepicker({
		hourMax: 20,
		hourMin: 10,
		stepMinute: 15,
		timeFormat: 'HH:mm',
		minuteGrid: 15,
		hourGrid: 2

	});
	$("#save-dialog").click(function(){
		var param= new Object();
			param.act 			= "save_dialog";
			param.id 			= $("#id").val();
			param.week_day_id   = dey;
			param.start 		= $("#start").val();
			param.breack_start 	= $("#breack_start").val();
			param.breack_end 	= $("#breack_end").val();
			param.end			= $("#end").val();
			if(param.start < param.breack_start & param.breack_start<param.breack_end & param.breack_end<param.end){
			$.getJSON(aJaxURL, param, function(json) {
				LoadTable();
				$("#add-edit-form").dialog("close");
		});} else alert('მიუთითეთ კორექტული დრო');


	});
	$("#start").focus();
};
function LoadTable(){
	GetDataTable("example",aJaxURL+'?dey='+dey,"get_list",6,'',0)
}

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
			<table>
			<tr><th style="padding: 10px; margin: 10px">
			<a class="menun ui-state-default" id="2">ორშაბათი</a><br/>
			<a class="menun ui-state-default" id="3">სამშაბათი</a><br/>
			<a class="menun ui-state-default" id="4">ოთხშაბათი</a><br/>
			<a class="menun ui-state-default" id="5">ხუთშაბათი</a><br/>
			<a class="menun ui-state-default" id="6">პარასკევი</a><br/>
			<a class="menun ui-state-default" id="7">შაბათი</a><br/>
			<a class="menun ui-state-default" id="1">კვირა</a><br/>
			</th>
			<th style="; padding: 10px; margin: 10px">

            	<h2 align="center"style="">სამუშაო გრაფიკები</h2>
            	<h2 align="center" id="wek_h">'ორშაბათი'</h2>
            	<div id="button_area" style="">
            	<button id="add">დამატება</button> 	<button id="dis">წაშლა</button>
            	</div>
            	<div id="get-info" style="float : left; margin-left: 30px; margin-top: 50px;"></div>
                <table class="display" id="example">
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="width: 25%">მუშაობის დასაწყისი</th>
                            <th style="width: 25%">შესვენების დასაწყისი</th>
                            <th style="width: 25%">შესვენების დასასრული</th>
                            <th style="width: 25%">სამუშაოს დასასრული</th>
                            <th style="width: 30px">#</th>
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
                                <input type="checkbox" name="check-all" id="check-all">
                            </th>

                    </thead>
                </table>
			</th></tr>
			</table>
		</div>
  </div>
    <div  id="add-edit-form" class="form-dialog" title="ორშაბათი">
	</div>
</body>
</html>
