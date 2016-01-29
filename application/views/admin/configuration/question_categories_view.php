<?php
/*
* Version       : 1.0
* Filename      : question_categories_view.php
* Purpose       : This file is used to show questions categories page content
*/
?>
<script type="text/javascript">
	$(document).ready(function(){
	$(".click_title").click(function(){
	$(this).toggleClass("plus-img");
	 $(".add_drems_section").slideToggle()
	});
	$(".click_title").toggleClass("plus-img");
	});
</script>

<div  id="page-content-wrapper"><!--#page-content-wrapper Start-->
  <div class="mid_content_section"> <a class="click_title fileUpload btn">Add New Test</a>
    <?php if($this->session->flashdata('successMsg')) echo '<div class="alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'.$this->session->flashdata('successMsg').'</div>'; ?>
    <?php if($this->session->flashdata('errorMsg')) echo '<div class="alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'.$this->session->flashdata('errorMsg').'</div>'; ?>
    <div class="add_drems_section"  style="display:none;"><!--dashboard_table Start-->
      <div id="success_msg"></div>
      <form id="addCategory" name="addCategory" novalidate>
        <div class="inbox">
	  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <table class="step-form small_view">
            <tbody>
              <tr>
                <td class="filed-one select-section"><label>Test Name:</label>
                  <input type="text" placeholder="Question Category Name" name="category_title" id="category_title" class="required">
                  <span class="error_star">*</span> <span class="validatetext" id="category_title_validate">
                  <?php  echo form_error('category_title');?>
                  </span></td>
              </tr>
              <tr class="inner-table option_section">
                <td class="padding-none"><div class="hide_parent">
                    <table class="step-form">
                      <tbody>
                        <tr>
                          <td><label>Test Duration(In Min):</label>
                            <input onkeypress="return isNumberKey(event)" type="text" placeholder="Test Duration(In Min):" name="test_duration" id="test_duration" class="required">
                            <span class="error_star">*</span> <span class="validatetext" id="test_duration_validate">
                            <?php  echo form_error('category_title');?>
                            </span>
                            <input type="hidden" value="1" name="is_active" /></td>
                        </tr>
                        <tr>
                          <td colspan="3" style="padding:0"><table width="100%;">
                              <?php 
				if($diffLevels) { ?>
                              <tr>
                                <?php foreach($diffLevels as $key=>$data){ ?>
                                <td><label><b><?php echo $key+1; ?>.</b> <?php echo $data->difficulty_levels_title ?> Questions:</label>
                                  <input type="hidden" name="preference[]" value="<?php echo $data->preference ?>"  />
                                  <input preference="<?php echo $data->preference ?>" maxlength="2" onkeypress="return isNumberKey(event)" class="question_count" type="text" placeholder="<?php echo $data->difficulty_levels_title ?> Questions" name="diff_levels_questions[]"></td>
                                <?php		
										}
									?>
                              </tr>
                              <tr>
                                <td ><label>Total Questions:</label>
                                  <input type="text" placeholder="Total Questions" name="no_of_questions" id="no_of_questions" readonly class="required">
                                  <span class="error_star">*</span> <span class="validatetext" id="no_of_questions_validate">
                                  <?php  echo form_error('no_of_questions');?>
                                  </span></td>
                                <td><label>Total marks:</label>
                                  <input readonly type="text" placeholder="Total marks" name="total_marks" id="total_marks" class="required">
                                  <span class="error_star">*</span> <span class="validatetext" id="total_marks_validate">
                                  <?php  echo form_error('category_title');?>
                                  </span></td>
                              </tr>
                              <?php }
									?>
                            </table></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
              </tr>
              <tr class="button-section">
                <td ><input type="submit" value="Add" title="Add" class="fileUpload btn"></td>
              </tr>
            </tbody>
          </table>
          <div class="clr"></div>
        </div>
      </form>
    </div>
    <!--dashboard_table End--> 
  </div>
  <div class="section-title">
    <h1>Tests List</h1>
  </div>
  <div class="dashboard_table12" id="gird_section">
    <table id="categories_list">
    </table>
    <div id="pager"></div>
  </div>
