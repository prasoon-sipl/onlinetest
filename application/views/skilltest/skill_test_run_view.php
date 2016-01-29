<?php
/*
* Version       : 1.0
* Filename      : skill_test_run_view.php
* Purpose       : This file is used to show home page content
*/

$testObj = array();
$testObj['question_id'] =  $questionDetails[0]->questions_id;
$testObj['answer_type'] =  $questionDetails[0]->answer_type;
$testObj['answer'] = '';
$testObj['status'] = true;
$testObj['content'] = ''; ?>
<script type="text/javascript">
  var testObj = <?php echo json_encode($testObj); ?>;
  testObj.timeSpent = 0;
</script>

<div class="mid_content_section home_inner new_penal"><!--.mid_content_section .home_inner .new_penal Start-->
  <div id="all_container" class="container-fluid inner-block"> <!--#all_container Start--> 
    <!-- flash message -->
    <?php if($this->session->flashdata('successMsg')) echo '<div class="alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'.$this->session->flashdata('successMsg').'</div>'; ?>
    <?php if($this->session->flashdata('errorMsg')) echo '<div class="alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'.$this->session->flashdata('errorMsg').'</div>'; ?>
    <!--// flash message -->
    
    <div class="terms-section heading-bg">
      <div class="test-details ">
        <ul class="test-detail">
          <li><span><b>Test name:</b> <?php echo $this->session->userdata('testName'); ?> </span></li>
          <li><span><b>No of Questions:</b> <?php echo $this->session->userdata('totalQuestions');?></span></li>
        </ul>
      </div>
      <aside class="remaining-time"><b>Time remaining:</b>
        <div id="countdown"></div>
      </aside>
      <div class="clr"></div>
    </div>
    <!--<p id="countdown_second"></p>-->
    <div class="terms-section">
      <form action="" method="post" id="testformid">
        <div id="question_content">
          <?php
     if($questionDetails){
        $count= 1;
        foreach($questionDetails as $question ){
          // Show question to user
            if($count == 1){?>
          <h1><?php echo "<span class='ques-no'>Question ".$this->session->userdata('noOFQuestionsGiven').":</span> "; echo $question->question_description ?></h1>
          <ul class="test-detail">
            <?php }
            
          // Check Question type and give option to user
            if($question->answer_type == 1){
                // display radio button
                ?>
            <li>
              <input type="radio" id="answer_<?php echo $question->answer_id ?>" value="<?php echo $question->answer_id; ?>" name="answer">
              <?php echo $question->answer_description;
                ?> </li>
            <?php
            }else{// display check box ?>
            <li>
              <input type="checkbox" class="multianswer" development="true" id="answer_<?php echo $question->answer_id?>" value="<?php echo $question->answer_id; ?>" name="answer">
              <?php echo $question->answer_description;
                ?> </li>
            <?php
            }
            $count++;
        }// End of foreach
        ?>
          </ul>
        </div>
        <button type="submit" id="submit_skip" value="0" class="test_submit btn login_btn" data-loading-text="next..." title="Skip">Skip</button>
        <button type="submit" id="submit_ans" value="1" class="test_submit btn login_btn" data-loading-text="Submiting..." title="submit">Submit</button>
        <input id="end_test" type="button" value="End test" class="btn login_btn fr">
      </form>
      <?php        
    }else{
     $this->session->set_flashdata('errorMsg','Configuration error please contact with admin.');
     redirect('/');
    }
 ?>
    </div>
  </div>
  <!--#all_container End--> 
</div>
<!--.mid_content_section .home_inner .new_penal End--> 
<!-- Modal -->
<div class="modal fade" id="info_box" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <div class="inner_heading login_heading">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h1 id="info_box_title" class="modal-title"></h1>
        </div>
      </div>
      <div id="info_box_content" class="modal-body"> <img src="<?php echo base_url(IMAGES.'global-ajax-loader.gif'); ?>" class="loderimg" alt="Loading...." /></div>
      <div class="modal-footer"> </div>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>
<!-- /.modal --> 

