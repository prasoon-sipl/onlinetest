<?php
/*
* Version       : 1.0
* Filename      : question_categories_view.php
* Purpose       : This file is used to assign question from other categories to this category
*/
?>

<div  id="page-content-wrapper"><!--#page-content-wrapper Start-->
  <div class="section-title">
    <h1>Assign Questions from other Tests</h1>
  </div>
  <div class="mid_content_section">
    <p id="success_msg"></p>
    <ul class="test-detail">
      <li><span>Test Title</span> <?php echo $category->category_title ?> </li>
    </ul>
  </div>
  <div class="section-title">
    <h1>Other Tests List</h1>
  </div>
  <div class="mid_content_section">
    <div class="row-fluid">
      <div class="span4">
        <h4>Drag and drop test you want to assign to <?php echo $category->category_title ?>: </h4>
        <?php if($subcategory) { ?>
        <ul  id="existinSubcategories" class="connectedSortable">
          <?php foreach($subcategory as $key=>$data) { ?>
          <li>
            <label><b><?php echo $data->category_title ?></b></label>
            <div class="hidePannel">
              <input type="hidden" name="sub_categories[]" value="<?php echo $data->question_category_id ?>" />
              <label>
                <input dataTitle="<?php echo $data->category_title ?></b>" dataVal="" type="radio" name="sub_categories_<?php echo $data->question_category_id ?>" class="subcategories" value="all" />
                Add all questions</label>
              <label>
                <input dataTitle="<?php echo $data->category_title ?></b>" dataVal="<?php echo $data->question_category_id ?>" type="radio" name="sub_categories_<?php echo $data->question_category_id ?>" class="subcategories filteredit" value="<?php echo $data->question_category_id ?>" />
                Filter Questions</label>
            </div>
          </li>
          <?php } ?>
        </ul>
        <form method="post" action="" id="savesubcategories">
	  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <ul id="selectedSubcategories" class="connectedSortable">
          </ul>
          <input id="submitsubcategories" value="Save" type="submit" class="btn"/>
          <input  value="Cancel" onclick="window.location='<?php echo base_url(ADMIN.'configuration/category'); ?>'" type="button" class="btn"/>
        </form>
        <?php } else { 
			echo '<p class="alert alert-danger">No other test available. Please <a href="'.base_url(ADMIN.'questions').'">click here</a> to add question .</p>'; 
		} ?>
      </div>
      <div class="span8">
        <div class="dashboard_table12" id="gird_section">
          <h2 id="gridHeading"></h2>
          <table id="questions_list">
          </table>
          <div id="pager"></div>
        </div>
      </div>
      <div class="clr"></div>
    </div>
  </div>
</div><!--#page-content-wrapper End-->
<script>
var mainCategoryId = <?php echo $category->question_category_id ?>;
$(function() {
	$( "#existinSubcategories, #selectedSubcategories" ).sortable({
    	connectWith: ".connectedSortable"
    }).disableSelection();
});


