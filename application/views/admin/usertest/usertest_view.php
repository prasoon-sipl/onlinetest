<?php
/*
* Version       : 1.0
* Filename      : usertest_view.php
* Purpose       : This file is used to show user test
*/
?>
<script type="text/javascript">
$(document).ready(function(){
	$(".click_title").click(function(){
		$(this).toggleClass("plus-img");
		$(".add_drems_section").slideToggle()
	});
});
</script>

<div  id="page-content-wrapper"><!--#page-content-wrapper Start-->
  
  <?php if($this->session->flashdata('successMsg')) echo '<div class="alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'.$this->session->flashdata('successMsg').'</div>'; ?>
  <?php if($this->session->flashdata('errorMsg')) echo '<div class="alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'.$this->session->flashdata('errorMsg').'</div>'; ?>
  <div class="section-title">
    <h1>User's Test Report</h1>
  </div>
  <div class="dashboard_table12" id="gird_section">
    <table id="usertest_list">
    </table>
    <div id="pager"></div>
  </div>
</div><!--#page-content-wrapper End-->

<script type="text/javascript">

   	
/* Load Jq grid */		
jQuery(document).ready(function(){
		var userId='';
		var op = ":All;1:Yes;0:No";
		var arr = ['0','1'];
		var sym;
                var ds = ":All;1:Flat;0:Percent";
		var dsarr = ['0','1'];
		var dsym;
                var symb = '';
                var  question_category_id = '';
		
        grid = $("#usertest_list"),
        grid.jqGrid({
				
				url: basePath+'/usertest/usertestGrid',
				
				datatype: "json",
				
				colNames: ['Test id','User id','Test Id','Test Title','No of Question','Test Duration','Max Marks','Questions Attempted','Time Spent','Marks Obtained','Test Status', 'Is Active', 'Created Date','Action'],
				
				colModel: [
						{
							name:'user_test_id',index:'user_test_id',sortable:true,search:true
						},
						{
							name:'fk_user_id',index:'fk_user_id',sortable:true,search:true 
						},
						{       name: 'fk_question_category_id', index: 'fk_question_category_id', align: "center", search:true, sortable:true ,formatter: function (cellvalue) { question_category_id = cellvalue;return cellvalue;}
						},
                                                {       name: 'test_title', index: 'test_title', align: "center", search:true, sortable:true ,formatter: function (cellvalue) { return '<a target="_blank" href="'+basePath+'/configuration/viewQuesCategory/'+question_category_id+'" >'+cellvalue+'</a>'; }
						},
                                                {       name: 'no_of_question', index: 'no_of_question', align: "center", search:true, sortable:true
						},
                                                {       name: 'test_duration', index: 'test_duration', align: "center", search:true, sortable:true
						},
                                                {       name: 'max_marks', index: 'max_marks', align: "center", search:true, sortable:true
						},
                                                {       name: 'questions_attempted', index: 'questions_attempted', align: "center", search:true, sortable:true
						},
                                                {       name: 'time_spent', index: 'time_spent', align: "center", search:true, sortable:true
						},
                                                {       name: 'marks_obtained', index: 'marks_obtained', align: "center", search:true, sortable:true
						},
                                                {       name: 'test_status', index: 'test_status', align: "center", search:true, sortable:true
						},
						{       name: 'is_active', index: 'is_active', width: 80, align: "center", search:true, sortable:false,formatter: function (cellvalue) { if (cellvalue == 1) { return 'Yes'; }else{ return 'No'; } }, stype:"select",searchoptions:{ value: op, sopt:['eq'], dataEvents :[{ type: 'change', fn: function(e) { var thisval = $(this).find('option:selected').text(); if(thisval=='1'){ sym= arr[1]; }else{ sym = arr[0]; } } }] }
						},
                                                {       name: 'created_date', index: 'created_date',align: "center",search:true,formatter:'date',formatoptions: { srcformat:'Y-m-D H:i:s', newformat:'<?php echo DATE_FORMAT; ?>'}, searchoptions:{sopt: ['dt']} 
                                                },
                                                {
                                                     name: 'user_test_id',index: 'user_test_id', align: "center",width: 30, search:false, sortable:false,key: false,formatter: function (cellvalue) { userStr = 'Are you really want to delete?'; return '<a  class="deleteicon-view  action-icon" title="Delete"  onClick="var con = confirm(\''+userStr+'\'); if(!con)return false;" href="'+basePath+'/usertest/deleteUserTest/'+cellvalue+'";">Delete</a>'; }
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
		jQuery("#usertest_list").jqGrid('navGrid','#pager',{del:false,add:false,edit:false,search:false},{multipleSearch:false});
		jQuery("#usertest_list").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});

		/* Boot strap date picker */
                $('#gs_created_date').datepicker({
                        format: '<?php echo DATE_FORMAT_BOOT_STRAP; ?>',
                });
                /* Filter data after choose date */
                $('#gs_created_date').change(function () {
                        $("#usertest_list")[0].triggerToolbar();
                });
                
                           
});

</script>