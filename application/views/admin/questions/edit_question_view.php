<?php
/*
* Version       : 1.0
* Filename      : question_categories_view.php
* Purpose       : This file is used to show questions categories page content
*/
?>
<script type="text/javascript" src="<?php echo base_url(JS.'chosen.jquery.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url(JS.'jquery.form.js'); ?>"></script>
<!-- extenal css links sent from controller -->

<link type="text/css" href="<?php echo base_url(CSS.'chosen.css'); ?>" rel="stylesheet" />
<script type="text/javascript" src="<?php echo base_url(PLUGIN.'summernote/summernote.min.js'); ?>"></script><!-- extenal css links sent from controller -->
<link type="text/css" href="<?php echo base_url(PLUGIN.'summernote/bootstrap.no-icons.min.css'); ?>" rel="stylesheet" />
<link type="text/css" href="<?php echo base_url(PLUGIN.'summernote/font-awesome.min.css'); ?>" rel="stylesheet" />
<link type="text/css" href="<?php echo base_url(PLUGIN.'summernote/summernote.css'); ?>" rel="stylesheet" />
<div  id="page-content-wrapper"><!--#page-content-wrapper Start-->
  <div class="mid_content_section">
    <h1>Update Question</h1>
    <?php if($this->session->flashdata('successMsg')) echo '<div class="alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'.$this->session->flashdata('successMsg').'</div>'; ?>
    <?php if($this->session->flashdata('errorMsg')) echo '<div class="alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'.$this->session->flashdata('errorMsg').'</div>'; ?>
    <div id="success_msg"></div>
    <div class="add_drems_section"><!--add_drems_section Start-->
      
      <form id="addCategory" name="addCategory" method="post" novalidate>
        <div class="inbox">
	  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <table class="step-form small_view">
            <tbody>
              <tr>
                <td class="filed-one select-section"><label>Test:</label>
                  <select  data-placeholder="Question Categories" style="width:350px;" multiple class="chosen-select" tabindex="8" name="category_id[]">
                    <?php if($questionsCategories) { 
					if(!isset($selCat))
					$selCat = explode(',',$questionData[0]->sub_categories);
					foreach($questionsCategories as $data) {
                                           if( $data->question_category_id !=DEFAULT_CATEGORY){
                                            ?>
                    <option <?php echo isset($selCat)&& is_array($selCat)? in_array($data->question_category_id,$selCat)?'selected="selected"':'' :''; ?> value="<?php echo $data->question_category_id ?>"><?php echo $data->category_title ?></option>
                    <?php   }}
				       } ?>
                  </select>
                  <span class="error_star">*</span> <span class="validatetext" id="no_of_options_validate">
                  <?php  echo form_error('category_id[]');?>
                  </span></td>
                <td><label>Answer Type:</label>
                  <input  class="answer_type" <?php echo set_value('answer_type',$questionData[0]->answer_type)?set_value('answer_type',$questionData[0]->answer_type)==1?'checked="checked"':'':'checked="checked"' ?>  type="radio" placeholder="Answer Type" name="answer_type" value="1">
                  Single Answer &nbsp; &nbsp; &nbsp;
                  <input <?php echo set_value('answer_type',$questionData[0]->answer_type)==2?'checked="checked"':'' ?> class="answer_type" type="radio" placeholder="Name" name="answer_type" value="2">
                  Multi Answer <span class="error_star">*</span> <span class="validatetext" id="question_type_validate">
                  <?php  echo form_error('answer_type');?>
                  </span></td>
              </tr>
              <tr>
                <td><label>Number of Answers:</label>
                  <?php	  
								$numberOfAns = array();
								for($i=0;$i<6;$i++) 
									$numberOfAns[$i+2] = $i+2;
            					echo form_dropdown('no_of_options',$numberOfAns,set_value('no_of_options',$questionData[0]->no_of_options),'id="no_of_options" class="requried"'); ?>
                  <span class="error_star">*</span> <span class="validatetext" id="no_of_options_validate">
                  <?php  echo form_error('no_of_options');?>
                  </span></td>
                <td><label>Difficulty Level:</label>
                  <?php	  
								$diffDrop[''] = '-Select-';;
								if($diffLevels) {
									foreach($diffLevels as $key=>$data)
										$diffDrop[$data->preference]=$data->difficulty_levels_title;
								}
            					echo form_dropdown('difficulty_levels_id',$diffDrop,set_value('difficulty_levels_id',$questionData[0]->fk_difficulty_levels_id),'id="difficulty_levels_id" class="requried"'); ?>
                  </select>
                  <span class="error_star">*</span> <span class="validatetext" id="difficulty_levels_id_validate">
                  <?php  echo form_error('difficulty_levels_id');?>
                  </span></td>
              </tr>
              <tr>
                <td><label>Question</label>
                  <textarea name="question_description" id="question_description" placeholder= "Enter a Question description" class="textarea-box required"><?php echo set_value('question_description',$questionData[0]->question_description) ?></textarea>
                  <span class="error_star">*</span> <span class="validatetext" id="question_description_validate">
                  <?php  echo form_error('question_description');?>
                  </span></td>
                <td><label>Is Active:</label>
                  <select name="is_active" id="is_active">
                    <option value="1" <?php echo set_value('is_active',$questionData[0]->is_active)?'selected="selected"':'' ?> >Yes</option>
                    <option value="0" <?php echo !set_value('is_active',$questionData[0]->is_active)?'selected="selected"':'' ?> >No</option>
                  </select>
                  <span class="error_star">*</span> <span class="validatetext" id="is_active_validate">
                  <?php  echo form_error('is_active');?>
                  </span></td>
              </tr>
              <tr>
                <td colspan="2" style="padding:0;"><table class="step-form">
                    <tr>
                      <th valign="top" width="50px">No.</th>
                      <th valign="top">Is Correct Answer <span class="validatetext" id="answer_validate">
                        <?php  echo form_error('correctAns');?>
                        <?php  echo form_error('correctAns[]');?>
                        </span></th>
                      <th valign="top">Answer Title <span class="validatetext" id="answer_validate">
                        <?php  echo form_error('answer[]');?>
                        </span></th>
                    </tr>
                    <?php
							$numberOfAns = set_value('no_of_options',$questionData[0]->no_of_options);
							
							if(set_value('answer_type',$questionData[0]->answer_type)==2) {
								$questionType = 'checkbox';
							}
							//print_r($correctAns);
							
							$savedCorrAns = array();
							$savedAns = array();
							foreach($questionData as $key=>$val){
								if($val->is_correct==1)
									$savedCorrAns[]=$key+1;
								$savedAns[] = $val->answer_description;
							}
							
							
							if(!isset($correctAns))
								$correctAns = $savedCorrAns;
							
							if(!isset($selAns))
								$selAns = $savedAns;
						   	for($i=1;$i<=$numberOfAns;$i++) {?>
                    <tr class="<?php echo ($i%2==0)? 'even_row':'odd_row';?> questions_group">
                      <td><?php echo $i ?></td>
                      <td><input type="<?php echo isset($questionType)?'checkbox':'radio'?>" <?php echo isset($questionType)?'name="correctAns[]"':'name="correctAns"'?> <?php echo ( isset($correctAns) && is_array($correctAns))?in_array($i,$correctAns)?'checked="checked"':'':'';  ?> value="<?php echo $i ?>" class="correctAns"  /></td>
                      <td><textarea name="answer[]"><?php echo isset($selAns[$i-1])?$selAns[$i-1]:'' ?></textarea></td>
                    </tr>
                    <?php }		 
						   ?>
                  </table></td>
              </tr>
              <tr class="button-section">
                <td colspan="2"><input type="submit" value="Update" title="Add" class=" btn"></td>
                <td><input type="button" value="Go Back" onclick="window.location='<?php echo base_url(ADMIN.'questions') ?>'" title="Add" class="btn"></td>
              </tr>
            </tbody>
          </table>
          <div class="clr"></div>
        </div>
      </form>
    </div>
    <!--add_drems_section End--> 
  </div>
