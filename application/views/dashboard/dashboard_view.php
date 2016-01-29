<?php
/*
 * Version       : 1.0
 * Filename      : dashboard_view.php
 * Purpose       : This file is used to show user dashboard content
 */
?>
<!-- mid content section start here -->

<div class="mid_content_section home_inner new_penal">
  <div class="container-fluid">
    <div class="inner-block">
      <div class="dashboard-upper"> 
        <!-- flash message -->
        <?php
if ($this->session->flashdata('successMsg'))
    echo '<div class="alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>' . $this->session->flashdata('successMsg') . '</div>';
?>
        <?php
if ($this->session->flashdata('errorMsg'))
    echo '<div class="alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>' . $this->session->flashdata('errorMsg') . '</div>';
?>
        <!--// flash message --> 
      </div>
      <div class="inner_heading">
        <h1>Available Skill Tests</h1>
      </div>
      
       <table id="cat_view" class="display test-grid">
	    <?php
	    if ($testCatagory) {
		foreach ($testCatagory as $details) { ?>
		    <thead>
			<tr class="heading-row">
			  <th><?php echo $details->category_title;?></th>
			  <th></th>
			  <th></th>
			</tr>
		    </thead>
		  
		    <?php
		    if ($details->child_cat_id) {
			$categoryIds    = array();
			$categoryNames  = array();
			$testDurations  = array();
			$totalQuestions = array();
			$percentages    = explode(',', $details->perc);
			$categoryIds    = explode(',', $details->child_cat_id);
			$creatorName    = explode(',', $details->child_cat_name);
			$totalQuestions = explode(',', $details->child_cat_no_of_questions);
			$testDurations  = explode(',', $details->child_cat_test_duration);
			
			foreach ($categoryIds as $key => $category) { ?>
			    <tr>
				<td>
				    <span  title="<?php echo $creatorName[$key];?>"><?php echo $creatorName[$key];?></span>
				    <p>
					Duration: <?php echo $testDurations[$key];?> minutes, No of Questions: <?php echo $totalQuestions[$key];?>
				    </p>
				</td>
				<?php 
				if ($percentages[$key] > 0) { ?>
				    <td>
					Your Last score <span>
					<?php echo $percentages[$key];?> %</span>
				    </td>
				<?php
				} else { ?>
				    <td></td>
				<?php
				} ?>
				<td>
				    <a href="<?php echo base_url('skilltest/catagory/' . $creatorName[$key]); ?>" title="<?php echo $creatorName[$key];?>">
				    <input type="submit" name="submit" class="cancle_changes" value="Start Test" title="Start Test"/>
				    </a>
				</td>
			    </tr>
			<?php
			}
		    } else {?>
			<tr>
			    <td colspan="3">
			       <div class="alert alert-danger">No test available.</div>
			    </td>
			</tr>
		    <?php
		    } 
		}
	    } else { ?>
		<tr>
		    <td colspan="3">
		       <div class="alert alert-danger">No test available.</div>
		    </td>
		 </tr>
	    <?php
	    } ?>
	    
	</table>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $('#cat_view').dataTable({
       "bSort" : false,
       "bInfo" : false,
       "bLengthChange": false,
       "aoColumns": [ {"bSearchable": true}, {"bSearchable": false}, {"bSearchable": false}],
       "fnDrawCallback": function(oSettings) {
        if ($('#cat_view tr').length < 11) {
            $('.dataTables_paginate').hide();
        }
       }
    });
    $('div.dataTables_filter input').attr('placeholder', 'Search');
	$(".test-grid td h2").click( function(){
	    $(this).toggleClass("plus-img");
	    $(this).closest("td").find(".test-link").slideToggle("fast");	
	});
    });
        
    function checktestStatus(cat){
	$('#footer_loader').fadeIn();
	$.ajax({
	   url:basePath+'dashboard/checkPayment',
	   type:'post',
	   dataType:'json',
	   data:{cat:cat},
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
			}
		   }else{
		       if(data.pay)
			   window.location = basePath+'skilltest/start/'+cat; 
		       else
			   window.location = basePath+'payment/pay/'+cat; 
		   } 
		   //window.location = basePath+'skilltest/run/';
	     }
	  });
    
       return false;
     }
</script> 
