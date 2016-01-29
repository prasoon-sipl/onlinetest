<?php
/*
 * Version       : 1.0
 * Filename      : content_view.php
 * Purpose       : This file is used to display main content area
 */
?>
<!-- start middle section of page -->
<!--<div class="middle_area">-->

<div class="large-8 columns">
  <?php 
	  if(isset($userMenuContent)) echo $userMenuContent; 
	 if(isset($contentarea)) echo $contentarea;
	
	?>
</div>
<!--</div>--> 
<!-- end middle section of page --> 