</div>
<!--#page-content-wrapper End--> 
<script type="text/javascript">
$('#question_description').summernote({
	      height: "200px",
	      width: "83%",
	      toolbar: [
	      //['style', ['style']], // no style button
	      ['style', ['bold', 'italic', 'underline', 'clear','fontname']],
	      ['fontsize', ['fontsize']],
	      ['color', ['color']],
	      ['para', ['ul', 'ol', 'paragraph']],
	      ['height', ['height']],
	      //['insert', ['picture']],
	      //['insert', ['link']], // no insert buttons
	      //['table', ['table']], // no table button
	      //['help', ['help']] //no help button
	      ]
      });

/* handling form according to selection */
// run while changing ans type
var answerType = $('.answer_type:checked').val();
$('.answer_type').click(function(){
	//1 for single types answer
	answerType=$(this).val();
	if($(this).val()==1) {
		var counter=1;
		$('.correctAns').each(function(){
			$(this).parent().html('<input class="correctAns" type="radio" value="'+counter+'" name="correctAns">');
			counter++;
		});
	} else if($(this).val()==2) {
	//2 for single multi answer
		var counter=1;
		$('.correctAns').each(function(){
			$(this).parent().html('<input class="correctAns" type="checkbox" value="'+counter+'" name="correctAns[]">');
			counter++;
		});
	}
});

// run while changing number of answers
$('#no_of_options').change(function(){
	var existingQueCnt = $('.questions_group').length;
	//alert(existingQueCnt);
	console.log(existingQueCnt);
	if(existingQueCnt>parseInt($(this).val())){
		existingQueCnt = existingQueCnt-parseInt($(this).val());
		for(var i=0;i<existingQueCnt;i++) {
			$('.questions_group').last().remove();
		}
	} else if(existingQueCnt<=parseInt($(this).val())) {
		for(var i=existingQueCnt+1;i<=parseInt($(this).val());i++) {
			var radioCheck='<input class="correctAns" type="radio" value="'+i+'" name="correctAns">';
			if(answerType==2)
			radioCheck='<input class="correctAns" type="checkbox" value="'+i+'" name="correctAns[]">';
			var evenOdd='even_row';
			if(i%2!=0)
				evenOdd = 'Odd_row';
			var htmlCont='<tr class="'+evenOdd+' questions_group"><td>'+i+'</td><td>'+radioCheck+'</td><td><textarea name="answer[]"></textarea></td></tr>';
			$('.questions_group').last().after(htmlCont);
		}
	}
});


</script> 
<script type="text/javascript">
    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    };
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
</script>