<!-- Modal -->
<div class="modal fade" id="error_box" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <div class="inner_heading login_heading">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h1 id="error_box_title" class="modal-title"></h1>
        </div>
      </div>
      <div id="error_box_content" class="modal-body"> <img src="<?php echo base_url(IMAGES.'global-ajax-loader.gif'); ?>" class="loderimg" alt="Loading...." /></div>
      <div class="modal-footer"> </div>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>
<!-- /.modal --> 

<script type="text/javascript"> 
    
$("#end_test").click(function() {
  if(confirm("Are you sure you want to end this Test?")){
   $('#footer_loader').fadeIn();
     $.ajax({
        url:basePath+'skilltest/endtest',
        type:'post',
        data:{ <?php echo $this->security->get_csrf_token_name(); ?> : '<?php echo $this->security->get_csrf_hash(); ?>'},
        dataType:'json',
            success:function(data){
            $('#footer_loader').fadeOut();
             if(data.status){
                 inActiveTime = true; 
                 $('#all_container').html("");
                 $('#info_box_title').html("Test Completed");
                   if(!data.test_id)
                    $('#info_box_content').html("<p>Thanks your test has been completed please <a href='"+basePath+"report/'>click here </a> for your results");
                   else
                    $('#info_box_content').html("<p>Thanks your test has been completed please <a href='"+basePath+"report/status/"+data.test_id+"'>click here </a> for your results");   
                  $('#info_box').modal('show');  
             }
             else{
                   $('#info_box').modal('hide');
                   $('#all_container').html("");  
                   $('#error_box_title').html("Error Ocuired");
                   $('#error_box_content').html("<p>"+data.message+"</p>");
                   $('#error_box').modal('show');
             }
            
        }
       });
  }
});    
$('#info_box').on('hidden.bs.modal', function (e) {
      window.location.href = basePath+'report';
});

$('#error_box').on('hidden.bs.modal', function (e) {
      window.location.href = basePath+'dashboard';
});

var inActiveTime = false;
var form_error = false;
var ans ='';
testObj.answer = '';
$(".test_submit").click(function() {
      form_error = false;
        if($(this).val() == 1){ 
    	 if(testObj.answer_type==2){
            var selected = [];
            $("input:checkbox[name=answer]:checked").each(function(){
                selected.push($(this).val());
            });
            testObj.answer =  selected.toString();
    	 }else{
      	   testObj.answer =  $('input:radio[name=answer]:checked').val();
    	 }
        
         if(testObj.answer== '' || testObj.answer == null ){
           form_error = true
         }else{
           form_error = false   
         }
     
     }else{
     	//if(confirm('Are you sure to skip this question' )){
            testObj.answer='';
        //}else    return false        
          
     }
     
if(!form_error){
     $('#footer_loader').fadeIn();
     $.ajax({
        url:basePath+'skilltest/submitAnswer',
        type:'post',
        dataType:'json',
        data:{question_id:testObj.question_id,answer_type:testObj.answer_type,answer:testObj.answer,status:testObj.status,time_spent:testObj.timeSpent, <?php echo $this->security->get_csrf_token_name(); ?>: "<?php echo $this->security->get_csrf_hash(); ?>"},
        success:function(data){
        $('#footer_loader').fadeOut();
        if(data.refresh){
                window.location.href = basePath+'skilltest/run/';
        }else     if(data.status){                       
               if(data.result){ 
                 inActiveTime = true; 
                 $('#all_container').html("");
                 $('#info_box_title').html("Test Completed");
                   if(!data.test_id)
                    $('#info_box_content').html("<p>Thanks your test has been completed please <a href='"+basePath+"report/'>click here </a> for your results");
                   else
                    $('#info_box_content').html("<p>Thanks your test has been completed please <a href='"+basePath+"report/status/"+data.test_id+"'>click here </a> for your results");   
                  $('#info_box').modal('show');  
               }                           
               testObj.question_id = data.question_id;  
               testObj.answer_type = data.answer_type;
               testObj.status = data.status;
               $('#question_content').html(data.content);
            }else{
                 inActiveTime = true;
                 if(data.is_time_finished){
                        $('#all_container').html("");  
                        $('#info_box_title').html("Time has been elapsed");
                         if(!data.test_id)
                         $('#info_box_content').html("<p>We are sorry your time has been elapsed please <a href='"+basePath+"report/'>click here </a> for your results");
                        else
                         $('#info_box_content').html("<p>We are sorry your time has been elapsed please <a href='"+basePath+"report/status/"+data.test_id+"'>click here </a> for your results");   

                        $('#error_box').modal('hide'); 
                        $('#info_box').modal('show');
                 }else if(data.is_test_completed){
                        $('#all_container').html("");
                        $('#info_box_title').html("Test Completed");
                        if(!data.test_id)
                        $('#info_box_content').html("<p>Thanks your test has been completed please <a href='"+basePath+"report/'>click here </a> for your results");
                       else
                        $('#info_box_content').html("<p>Thanks your test has been completed please <a href='"+basePath+"report/status/"+data.test_id+"'>click here </a> for your results");   

                        $('#error_box').modal('hide');
                        $('#info_box').modal('show');
                 }else{                 
                   $('#info_box').modal('hide');
                   $('#all_container').html("");  
                   $('#error_box_title').html("Error Ocuired");
                   $('#error_box_content').html("<p>"+data.message+"</p>");
                   $('#error_box').modal('show');
                }   
            }  
       }
    });
   }else{
    alert("Please select an answer or skip this question.");
   }
    return false;
});
</script> 
<script type="text/javascript">
// set the date we're counting down to
var testDurationMin = 0;
<?php if($this->session->userdata('testDuration')>0) { ?>
	testDurationMin = <?php echo $this->session->userdata('testDuration'); ?>	
<?php } ?>
var testStartTime=0;
<?php if($this->session->userdata('testStartTime')>0) { ?>
testStartTime = new Date(<?php echo (strtotime($this->session->userdata('testStartTime')) * 1000) ?>).getTime();
<?php } ?>
// variables for time units
var hours, minutes, seconds;
// get tag element
var countdown = document.getElementById('countdown');
//var countdown_second = document.getElementById('countdown_second');
var timeStamp = <?php echo (time() * 1000) ?>;

