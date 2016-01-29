<?php
/*
* Version       : 1.1
* Filename      : settings_view.php
* Purpose       : This file is used to show setting page content
*/
?>

<!-- for heading section -->

<div class="section-title">
  <h1>Admin Settings</h1>
</div>
<!-- for heading section --> 
<!-- flash message -->
<?php if($this->session->flashdata('successMsg')) echo '<div class="alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'.$this->session->flashdata('successMsg').'</div>'; ?>
<?php if($this->session->flashdata('errorMsg')) echo '<div class="alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'.$this->session->flashdata('errorMsg').'</div>'; ?>
<!--// flash message -->
<!-- mid content section start here -->
<div class="mid_content_section">
  <div class="row-fluid">
    <div class="span12"> 
      <!-- form section start here -->
      <form id="setting_form" method="post" action="">
        <table class="step-form small_view">
	  
	  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <tr>
            <td class="filed-one"><label>Full Name:</label>
              <input type="text" name="full_name" id="full_name" placeholder= "Enter Full Name" class="required input-box" maxlength="45" value="<?php echo set_value('full_name',$settings->full_name)?>" >
              <span class="error_star">*</span> <span id="full_name_validate">
              <?php  echo form_error('full_name');?>
              </span></td>
            <!--</tr>
		    
		    <tr>-->
            <td class="filed-one"><label>Email:</label>
              <input type="text" name="email" id="email" placeholder= "Enter Email" class="required email input-box" maxlength="45" value="<?php echo isset($settings->email)? $settings->email:set_value('email')?>" >
              <span class="error_star">*</span> <span id="email_validate">
              <?php  echo form_error('email');?>
              </span></td>
          </tr>
          <tr>
            <td class="filed-one"><label>Current Password:</label>
              <span class="password_eye">
              <input type="password" name="old_password" id="old_password" placeholder= "Password" class="input-box" maxlength="16" value="<?php echo set_value('old_password'); ?>" >
              <a id="oldpass_show" class="hide_pass" href="javascript:void(0)">&nbsp;</a> </span> <span id="password_validate">
              <?php  echo form_error('old_password');?>
              </span></td>
            <!--</tr>
		     
		     <tr>-->
            <td class="filed-one"><label>New Password:</label>
              <span class="password_eye">
              <input type="password" name="password" id="password" placeholder= "Password" class="input-box" maxlength="16" value="<?php echo set_value('password'); ?>" >
              <a id="pass_show" class="hide_pass" href="javascript:void(0)">&nbsp;</a> </span> <span id="password_validate">
              <?php  echo form_error('password');?>
              </span></td>
          </tr>
          <tr>
            <td class="filed-one select-section"><label>Is Active:</label>
              <span class="select_bg cate_bg">
              <select name="is_active" id="is_active" class="required">
                <option value="1" <?php if($settings->is_active == 1) echo 'selected="selected"';?>>Yes</option>
                <option value="0" <?php if($settings->is_active == 0) echo 'selected="selected"';?>>No</option>
              </select>
              </span> <span class="error_star">*</span> <span id="is_active_validate" class="clr">
              <?php  echo form_error('is_active');?>
              </span></td>
            <!--</tr>
		    
		    <tr>-->
            <td class="filed-one select-section"></td>
          </tr>
          <tr>
            <td class="filed-one select-section"><label>Created Date:</label>
              <?php echo date(DATE_FORMAT, strtotime($settings->created_date));?></td>
            <!--</tr>
		    
		    
		    <tr>-->
            <td class="filed-one select-section"><label>Updated Date:</label>
              <?php echo date(DATE_FORMAT, strtotime($settings->updated_date));?></td>
          </tr>
          <tr class="button-section">
            <td colspan="2"><input type="submit" value="Save" title="Save" class="btn" />
          </tr>
        </table>
      </form>
      <!-- form section close here --> 
      
    </div>
    <!--/row--> 
    
  </div>
  <!--/.fluid-container--> 
</div>
<!-- mid content section close here --> 
<script type="text/javascript">
	
	$(document).ready(function() {
	    
	    $("#setting_form").validate({
		rules: {
			
		    email:{
			remote: {
			    url: basePath+"/settings/checkemail",
			    type: "post",
			    data: {
				    email: function(){ return $("#email").val(); },
				    <?php echo $this->security->get_csrf_token_name(); ?>: function(){ return "<?php echo $this->security->get_csrf_hash(); ?>"; }
		    
			    }
			}
		    }
		},
		messages: {
			email:{
				remote :"Email already exists. Please choose another email.",
			},					
		},
		errorElement: "span",
		errorPlacement: function (error, element) {
			var name = $(element).attr("name");
			$("#" + name + "_validate").html(error);
		}
	    });
       });
	
	
	 //show and hide password
	$("#oldpass_show").on('click', function(e){
	   var action = $(this).attr("class");
	   if (action == 'hide_pass') {
	       $(this).addClass("show_pass");
	       $("#old_password").prop("type", "text");
	   }else{
	       $(this).removeClass("show_pass");
	       $("#old_password").prop("type", "password");
	   }
       });
	
       $("#pass_show").on('click', function(e){
	   var action = $(this).attr("class");
	   if(action == 'hide_pass'){
	       $(this).addClass("show_pass");
	       $("#password").prop("type", "text");
	       
	   }else {
	       $(this).removeClass("show_pass");
	       $("#password").prop("type", "password");
	   }
       });
	
</script>