</script> 
<script type="text/javascript">
jQuery(document).ready(function(){ 
	var grid='';
	var addEditRequest = true;
	function loadJQgird(categoryId){
		var op = ":All;1:Radio;2:Checkbox";
		var arr = ['0','1'];
		var sym;
		var questionId;
		var selectedRowArr = new Array();
		var selectedRowCounter=0;
		
		grid = $("#questions_list"),
		grid.jqGrid({
					url: basePath+'/configuration/filterQuesByCategory',
					datatype: "json",
					postData: {categoryId:categoryId, mainCategoryId:mainCategoryId, <?php echo $this->security->get_csrf_token_name(); ?>: function(){ return '<?php echo $this->security->get_csrf_hash(); ?>'}},
					mtype: 'POST',
					colNames: ['Question Id', 'Question Title','Answer Type','Difficulty Level', 'Created Date', 'Updated Date','selectStatus'],
					colModel: [
						{
								name: 'questions_id', index: 'questions_id', align: "center", search:true, sortable:true,key: true,formatter: function (cellvalue) { questionId = cellvalue; return cellvalue; }
						},
						{
								name:'question_description',index:'question_description', align: "left", editable: true, editrules: { required: true }, sortable:true,search:true
						},
						{name: 'answer_type', index: 'answer_type',editable: true, sortable:true, width: 100, align: "center",
                                    search:true,sort:false,edittype:"select",editoptions:{value:"1:Radio;2:Checkbox"},				    formatter: function (cellvalue) {       
                                    if (cellvalue == 1) {
                                    	return 'Radio';	
                                    }else{
                                    	return 'Checkbox';
                                    }
                    },stype:"select",searchoptions:{
                                    value: op,
                                    sopt:['eq'],
                                    dataEvents :[
						{type: 'change', fn: function(e) {
						var thisval = $(this).find('option:selected').text();
						if(thisval=='1'){
								sym= arr[1];
						}else{
								sym = arr[0];
						}
                                    }
                                    }]
                    }
                    },
						{
								name:'difficulty_levels_title',index:'difficulty_levels_title', align: "left", editable: true, editrules: { required: true }, sortable:true,search:true
						},
						{
								name: 'created_date', index: 'created_date', width: 120, align: "center",search:true,align: "center",formatter:'date',formatoptions: { srcformat:'Y-m-D H:i:s', newformat:'<?php echo DATE_FORMAT; ?>'}, searchoptions:{sopt: ['dt']} 
						},
						{
								name: 'updated_date', index: 'updated_date', width: 120, align: "center",search:true,align: "center",formatter:'date',formatoptions: { srcformat:'Y-m-D H:i:s', newformat:'<?php echo DATE_FORMAT; ?>'}, searchoptions:{sopt: ['dt']} 
						},
						{
								name: 'selectStatus',hidden:true, index: 'selectStatus', align: "center", search:true, sortable:true,key: true,formatter: function (cellvalue) { if(cellvalue) {selectedRowArr[selectedRowCounter++] = cellvalue;} return cellvalue; }
						}
					],
					rowNum: 10,
					beforeRequest: function(){
						grid.jqGrid('resetSelection');
					},
					onSelectRow: function (id,status) {
									if(status) {
										selectUnselectQuestions(id,'checked');
									} else selectUnselectQuestions(id,'unchecked');
								 	
								 },
					gridComplete: function(id,status){
									  
    								var grid_ids=grid.jqGrid('getDataIDs');
									addEditRequest = false;
									
									grid.jqGrid("resetSelection");
									for(var i=0; i<grid_ids.length; i++){
										var rowid = grid_ids[i];
										
										
										if($.inArray(rowid,selectedRowArr)!=-1){
											
												grid.jqGrid('setSelection',rowid,true);
											
											
										}
									}
									addEditRequest = true;
									selectedRowCounter=0;
									selectedRowArr = new Array();
					},
					rowList: [10, 20,30,40],
					viewrecords: true,
					sortorder: 'asc',
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
					multiselect: true,
					caption: "",
					ignoreCase: true,
					autowidth: true
		});
		//grid.jqGrid('navGrid','#pager',{del:false,add:false},editSettings,{},{},{multipleSearch:false});
		grid.jqGrid('filterToolbar',{stringResult: true,searchOnEnter:false});
	}
	
	// reload grid after every action
	  function reloadJqGrid(subCategoryId) {
		  //lastXhr.abort();
		  //loadWineGrid = true;
		  grid.setGridParam({
			  postData:{categoryId:subCategoryId,mainCategoryId:mainCategoryId, <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'},
			  page:1
		  });
		  grid.trigger("reloadGrid", [{current: true}]);
	  }
	
	$('.filteredit').on('click',function(e){
		//e.preventDefault();	
		var subCategoryId = $(this).attr('dataval');
		if(subCategoryId=='')
			return false;
		
		$('#gridHeading').html($(this).attr('dataTitle'));
		
		if(grid=='')
			loadJQgird(subCategoryId);
		else {
			reloadJqGrid(subCategoryId);
		}
	});
	
	
	$('#gird_section').on('click', '.cbox', function() {
    	var questionsId='';
		var checkuncheck='';
		
		if($(this).attr('id')=='cb_questions_list') {
			$('.cbox').each(function(){
				var idArr=$(this).attr('id');
				
				idArr = idArr.split('_');
				idArr=idArr[idArr.length-1];
				if(idArr!='' && !isNaN(idArr)) {
					if(questionsId=='')
						questionsId = idArr;
					else 
						questionsId += ','+idArr;
				}
			});
			if($(this).prop('checked')) {	
				checkuncheck = 'checked';
			} else 
				checkuncheck = 'unchecked';
			selectUnselectQuestions(questionsId,checkuncheck);
		}
	});
	
	function selectUnselectQuestions(questionsId,checkuncheck) {
		if(addEditRequest) {
			$.ajax({
				url:basePath+'/configuration/addRemoveQuestions',
				type:'post',
				dataType:'json',
				data:{action:checkuncheck, questionsId:questionsId, categoryId:mainCategoryId, <?php echo $this->security->get_csrf_token_name(); ?>: "<?php echo $this->security->get_csrf_hash(); ?>"},
				success:function(result){
					//alert(result);
				}
			});
		}
	}
	$('#savesubcategories').submit(function(e){
	  e.preventDefault();
	  var formObj = $(this);
	  console.log();
	  $.ajax({
	    url:basePath+'/configuration/saveQuesSubcategories/'+mainCategoryId,
	    type:'post',
	    dataType: 'json',
	    data:formObj.serialize(),
	    success:function(result){
	      if(result.status) {
		bootbox.alert(result.message+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>', function() {
		  //location.reload(true); 
		  window.location = basePath+"/configuration/viewQuesCategory/"+mainCategoryId;
		});
	      } else {
		$('#success_msg').html(result.message+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>');
		bootbox.alert(result.message, function() {
		//location.reload(true); 
		//window.location = basePath+"/configuration/questionsAssign/"+res.categoryId;
		});
	      }
	    }
	  });
      });
	
	//submitsubcategories
});
</script>