// update the tag with id "countdown" every 1 second
// find the amount of "seconds" between now and target
var timeElapsed = timeStamp-testStartTime;
var totalTimeDuration = (testDurationMin*60*1000);
var targetDate = testStartTime+totalTimeDuration;
var timeCounter = setInterval(checkDateTime, 1000);

    
function checkDateTime(){ 
    var timespent =  timeStamp-testStartTime;
    testObj.timeSpent = (timespent/1000);
   // countdown_second.innerHTML = testObj.timeSpent;
    var currentDate = new Date(timeStamp).getTime();
    timeStamp = timeStamp+1000;
    var secondsLeft = (targetDate - currentDate) / 1000;
    secondsLeft = secondsLeft % 86400;
    hours = parseInt(secondsLeft / 3600);
    secondsLeft = secondsLeft % 3600;
    minutes = parseInt(secondsLeft / 60);
    seconds = parseInt(secondsLeft % 60);
    
    if (seconds<0) {
       testCompleted();  
    }else{
         if(hours<10) hours = '0'+hours;
         if(minutes<10) minutes = '0'+minutes;
             
       countdown.innerHTML = '<h2><span> '+ hours + '<span>: </span> '+ minutes + '<span>:</span> ' + seconds + '<span></span></h2>';  
    }
}

function testCompleted(){
    clearInterval(timeCounter);
    $.ajax({
        url:basePath+'skilltest/setTestStatus',
        type:'post',
        data:{ <?php echo $this->security->get_csrf_token_name(); ?> : '<?php echo $this->security->get_csrf_hash(); ?>'},
        dataType:'json',
        success:function(data){
         if(!data.status){ 
            if(!inActiveTime) 
            alert(data.message);
            window.location.href = basePath+'report/';
          
        }else{
            $('#all_container').html("");
            $('#info_box_title').html("Time has been elapsed");
            $('#info_box_content').html("<p>We are sorry your time has been elapsed please <a href='"+basePath+"report/status/"+data.test_id+"'>click here </a>for your results");
            $('#error_box').modal('hide');
            $('#info_box').modal('show');            
        }        
       }
    });   
               
}
</script>