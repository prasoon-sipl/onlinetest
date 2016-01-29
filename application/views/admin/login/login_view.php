<?php
/*
* Version       : 1.0
* Filename      : login_view.php
* Purpose       : This file is used to show admin login page content
*/
?>

<div class="login-wrapper"><!--Login Wrapper Start-->
  <div class="modal-header">
    <div class="inner_heading login_heading">
      <h1>Admin <span>Login</span></h1>
    </div>
  </div>
  <div class="modal-body login-fielde">
    <form id="loginformid" action="<?php echo site_url(ADMIN.'login/checklogin'); ?>" method="post" novalidate>
      <div class="login_fields"> <span class="error"><?php echo $this->session->flashdata('errorMsg');?></span>
        
	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
	<p> 
          <!--<label>Email:</label>--> 
          <span>
          <input type="text" placeholder="Email Id" class="required email" id="email" value="admindemo@onlinetest.com" name="email">
          <span class="validatetext" id="email_validate"><?php echo form_error('email'); ?></span> <span>Email- <b>admindemo@onlinetest.com</b></span> </span> </p>
        <div class="clr"></div>
        <p class="password_box"> 
          <!--<label>Password:</label>--> 
          <span>
          <input type="password" placeholder="Password" class="required" id="password" value="@dmindemo" name="password">
          <span class="validatetext" id="password_validate"><?php echo form_error('password'); ?></span> <span>Password- <b>@dmindemo</b></span> </span> </p>
        <div class="clr"></div>
        <button title="Login" class=" btn login_btn" id="submitlogin" type="submit">Login</button>
        <div class="clr"></div>
      </div>
    </form>
  </div>
</div><!--Login Wrapper End-->
<script type="text/javascript">
	$("#loginformid").validate({
		rules: {
		},
		messages: {
		
		},
		errorElement: "span",
		
		errorPlacement: function (error, element) {
		    var name = $(element).attr("name");
		    $("#" + name + "_validate").html(error);
		}
	});	
</script>