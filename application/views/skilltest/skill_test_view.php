<?php
/*
* Version       : 1.0
* Filename      : home_view.php
* Purpose       : This file is used to show home page content
*/
?>

<script type="text/javascript" language="javascript">

window.history.forward();
function noBack() { 
	window.history.forward(0);
}

$(document).ready(function(){
	noBack();
	//window.history.back(0) = window.location;
});

$( window ).unload(function() {
  //alert( "Handler for .unload() called." );
});

$(document).ready(function(){
	noBack();
	//window.history.back(0) = window.location;
});



function DisableBackButton() {
window.history.forward()
}
DisableBackButton();
window.onload = DisableBackButton;
window.onpageshow = function(evt) { if (evt.persisted) DisableBackButton() }
window.onunload = function() { void (0) }
</script>
<!-- mid content section start here -->
<div class="mid_content_section home_inner new_penal">
    <div class="container-fluid">
      <div class="inner-block">
     	<div class="dashboard-upper upper-detailsec">
       		<ul class="test-detail">
              <li><span>Test name:</span><?php echo $subCategoryDetails->category_title;?></li>
              <li><span>Available time:</span><?php echo $subCategoryDetails->test_duration;?> minutes</li>
              <li><span>No of Questions:</span><?php echo $subCategoryDetails->no_of_questions;?></li>
            </ul>
        </div>
        <div class="inner_heading">
    		<h1>Terms and Conditions</h1>
        </div>
           <div class="terms-section"><!--.terms-section Start-->
           		<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p><p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p><p>It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                <input type="submit" id="start_test" class="cancle_changes" title="Start Test" value="Start Test"/>
           </div> <!--.terms-section End-->
      </div>  
	</div>
</div>
<script type="text/javascript">
 //var catNo = '';
 var catTitle = '<?php echo $subCategoryDetails->category_title;?>';

 $('#start_test').click(function(){
     var data = {cat_title: catTitle, <?php echo $this->security->get_csrf_token_name(); ?>: "<?php echo $this->security->get_csrf_hash(); ?>"}; 
     $('#footer_loader').fadeIn();
	 $.ajax({
		 url:basePath+'skilltest/preRequisites',
		 type:'post',
		 dataType:'json',
		 data:data,
		 success:function(data){ 
                     $('#footer_loader').fadeOut();
			  if(data.isLogout) {
				 alert('You have been signed off! please login again..');
				 window.location = basePath;
			  }  
					 
			  if(!data.status){
			       alert(data.message)
                               if(data.isTestRunning){
                                window.location = basePath+'skilltest/run/';   
                               }else{
                                window.location = basePath+'dashboard/';      
                               }
                          }else 
				window.location = basePath+'skilltest/run/';
			  
		}
   });
}); 
</script>

