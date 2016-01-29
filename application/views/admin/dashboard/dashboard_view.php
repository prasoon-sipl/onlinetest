<?php
/*
* Version       : 1.1
* Filename      : dashboard_view.php
* Purpose       : This file is used to show admin dashboard page content
*/
?>

<div id="page-content-wrapper"><!--#page-content-wrapper Start--> 
  <!-- mid content section start here -->
  <div class="mid_content_section home_inner new_penal">
    <div class="container-fluid">
      <div class="inner-block">
        <div class="dashboard-upper"> 
          <!-- flash message -->
          <?php if($this->session->flashdata('successMsg')) echo '<div class="alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'.$this->session->flashdata('successMsg').'</div>'; ?>
          <?php if($this->session->flashdata('errorMsg')) echo '<div class="alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'.$this->session->flashdata('errorMsg').'</div>'; ?>
          <!--// flash message --> 
        </div>
        <?php if($testCatagory){?>
        <div class="inner_heading">
          <h1>Active Skill Tests</h1>
        </div>
        <table class="test-grid">
          <tbody>
            <?php foreach($testCatagory as $details){ ?>
            <tr>
              <td><h2><?php echo $details->category_title; ?></h2>
                <div class="test-link">
                  <ul>
                    <?php   
				     if($details->child_cat_id){
					   $categoryIds = array();
					   $categoryNames = array();
					   $testDurations = array(); 
					   $totalQuestions = array();
					   $categoryIds =  explode(',',$details->child_cat_id);
				       $creatorName =  explode(',',$details->child_cat_name);
					   $totalQuestions =  explode(',',$details->child_cat_no_of_questions);
					   $testDurations =  explode(',',$details->child_cat_test_duration);
					  
					    foreach($categoryIds as $key=>$category ){?>
                    <li><a href="<?php echo base_url(ADMIN.'configuration/category');?>" title="<?php echo $creatorName[$key]; ?>"><?php echo $creatorName[$key]; ?></a><span>Duration: <?php echo $testDurations[$key]; ?> minutes, <?php echo $totalQuestions[$key];?> Question</span> </li>
                    <?php } 
	             } ?>
                  </ul>
                </div></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        <?php }else{ ?>
        <div class="alert alert-danger">
          <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
          No test available</div>
        <?php } ?>
      </div>
    </div>
  </div>
  <script type="text/javascript">
	$(document).ready(function(){	
	$(".test-grid td h2").click( function(){
	$(this).toggleClass("plus-img");
	 $(this).closest("td").find("div.test-link").slideToggle("fast");	
	});
	});
</script> 
</div>
<!--#page-content-wrapper End--> 

