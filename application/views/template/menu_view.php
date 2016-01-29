<?php
/*
* Version       : 1.0
* Filename      : menu_view.php
* Purpose       : This file is used to load site menu
*/
?>

<button data-target=".nav-collapse" data-toggle="collapse" class=" btn-navbar" type="button"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
<div class="nav-collapse collapse fr"><!--nav-collapse Start here-->
  <ul class="nav">
    <li class="sub_menu no_marg"><a href="<?php echo base_url('documentation');?>" target="_blank"><span class="documentation_icon"><i></i>Documentation</span></a></li>
      <?php 
	     if($this->session->userdata('testSesUserId')) {?>
    <li class="sub_menu no_marg"><a href="<?php echo base_url('dashboard');?>"><span class="testlist_icon"><i></i>Browse Tests</span></a> </li>
    <li class="sub_menu no_marg"><a href="<?php echo base_url('report');?>"><span class="testreport_icon"><i></i>Results</span></a> </li>
    <li class=""> <a title="Logout" href="<?php echo base_url('login/logout'); ?>"><span class="logout-icon"><i></i>Logout</span></a></li>
    <li class="user-profile last-child"> <a> <span>
      <?php 
		       $name = $this->session->userdata('userDisplayName') !='' ? $this->session->userdata('userDisplayName'):$this->session->userdata('userFullName');
		  	   echo $name;                 
               ?>
      </span></a> </li>
    <?php  }?>
  </ul>
</div>
<a href="http://codecanyon.net/item/online-skills-assesment/9379895" style="margin-top:2px;" class="cancle_changes fr" target="_blank" >Buy Now</a>
<!--nav-collapse End here-->