<div  id="page-content-wrapper"><!--#page-content-wrapper Start-->
  <div class="mid_content_section">
    <h1 class="">Edit Test</h1>
    <div class="add_drems_section"><!--dashboard_table Start-->
      <div id="success_msg"></div>
      <form id="editCategory" name="editCategory" novalidate>
        <div class="inbox">
	  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <table class="step-form small_view">
            <tbody>
              <tr>
                <td class="filed-one select-section"><label>Question Test Name:</label>
                  <input type="text" placeholder="Question Category Name" name="category_title" id="category_title" value="<?php echo $formData->category_title ?>" class="required">
                  <span class="error_star">*</span> <span class="validatetext" id="category_title_validate">
                  <?php  echo form_error('category_title');?>
                  </span></td>
                <td class="filed-one select-section"><label>Is Active:</label>
                  <select name="is_active" id="is_active">
                    <option value="1" <?php echo $formData->is_active?'selected="selected"':'' ?> >Yes</option>
                    <option value="0" <?php echo !$formData->is_active?'selected="selected"':'' ?> >No</option>
                  </select>
                  <span class="error_star">*</span> <span class="validatetext" id="is_active_validate">
                  <?php  echo form_error('is_active');?>
                  </span></td>
              </tr>
              <tr class="inner-table option_section">
                <td colspan="2" class="padding-none"><div class="hide_parent" <?php echo empty($formData->parent_category_id)? 'style="display:none"':''?>>
                    <table class="step-form">
                      <tbody>
                        <tr>
                          <td><label>Test Duration(In Min):</label>
                            <input onkeypress="return isNumberKey(event)" type="text" placeholder="Test Duration(In Min):" name="test_duration" id="test_duration" class="required" value="<?php echo $formData->test_duration ?>">
                            <span class="error_star">*</span> <span class="validatetext" id="test_duration_validate">
                            <?php  echo form_error('category_title');?>
                            </span></td>
                        </tr>
                        <tr>
                          <td colspan="3" style="padding:0"><table width="100%;">
                              <?php								
									$diffLevelsData = json_decode($formData->difficulty_levels,true);
									if($diffLevels) {
										?>
                              <tr>
                                <?php
										foreach($diffLevels as $key=>$data){
									?>
                                <td><label><b><?php echo $key+1; ?>.</b> <?php echo $data->difficulty_levels_title ?> Questions:</label>
                                  <input type="hidden" name="preference[]" value="<?php echo $data->preference ?>"  />
                                  <input value="<?php echo isset($diffLevelsData[$data->preference])?$diffLevelsData[$data->preference]:'' ?>" preference="<?php echo $data->preference ?>" maxlength="2" onkeypress="return isNumberKey(event)" class="question_count" type="text" placeholder="<?php echo $data->difficulty_levels_title ?> Questions" name="diff_levels_questions[]"></td>
                                <?php		
										}
									?>
                              </tr>
                              <tr>
                                <td ><label>Total Questions:</label>
                                  <input type="text" placeholder="Total Questions" name="no_of_questions" id="no_of_questions" readonly class="required" value="<?php echo $formData->no_of_questions ?>">
                                  <span class="error_star">*</span> <span class="validatetext" id="no_of_questions_validate">
                                  <?php  echo form_error('no_of_questions');?>
                                  </span></td>
                                <td><label>Total marks:</label>
                                  <input readonly type="text" placeholder="Total marks" name="total_marks" id="total_marks" class="required" value="<?php echo $formData->total_marks ?>">
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
                  </div></td>
              </tr>
              <tr class="button-section">
                <td ><input type="submit" value="Update" title="Add" class="btn"></td>
                <td><a class="btn" href="<?php echo base_url(ADMIN.'configuration/questionsAssign/'.$formData->question_category_id) ?>" title="Add Questions from other Tests">Add Questions</a>
                  <input type="button" value="Go Back" onclick="window.location='<?php echo base_url(ADMIN.'configuration/category') ?>'" title="Go Back" class="btn"></td>
              </tr>
            </tbody>
          </table>
          <div class="clr"></div>
        </div>
      </form>
    </div>
    <!--dashboard_table End--> 
  </div>
</div>
<!--#page-content-wrapper End--> 
<script>
$(function () {
    $('form').on('submit', function (e) {
		e.preventDefault();
        if($("#editCategory").valid()){
            $.ajax({
              type: 'post',
              url: basePath+'/configuration/addEditCategory/<?php echo $formData->question_category_id ?>',
              data: $('form').serialize(),
	      dataType:'json',
              success: function (res) {
                if(res.status) {
		  $("#success_msg").html('<p class="alert alert-success" >'+res.message+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button></p>');
		  bootbox.alert(res.message+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>', function() { location.reload(true); });
		} else {
		  $('#success_msg').html(res.message+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>');
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

$("#editCategory").validate({
    rules: {
        category_title:{
            required: true,
            remote: {
                    url: basePath+"/configuration/checkcategoryname",
                    type: "post",
                    data: {
                            category_title: function(){ return $("#category_title").val(); },
			    question_category_id: function(){ return '<?php echo $formData->question_category_id ?>' },
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