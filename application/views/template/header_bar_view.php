<?php
/*
* Version       : 1.0
* Filename      : header_bar_view.php
* Purpose       : This file is used to show the header menu
*/
?>

<div class="header-upper" data-anchor="top">
  <header><!--header Start Here-->
    <div class="container-fluid">
      <div class="navbar navbar-inverse"><!--navbar Start Here-->
        <div class="navbar-inner">
          <div class="logo fl"><a href="<?php echo base_url();?>" title="<?php echo SITE_NAME;?>"><?php echo SITE_NAME;?></a></div>
          <?php echo isset($menuContent)?$menuContent:''; ?> </div>
      </div>
      <!--navbar End Here--> 
    </div>
  </header>
  <!--header End Here--> 
</div>
