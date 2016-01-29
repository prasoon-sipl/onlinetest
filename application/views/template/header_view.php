<?php
/*
* Version       : 1.0
* Filename      : header_view.php
* Purpose       : This file is used to start the html and css and js
*/
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title><?php echo isset($title) ? $title : $this->config->item('defaultTitle'); ?></title>
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
    <!-- extra meta tag start -->
    <?php echo (isset($metaData) && !empty($metaData))?$metaData:'' ?><?php echo (isset($staticData) && !empty($staticData))?$staticData:'' ?>
    <!-- extra meta tag end -->

    <link rel="icon" href="<?php echo base_url(IMAGES.'favicon.ico');?>" type="image/x-icon" />
    <link href="<?php echo base_url(CSS.'style.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url(CSS.'reset.css'); ?>" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,600' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
    <!-- Le styles -->

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script async src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <script src="<?php echo base_url(JS.'jquery1.9.js'); ?>"></script>
    <script src="<?php echo base_url(JS.'bootstrap.min.js'); ?>"></script>
    <script src="<?php echo base_url(JS.'bootstrap.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(JS.'jquery.validate.js');?>"></script>
    <script type="text/javascript">
		var basePath='<?php echo base_url(); ?>'; 
		<?php /*?>var reDirectTo = '<?php echo $this->session->userdata('callbackurl') && $this->session->userdata('callbackurl')!=''?$this->session->userdata('callbackurl'):''; ?>';<?php */?>
		var reDirectTo = '';
        //login show and hide password
		$(document).ready( function(){
	$("#pass_show").on('click', function(e){
	    var action = $(this).attr("class");
	    if (action == 'hide_pass') {
		$(this).addClass("show_pass");
		$("#password").prop("type", "text");
	    }else{
		$(this).removeClass("show_pass");
		$("#password").prop("type", "password");
	    }
	});
	});
	
	//For header
	$(window).scroll(function() {
    var windscroll = $(window).scrollTop();
    if (windscroll >= 50) {
	 $('header').addClass('fixed');	
	}else{
	$('header.fixed').removeClass('fixed');	
	}
	});
    </script>

    <!-- extenal js links sent from controller -->
    <?php echo $jsLink; ?>
    <!-- extenal css links sent from controller -->
    <?php echo $cssLink; ?>
    </head>
    
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    
      ga('create', 'UA-56679202-1', 'auto');
      ga('send', 'pageview');

    </script>
    <?php $bodyClass= ''; if($this->uri->segment(1,0) != 'home' || $this->uri->segment(2,0) != '') $bodyClass = 'class="inner-bg"'; ?>
    <body class="inner-bg ">
