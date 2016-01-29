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
  <div class="mid_content_section"> <a class="click_title fileUpload btn">Add New Question</a>
    <?php if($this->session->flashdata('successMsg')) echo '<div class="alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'.$this->session->flashdata('successMsg').'</div>'; ?>
    <?php if($this->session->flashdata('errorMsg')) echo '<div class="alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'.$this->session->flashdata('errorMsg').'</div>'; ?>
    <div class="add_drems_section"><!--dashboard_table Start-->
      <div id="success_msg"></div>
      <form id="addCategory" name="addCategory" method="post" novalidate>
        <div class="inbox">
	  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <table class="step-form small_view">
            <tbody>
              <tr>
                <td class="filed-one select-section"><label>Test:</label>
                  <select  data-placeholder="Question Categories" style="width:350px;" multiple class="chosen-select" tabindex="8" name="category_id[]">
                    <?php
		    if($questionsCategories) { 
		      foreach($questionsCategories as $data) {
			if( $data->question_category_id !=DEFAULT_CATEGORY){ ?>
			  <option <?php echo isset($selCat)&& is_array($selCat)? in_array($data->question_category_id,$selCat)?'selected="selected"':'' :''; ?> value="<?php echo $data->question_category_id ?>"><?php echo $data->category_title ?></option>
			<?php
			}
		      }
		    } ?>
                  </select>
                  <span class="error_star">*</span>
		  <span class="validatetext" id="no_of_options_validate">
                  <?php  echo form_error('category_id[]');?>
                  </span>
		</td>
                <td>
		  <label>Answer Type:</label>
                  <input  class="answer_type" <?php echo set_value('answer_type')?set_value('answer_type')==1?'checked="checked"':'':'checked="checked"' ?>  type="radio" placeholder="Answer Type" name="answer_type" value="1">
                  Single Answer &nbsp; &nbsp; &nbsp;
                  <input <?php echo set_value('answer_type')==2?'checked="checked"':'' ?> class="answer_type" type="radio" placeholder="Name" name="answer_type" value="2">
                  Multi Answer <span class="error_star">*</span> <span class="validatetext" id="question_type_validate">
                  <?php  echo form_error('answer_type');?>
                  </span>
		</td>
              </tr>
              <tr>
                <td>
		  <label>Number of Answers:</label>
                  <?php	  
		  $numberOfAns = array();
		  for($i=0;$i<6;$i++) 
		    $numberOfAns[$i+2] = $i+2;
		      echo form_dropdown('no_of_options',$numberOfAns,set_value('no_of_options',4),'id="no_of_options" class="requried"'); ?>
                  <span class="error_star">*</span>
		  <span class="validatetext" id="no_of_options_validate">
                  <?php  echo form_error('no_of_options');?>
                  </span>
		</td>
                <td>
		  <label>Difficulty Level:</label>
                  <?php	  
		  $diffDrop[''] = '-Select-';
		  if($diffLevels) {
		    foreach($diffLevels as $key=>$data)
		      $diffDrop[$data->preference]=$data->difficulty_levels_title;
		    }
		    echo form_dropdown('difficulty_levels_id',$diffDrop,set_value('difficulty_levels_id',4),'id="difficulty_levels_id" class="requried"'); ?>
                  </select>
                  <span class="error_star">*</span>
		  <span class="validatetext" id="difficulty_levels_id_validate">
                  <?php  echo form_error('difficulty_levels_id');?>
                  </span></td>
              </tr>
              <tr>
                <?php /*?> <td>
                             	<label>Questions Subject:</label>
                                <input type="text" value="<?php echo set_value('question_title') ?>" placeholder="Questions Subject" name="question_title" id="question_title" class="">
                               <span class="validatetext" id="question_title_validate"><?php  echo form_error('question_title');?></span> 
                             </td><?php */?>
                <td colspan="2"><label>Question</label>
                  <textarea name="question_description" id="question_description" placeholder= "Enter a Question description" class="textarea-box required"><?php echo set_value('question_description','') ?></textarea>
                  <span class="error_star">*</span> <span class="validatetext" id="question_description_validate">
                  <?php  echo form_error('question_description');?>
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
		      $numberOfAns=4;		
		      if(set_value('no_of_options'))
			$numberOfAns = set_value('no_of_options');
		      if(set_value('answer_type')==2) {
			$questionType = 'checkbox';
		      }
		      //print_r($correctAns);
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
                <td colspan="2"><input type="submit" value="Add" title="Add" class=" btn"></td>
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
    <h1>Questions List </h1>
    <a class="fileUpload btn fr" href="<?php echo site_url(ADMIN.'questions/import'); ?>" title="Import Questions" >Import Questions</a> </div>
  <div class="dashboard_table12" id="gird_section">
    <table id="categories_list">
    </table>
    <div id="pager"></div>
  </div>