</div>
<!--#page-content-wrapper Start-->
<script type="text/javascript">
jQuery(document).ready(function(){
    var op = ":All;1:Yes;0:No";
    var arr = ['0','1'];
    var sym;
	var categoryId;
   
    grid = $("#categories_list"),

    editSettings = {
        //recreateForm:true,
        jqModal:true,
        reloadAfterSubmit:true,
        closeOnEscape:true,
        savekey: [true,13],
        closeAfterEdit:false,
        afterComplete:function (response, postdata, formid) {
               if(response.responseText == 'false'){}	
            },
        editData: {}
    },
    grid.jqGrid({
                url: basePath+'/configuration/categoriesgrid',
                datatype: "json",
                colNames: ['Test Id', 'Category Title', 'Created Date', 'Updated Date', 'Is Active','Action'],
                colModel: [
                    {
                            name: 'question_category_id', index: 'question_category_id', align: "center", search:true, sortable:true,key: true,formatter: function (cellvalue) { categoryId = cellvalue; return cellvalue; }
                    },
                    {
                            name:'category_title',index:'quesCat.category_title', align: "left", editable: true, editrules: { required: true }, sortable:true,search:true
                    },  
                    {
                            name: 'created_date', index: 'quesCat.created_date', width: 120, align: "center",search:true,align: "center",formatter:'date',formatoptions: { srcformat:'Y-m-D H:i:s', newformat:'<?php echo DATE_FORMAT; ?>'}, searchoptions:{sopt: ['dt']} 
                    },
                    {
                            name: 'updated_date', index: 'quesCat.updated_date', width: 120, align: "center",search:true,align: "center",formatter:'date',formatoptions: { srcformat:'Y-m-D H:i:s', newformat:'<?php echo DATE_FORMAT; ?>'}, searchoptions:{sopt: ['dt']} 
                    },
                    {name: 'is_active', index: 'quesCat.is_active',editable: true, sortable:true, width: 100, align: "center",
                                    search:true,sort:false,edittype:"select",editoptions:{value:"1:Yes;0:No"},				    formatter: function (cellvalue) {       
                                    if (cellvalue == 1) {
                                    return 'Yes';	
                                    }else{
                                    return 'No';
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
                            name: 'action', index: 'action',width: 220, align: "right", search:false, sortable:false,formatter: function (cellvalue) { str = 'Are you really want to delete?';  return '<a class="" title="Add questions" href="'+basePath+'/configuration/questionsAssign/'+categoryId+'";">Add Questions</a> /<a class="viewicon-view action-icon" title="View" href="'+basePath+'/configuration/viewQuesCategory/'+categoryId+'";">View</a> /<a class="editicon-view  action-icon"  title="Edit" href="'+basePath+'/configuration/editQuesCategory/'+categoryId+'";">Edit</a> / <a onClick="var con = confirm(\''+str+'\'); if(!con)return false;" class="deleteicon-view  action-icon" title="Delete" href="'+basePath+'/configuration/deleteQuesCategory/'+categoryId+'";">Delete</a>' }
                    }
                ],
                rowNum: 10,
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
                sortname: 'quesCat.category_title',
                multiselect:false,
                filterToolbar: true,
                caption: "",
                editurl: basePath+'/configuration/addeditcategory',
                ignoreCase: true,
                autowidth: true,
		postData: { <?php echo $this->security->get_csrf_token_name(); ?>: function(){ return "<?php echo $this->security->get_csrf_hash(); ?>" }}
    });
    //grid.jqGrid('navGrid','#pager',{del:false,add:false},editSettings,{},{},{multipleSearch:false});
    grid.jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
});

$(function () {
    $('form').on('submit', function (e) {
	e.preventDefault();
        if($("#addCategory").valid()){
            $.ajax({
		type: 'post',
		url: basePath+'/configuration/addeditcategory',
		data: $('form').serialize(),
		dataType:'json',
		success: function (res) {
			if(res.status) {
				bootbox.alert('New test has been created successfully. <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>', function() {
					location.reload(true); 
				});
			} else {
				$('#success_msg').html(res.message+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>');
				if(res.error) {
					if(res.error.formerror) {
						for(var i in res.error.formerror) {
							
							$("#" + i + "_validate").html(res.error.formerror[i]);
						}
					}
				}
			}
		}
            });
        }
    });	
});

var parentRule = {
    
    test_duration:
    {
        required: true,
        minlength: 10,
        maxlength: 60
    },
    no_of_questions:
    {
        required: true,
        minlength: 5,
        maxlength: 100
    },
    total_marks:
    {
        required: true,
        minlength: 5,
        maxlength: 100
    }     
};

function addRules(rulesObj){
    for (var item in rulesObj){
       $('#'+item).rules('add',rulesObj[item]);  
    } 
}

function removeRules(rulesObj){
    for (var item in rulesObj){
       $('#'+item).rules('remove');  
    } 
}

/* restrict only decimal in textbox */
function isNumberKey(e) {
	//var charCode = (evt.which) ? evt.which : event.keyCode;
	if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57))
	   return false;
	return true;
}

// calculating total questions and total marks
$('.question_count').on('keyup change blur',function(){
	
	var totalQuestion=0;
	var totalMarks=0;
	$('.question_count').each(function(){
		if($(this).val()!=''){
			var preferences = parseInt($(this).attr('preference'));
			totalQuestion +=parseInt($(this).val());
			//console.log(totalMarks);
			totalMarks+=parseInt($(this).val())*preferences;
			//isAllValueAdded = false;
			//break;
		}
	});
	
	$('#no_of_questions').val(totalQuestion);
	$('#total_marks').val(totalMarks);
	
});

$("#addCategory").validate({
    rules: {
        category_title:{
            required: true,
            remote: {
                    url: basePath+"/configuration/checkcategoryname",
                    type: "post",
                    data: {
                            category_title: function(){ return $("#category_title").val(); },
			     <?php echo $this->security->get_csrf_token_name(); ?>: function(){ return "<?php echo $this->security->get_csrf_hash(); ?>"; }
                    }
            } 
        },
    },
    messages: {
        category_title:{
            remote :"Category name already exists."
        },
    },
    errorElement: "span",
    errorPlacement: function (error, element) {
        var name = $(element).attr("name");
        $("#" + name + "_validate").html(error);
    }
});

</script> 
