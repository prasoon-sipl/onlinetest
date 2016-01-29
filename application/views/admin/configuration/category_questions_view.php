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
  <div class="section-title">
    <h1>Basic Details</h1>
  </div>
  <div class="mid_content_section">
    <?php if($this->session->flashdata('successMsg')) echo '<div class="alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'.$this->session->flashdata('successMsg').'</div>'; ?>
    <?php if($this->session->flashdata('errorMsg')) echo '<div class="alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'.$this->session->flashdata('errorMsg').'</div>'; ?>
    <p id="success_msg"></p>
    <ul class="test-detail">
      <li><span>Test Title:</span> <?php echo $category->category_title ?> </li>
    </ul>
  </div>
  <div> <a class="btn" href="<?php echo base_url(ADMIN.'configuration/questionsAssign/'.$category->question_category_id) ?>">Assign More Questions</a> </div>
  <div class="section-title">
    <h1>Questions of test</h1>
  </div>
  <div class="mid_content_section">
    <div class="dashboard_table12" id="gird_section">
      <table id="questions_list">
      </table>
      <div id="pager"></div>
    </div>
  </div>
</div>
<!--#page-content-wrapper End--> 
<script type="text/javascript">
jQuery(document).ready(function(){
    var op = ":All;1:Yes;0:No";
    var arr = ['0','1'];
    var sym;
    var questionId;
    var answerId;
    grid = $("#questions_list"),
    grid.jqGrid({
                url: basePath+'/questions/getQuestions/<?php echo $category->question_category_id ?>',
                datatype: "json",
                 colNames: ['CSRF Token', 'Question Id','question_description','Difficulty Level', 'Created Date', 'Updated Date', 'Is Active','Action'],
                colModel: [
		
		   {
                        name: '<?php echo $this->security->get_csrf_token_name(); ?>',hidden:true,formatter: function (cellvalue) { return '<?php echo $this->security->get_csrf_hash(); ?>'; }
                    },
		   {
                            name: 'questions_id',index: 'questions_id',width: 50, align: "center", search:true, sortable:true,key: true,formatter: function (cellvalue) { questionId = cellvalue; return cellvalue; }
                    },
					{
                            name: 'question_description',width: 200,align: "left", index: 'question_description',formatter: function (cellvalue) { return cellvalue; }
                    },
					
                   
					
					{
                            name:'difficulty_levels_title',width: 30,index:'difficulty_levels_title', align: "left", editable: true, editrules: { required: true }, sortable:true,search:true
                    },
					
                    {
                            name: 'created_date', index: 'questions.created_date', width: 60, align: "center",search:true,align: "center",formatter:'date',formatoptions: { srcformat:'Y-m-D H:i:s', newformat:'<?php echo DATE_FORMAT; ?>'}, searchoptions:{sopt: ['dt']} 
                    },
                    {
                            name: 'updated_date', index: 'questions.updated_date', width: 60, align: "center",search:true,align: "center",formatter:'date',formatoptions: { srcformat:'Y-m-D H:i:s', newformat:'<?php echo DATE_FORMAT; ?>'}, searchoptions:{sopt: ['dt']} 
                    },
                    {name: 'is_active', index: 'questions.is_active',editable: true, sortable:true, width: 50, align: "center",
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
                            name: 'Action',index: 'Action', align: "center",width: 40, search:true, sortable:true,key: true,formatter: function (cellvalue) { return '<a onclick="removeQuestion('+questionId+')">Remove Question</a>'; }
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
		subGrid : true,
		subGridUrl: basePath+'/questions/getAnswers',
		subGridModel: [{ name  : ['No','Answer','Is Correct','Is Active'], 
                width : [100,600,70,70], params:['<?php echo $this->security->get_csrf_token_name(); ?>']}],
                ignoreCase: true,
                autowidth: true,
		postData: { <?php echo $this->security->get_csrf_token_name(); ?>: function(){ return "<?php echo $this->security->get_csrf_hash(); ?>" }}
				
    });
    //grid.jqGrid('navGrid','#pager',{del:false,add:false},editSettings,{},{},{multipleSearch:false});
    grid.jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
});

function removeQuestion(questionId) {
	if(!confirm('Are you sure to remove question?'))
		return false
	$.ajax({
		url:basePath+'/configuration/removeCategQues/'+questionId,
		type:'post',
		dataType:'json',
		data:{questionId:questionId,categoryId:<?php echo $category->question_category_id ?>,<?php echo $this->security->get_csrf_token_name(); ?>:'<?php echo $this->security->get_csrf_hash(); ?>'},
		success:function(result) {
			if(result.status) {
				bootbox.alert(result.message+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>', function() {
					location.reload(true); 
				});
			} else {
				$('#success_msg').html(result.message+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>');
				bootbox.alert(result.message, function() {
					
				});
			}
		}
	});
	
}

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
				  	$("#success_msg").html('<p class="alert alert-success" >New test has been created successfully. <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button></p>');
					bootbox.alert('<p class="alert alert-success" >New test has been created successfully. <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button></p>', function() {
		            	//location.reload(true); 
						window.location = basePath+"/configuration/questionsAssign/"+res.categoryId;
	                });
				} else {
					$('#success_msg').html('<p class="alert alert-danger">'+res.message+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button></p>');
					if(res.error) {
						if(res.error.formerror) {
							for(var i in res.error.formerror) {
								//alert(res.error.formerror[i])
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

// check click on parent categor
$('#is_parent').click(function(){
	if($(this).is(":checked")){
		$('.hide_parent').hide(500);
		addRules(parentRule);
	} else {
		removeRules(parentRule);
		$('.hide_parent').show(500);
	}
});


var parentRule = {
    parent_category_id:
    {
        required: true
    },
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
            remote :"Test name already exists."
        },
    },
    errorElement: "span",
    errorPlacement: function (error, element) {
        var name = $(element).attr("name");
        $("#" + name + "_validate").html(error);
    }
});
</script>