</div>
<!--#page-content-wrapper Start-->

<script type="text/javascript">
  $(document).ready(function(){
    $(".click_title").click(function(){
      $(this).toggleClass("plus-img");
      $(".add_drems_section").slideToggle()
    });
    <?php if(!set_value('answer_type')) { ?>
      $(".click_title").toggleClass("plus-img");
      $(".add_drems_section").slideToggle()
    <?php } ?>
  });
</script> 
<!--Toggle Class Sction--> 
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

		
jQuery(document).ready(function(){
    var op = ":All;1:Yes;0:No";
    var arr = ['0','1'];
    var sym;
    var questionId;
    var answerId;
    grid = $("#categories_list"),
    grid.jqGrid({
                url: basePath+'/questions/getQuestions',
                datatype: "json",
                colNames: ['CSRF Token', 'Question Id','question_description','Difficulty Level', 'Created Date', 'Updated Date', 'Is Active','Action'],
                colModel: [
		    {
                            name: '<?php echo $this->security->get_csrf_token_name(); ?>',hidden:true,formatter: function () { return '<?php echo $this->security->get_csrf_hash(); ?>'; }
                    },
		    {
                            name: 'questions_id',index: 'questions_id',width: 20, align: "center", search:true, sortable:true,key: true,formatter: function (cellvalue) { questionId = cellvalue; return cellvalue; }
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
                            name: 'Action',index: 'Action', align: "center",width: 30, search:false, sortable:false,key: true,formatter: function (cellvalue) { return '<a class="editicon-view action-icon" onclick="editQuestion('+questionId+')">Edit Question</a>'; }
                    }
                ],
                rowNum: 20,
                rowList: [10, 20,30,40],
                viewrecords: true,
                height: '100%',
                loadonce:false,
                autoencode: false,
                mtype: "POST",
                rownumbers: true,
                rownumWidth: 40,
                gridview: true,
                pager: '#pager',
                sortname: 'created_date',
		sortorder: 'desc',
                multiselect:false,
                filterToolbar: true,
                caption: "",
		subGrid : true,
		subGridUrl: basePath+'/questions/getAnswers',
		
    		subGridModel: [{ name  : ['No','Answer','Is Correct','Is Active'], width : [100,600,70,70], params:['<?php echo $this->security->get_csrf_token_name(); ?>']} ],
                ignoreCase: true,
                autowidth: true,
		postData: { <?php echo $this->security->get_csrf_token_name(); ?>: function(){ return "<?php echo $this->security->get_csrf_hash(); ?>" }}
		
    });
    //grid.jqGrid('navGrid','#pager',{del:false,add:false},editSettings,{},{},{multipleSearch:false});
    grid.jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
});


function editQuestion(questionId) {
	//obj = $(obj);
	//var questionId = obj.closest('tr').next('tr').attr('id');
	window.location = basePath+'/questions/editQuestion/'+questionId;
}
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
	//console.log(existingQueCnt);
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