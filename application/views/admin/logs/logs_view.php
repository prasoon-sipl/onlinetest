<?php
/*
* Version       : 1.0
* Filename      : logs_view.php
* Purpose       : This file is used to show logs list
*/

?>
<!-- mid content section start here -->

<script type="text/javascript">
	/* Load Jq grid */
	var op = ":All;1:Yes;0:No";
	     var arr = ['0','1'];
	     var sym;
	     
	     jQuery(document).ready(function(){
	     jQuery("#logs_list").jqGrid({
		 url: '<?php echo base_url(ADMIN.'logs/logsGrid/'); ?>',
		 datatype: "json",
		 colNames: ['Activity Id', 'Activity Type','Activity Description', 'User Id', 'Affected Table', 'Affected Table Id', 'IP Address','Latitude', 'Longitude', 'Browser', 'Created Date'],
		 colModel: [
	     
				{
				     name: 'activity_id', index: 'activity_id', align: "center", search:true, sortable:true
				},
			       
			    {
				     name: 'activity_type', index: 'activity_type', align: "left", search:true, sortable:true
				},
				{
				     name: 'activity_description', index: 'activity_description', align: "left", search:true, sortable:true
				},
				
				{name: 'fk_user_id', index: 'fk_user_id',  align: "center",search:true, sortable:true,
		  formatter: function (cellvalue) {
					  if(cellvalue!=0)
		    return '<a href ="'+basePath+'/users/info/'+cellvalue+'">'+cellvalue+'</a>';else return'';}
		},
				{
				     name: 'table_name', index: 'table_name', align: "left", search:true, sortable:true
				},
				{
				     name: 'table_id', index: 'table_id', align: "left", search:true, sortable:true
				},
				{
				     name: 'ipaddress', index: 'ipaddress', align: "center", search:true, sortable:true
				},
				{
				     name: 'lat', index: 'lat', align: "center", search:true, sortable:true
				},
				{
				     name: '	lng', index: 'lng', align: "center", search:true, sortable:true
				},
				{
				     name: 'browser', index: 'browser', align: "left", search:true, sortable:true
				},
				
				{   
				 name: 'created_date', index: 'created_date',  align: "center",formatter:'date', 
				 formatoptions: {
				 srcformat:'Y-m-D H:i:s', newformat:'<?php echo DATE_FORMAT; ?>'
				   }, searchoptions:{sopt: ['dt']} 
			       }
			     
			      
		 ],
		 rowNum: 10,
		 rowList: [10, 20,30,40],
		 viewrecords: true,
		 sortorder: 'desc',
		 height: '100%',
		 loadonce:false,
		 autoencode: false,
		 mtype: "POST",
		 rownumbers: true,
		 rownumWidth: 40,
		 gridview: true,
		 pager: '#pager',
		 sortname: 'created_date',
		 multiselect:false,
		 filterToolbar: true,
		 caption: "",
		 ignoreCase: true,
		 autowidth: true,
		 postData: { <?php echo $this->security->get_csrf_token_name(); ?>: function(){ return "<?php echo $this->security->get_csrf_hash(); ?>" }}
	     });
	     jQuery("#logs_list").jqGrid('navGrid','#pager',{del:false,add:false,edit:false,search:false},{multipleSearch:false});
	     jQuery("#logs_list").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
	 
	     /* Boot strap date picker */
	     $('#gs_created_date').datepicker({
		 format: '<?php echo DATE_FORMAT_BOOT_STRAP; ?>',
	     });
	     /* Filter data after choose date */
	     $('#gs_created_date').change(function () {
		$("#logs_list")[0].triggerToolbar();
	     });

	 });
    </script>

<div  id="page-content-wrapper"><!--span8 side-menu Start-->
  
  <div class="section-title">
    <h1>User Logs Information</h1>
  </div>
  
  <!-- flash message -->
  <?php if($this->session->flashdata('successMsg')) echo '<div class="alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'.$this->session->flashdata('successMsg').'</div>'; ?>
  <?php if($this->session->flashdata('errorMsg')) echo '<div class="alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'.$this->session->flashdata('errorMsg').'</div>'; ?>
  <!--// flash message -->
  
  <div class="dashboard_table12" id="gird_section"><!--dashboard_table Start-->
    <table id="logs_list">
    </table>
    <!--Grid table-->
    <div id="pager"></div>
    <!--pagination div--> 
  </div>
</div>
<!--dashboard_table End-->