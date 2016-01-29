<?php
/*
* Version       : 1.0
* Filename      : header_bar_view.php
* Purpose       : This file is used to show admin header content
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php echo isset($title) ? $title : $this->config->item('defaultTitle'); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link rel="icon" href="<?php echo base_url(IMAGES.'favicon.ico');?>" type="image/x-icon" />
<!-- Le styles -->
<link href="<?php echo base_url(CSS.'admin-style.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url(CSS.'admin-reset.css'); ?>" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->
<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
<script src="<?php echo base_url(JS.'jquery1.9.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url(JS.'jquery.validate.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url(JS.'bootstrap.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url(JS.'bootbox.min.js');?>"></script>
<script type="text/javascript">var basePath='<?php echo base_url(ADMIN); ?>';</script>
<!-- extenal js links sent from controller -->
<?php echo $jsLink; ?>
<!-- extenal css links sent from controller -->
<?php echo $cssLink; ?>
 <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    
      ga('create', 'UA-56679202-1', 'auto');
      ga('send', 'pageview');

</script>
</head>
<body class="inner-bg">