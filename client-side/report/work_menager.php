<html>
<head>

<script type="text/javascript">
var aJaxURL	= "server-side/report/work_menager.action.php";
var dey=1;
var tbName = "tabs";
$(document).ready(function(){
	GetDate("time");
  	GetTabs(tbName);
  	GetButtons("dis");
  	LoadTable();
  	SetEvents("add", "dis", "", "example", "add-edit-form", aJaxURL);
  	$(document).on("change", "#time", function () 	{

  		LoadTable()
  	  	});
  });
$(document).on("tabsactivate", "#tabs", function() {
	 LoadTable();
});
function LoadTable(){
	var type = GetSelectedTab(tbName);
  GetDataTable("example"+type,aJaxURL,"get_list"+type,8,'',0);
  var param= new Object()
	param.act 			= "get_list1";
  	param.time 			= $("#time").val()
  $.getJSON(aJaxURL, param, function(json) {

		$("#time_line").html(json.aaData);
});
}

</script>

<style type="text/css">

.menun{
    cursor:pointer;
    padding: 6px 10px;
    width: 100px !important;
    display: inline-block;
    margin: -3px;
}
#time_line td{
   border:solid 1px #088888;
}


</style>
</head>
<body>
<div id="tabs">

  <ul>
      <li><a href="#tab-1">დასადასტურებელი</a></li>
      <li><a href="#tab-2">დაუდასტურებელი</a></li>
  </ul>
  <div id="tab-1">
    <div id="dt_example" class="ex_highlight_row">
            <div id="container" style="width:90%">

              <div id="get-info" style="float : left; margin-left: 30px; margin-top: 50px;"></div>
              <button id="dis">დამატება</button>
                <table class="display0" id="example0">
                    <thead>
                        <tr id="datatable_header">
                            <th>ID</th>
                            <th style="20%">პიროვნება</th>
                            <th >კვირის დღე</th>
                            <th >მუშაობის  დასაწყისი</th>
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
  </div>
  <div id="tab-2">
    <div id="dt_example" class="ex_highlight_row">
          <div id="container" style="width:90%">
		 <h2 align="center"style="">სამუშაო გრაფიკები</h2>
		 <br>
		 <input style="height: 20px;" id="time" class="inpt" />
		 <br>
			<div id="time_line"></div>
   		</div>
  </div>
    <div  id="add-edit-form" class="form-dialog" title="ორშაბათი">
  </div>
  </div>
</div>
</body>
</html>
