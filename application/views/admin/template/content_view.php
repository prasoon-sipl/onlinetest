<?php
/*
* Version       : 1.0
* Filename      : content_view.php
* Purpose       : This file is used to show admin content
*/
?>

<div  id="page-content-wrapper"><!--#page-content-wrapper Start-->
	<?php 
	  if(isset($userMenuContent)) echo $userMenuContent; 
	 if(isset($contentarea)) echo $contentarea;
	
	?>
</div>
<!--#page-content-wrapper End-->

