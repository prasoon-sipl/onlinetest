<?php
/*
* Version       : 1.0
* Filename      : header_bar_view.php
* Purpose       : This file is used to show admin header bar content
*/
?>

<div class="container-fluid">
<!--container-fluid Start-->
<div class="full_wrapper" id="wrapper_width">
<!--full_wrapper start-->
<header>
  <div class="logo fl"> <a href="<?php echo site_url(); ?>" title="<?php echo SITE_NAME;?>"><?php echo SITE_NAME;?></a> </div>
  <div class="navbar navbar-inverse fr"><!--navbar navbar-inverse Start-->
    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> Menu</button>
    <div class="navbar-inner"><!--navbar-inner Start-->
      <div class="nav-collapse collapse">
        <ul class="nav">
          <li <?php  if($this->uri->segment(2) == 'dashboard') echo 'class="active"'; ?>> <a href="<?php echo site_url(ADMIN.'dashboard'); ?>">Dashboard</a> </li>
          <li class="sub-menu <?php  if($this->uri->segment(2) == 'users') echo "active"; ?> "> <a href="<?php echo site_url(ADMIN.'user'); ?>"><span>Users</span></a>
            <ul class="dropdown-menu">
              <li <?php  if($this->uri->segment(2) == 'user') echo 'class="active"'; ?>> <a href="<?php echo site_url(ADMIN.'user'); ?>">Users List</a></li>
              <li <?php  if($this->uri->segment(2) == 'usertest') echo 'class="active"'; ?>> <a href="<?php echo site_url(ADMIN.'usertest'); ?>">User's Test</a></li>
              <li <?php  if($this->uri->segment(2) == 'logs') echo 'class="active"'; ?>> <a href="<?php echo site_url(ADMIN.'logs'); ?>">Logs</a></li>
            </ul>
          </li>
          <li class="sub-menu <?php  if($this->uri->segment(2) == 'configuration') echo "active"; ?>  "> <a href="<?php echo site_url(ADMIN.'configuration'); ?>"><span>Configuration</span></a>
            <ul class="dropdown-menu">
              <li><a href="<?php echo site_url(ADMIN.'configuration/category'); ?>">Tests</a></li>
              <li><a href="<?php echo site_url(ADMIN.'configuration/diffLevel'); ?>">Difficulty Levels</a></li>
              <li><a href="<?php echo site_url(ADMIN.'questions'); ?>">Questions</a></li>
            </ul>
          </li>
          <li class="last-child user-profile sub_menu"> <a href="#" class="table-display"> <span class="user_icon"> <img title="User Image" alt="User Image" src="<?php echo base_url(IMAGES.'user-img.png'); ?>"> </span> <?php echo $this->session->userdata('adminName'); ?> </a>
            <ul class="dropdown-menu">
              <li><a href="<?php echo site_url(ADMIN.'settings'); ?>">Setting</a></li>
              <li><a href="<?php echo site_url(ADMIN.'login/logout'); ?>">Signout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
    <!--navbar-inner close--> 
    
  </div>
  <!--navbar navbar-inverse End--> 
  
</header>

<div class="clr"></div>
