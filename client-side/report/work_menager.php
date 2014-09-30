<html>
<head>

<script type="text/javascript">
var aJaxURL	= "server-side/report/work_menager.action.php";
var dey=1;
var tbName = "tabs";
$(document).ready(function(){
  GetTabs(tbName);
  GetButtons("dis");
  LoadTable();
  SetEvents("add", "dis", "", "example", "add-edit-form", aJaxURL);
  });
$(document).on("tabsactivate", "#tabs", function() {
	 LoadTable();
});
function LoadTable(){
	var type = GetSelectedTab(tbName);
  GetDataTable("example"+type,aJaxURL,"get_list"+type,8,'',0);
}

</script>

<style type="text/css">

.menun{
    cursor:pointer;
    padding: 6px 10px;
    width: 100px !important;
    display: inline-block;
    margin: -3px;
}</style>
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


      <a class="menun ui-state-default" id="2">ორშაბათი</a>
      <a class="menun ui-state-default" id="3">სამშაბათი</a>
      <a class="menun ui-state-default" id="4">ოთხშაბათი</a>
      <a class="menun ui-state-default" id="5">ხუთშაბათი</a>
      <a class="menun ui-state-default" id="6">პარასკევი</a>
      <a class="menun ui-state-default" id="7">შაბათი</a>
      <a class="menun ui-state-default" id="1">კვირა</a>

              <h2 align="center"style="">სამუშაო გრაფიკები</h2>
              <div id="button_area" style="">
              <button id="add">დამატება</button>
              <button id="dis">წაშლა</button>
              </div>
              <div id="get-info" style="float : left; margin-left: 30px; margin-top: 50px;"></div>
                <table class="display1" id="example1">
                    <thead>
                        <tr id="datatable_header">
							<td style="transform: rotate(270deg);">09:00</td>
							<td style=" transform: rotate(270deg);">09:15</td>
							<td style=" transform: rotate(270deg);">09:30</td>
							<td style=" transform: rotate(270deg);">09:45</td>
							<td style=" transform: rotate(270deg);">10:00</td>
							<td style=" transform: rotate(270deg);">10:15</td>
							<td style=" transform: rotate(270deg);">10:30</td>
							<td style=" transform: rotate(270deg);">10:45</td>
							<td style=" transform: rotate(270deg);">11:00</td>
							<td style=" transform: rotate(270deg);">11:15</td>
							<td style=" transform: rotate(270deg);">11:30</td>
							<td style=" transform: rotate(270deg);">11:45</td>
							<td style=" transform: rotate(270deg);">12:00</td>
							<td style=" transform: rotate(270deg);">12:15</td>
							<td style=" transform: rotate(270deg);">12:30</td>
							<td style=" transform: rotate(270deg);">12:45</td>
							<td style=" transform: rotate(270deg);">13:00</td>
							<td style=" transform: rotate(270deg);">13:15</td>
							<td style=" transform: rotate(270deg);">13:30</td>
							<td style=" transform: rotate(270deg);">13:45</td>
							<td style=" transform: rotate(270deg);">14:00</td>
							<td style=" transform: rotate(270deg);">14:15</td>
							<td style=" transform: rotate(270deg);">14:30</td>
							<td style=" transform: rotate(270deg);">14:45</td>
							<td style=" transform: rotate(270deg);">15:00</td>
							<td style=" transform: rotate(270deg);">15:15</td>
							<td style=" transform: rotate(270deg);">15:30</td>
							<td style=" transform: rotate(270deg);">15:45</td>
							<td style=" transform: rotate(270deg);">16:00</td>
							<td style=" transform: rotate(270deg);">16:15</td>
							<td style=" transform: rotate(270deg);">16:30</td>
							<td style=" transform: rotate(270deg);">16:45</td>
							<td style=" transform: rotate(270deg);">17:00</td>
							<td style=" transform: rotate(270deg);">17:15</td>
							<td style=" transform: rotate(270deg);">17:30</td>
							<td style=" transform: rotate(270deg);">17:45</td>
							<td style=" transform: rotate(270deg);">18:00</td>
							<td style=" transform: rotate(270deg);">18:15</td>
							<td style=" transform: rotate(270deg);">18:30</td>
							<td style=" transform: rotate(270deg);">18:45</td>
							<td style=" transform: rotate(270deg);">19:00</td>
							<td style=" transform: rotate(270deg);">19:15</td>
							<td style=" transform: rotate(270deg);">19:30</td>
							<td style=" transform: rotate(270deg);">19:45</td>
							<td style=" transform: rotate(270deg);">20:00</td>
                        </tr>
                    </thead>
                </table>

    </div>
  </div>
    <div  id="add-edit-form" class="form-dialog" title="ორშაბათი">
  </div>
  </div>
</div>
</body>
</html>
