<?php
/*
* Version       : 1.0
* Filename      : user_edit_view.php
* Purpose       : This file is used to Edit user
*/
?>
<script type="text/javascript">
$(document).ready(function(){
	$(".click_title").click(function(){
		$(this).toggleClass("plus-img");
		$(".add_drems_section").slideToggle()
	});
});
</script>

<div id="page-content-wrapper"><!--#page-content-wrapper Start-->

<div class="mid_content_section">
  <h1 class="click_title">Edit User</h1>
  <div class="add_drems_section"><!--dashboard_table Start--> 
    <!-- flash message -->
    <?php if($this->session->flashdata('successMsg')) echo '<div class="alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'.$this->session->flashdata('successMsg').'</div>'; ?>
    <?php if($this->session->flashdata('errorMsg')) echo '<div class="alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'.$this->session->flashdata('errorMsg').'</div>'; ?>
    <!--// flash message -->
    <div id="success_msg"></div>
    <form id="editUser" name="editUser" novalidate>
      <div class="inbox">
	 <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
        <table class="step-form small_view">
          <tbody>
            <tr>
              <td class="filed-one select-section"><label>Full Name:</label>
                <input value="<?php echo $userDetail->full_name; ?>" type="text" placeholder="Full Name" name="full_name" id="full_name" class="required" maxlength="100" minlength="2">
                <span class="error_star">*</span> <span class="validatetext" id="full_name_validate"></span></td>
              <td class="filed-one select-section"><label>Email:</label>
                <input value="<?php echo $userDetail->email; ?>"  type="text" email="true" placeholder="Email" name="email" id="email" class="required" email="true" />
                <span class="error_star">*</span> <span class="validatetext" id="email_validate"></span></td>
            </tr>
            <tr>
              <td class="filed-one select-section"><label>Is Active:</label>
                <select id="is_active" name="is_active">
                  <option value="1" <?php if($userDetail->is_active == 1)echo"selected=selected"; ?>>Yes </option>
                  <option value="0" <?php if($userDetail->is_active == 0)echo"selected=selected"; ?>>No</option>
                </select>
                <span class="error_star">*</span> <span class="validatetext" id="discount_type_validate"></span></td
                           >
            </tr>
            <tr>
              <td class="filed-one select-section"><label>Password:</label>
                <input  type="text"  placeholder="Password" name="password" id="password"  custom_password="true" minlength="6" maxlength="15" >
                <span class="error_star">*</span> <span class="validatetext" id="password_validate"></span></td>
            </tr>
            <tr class="button-section">
              <td ><input type="hidden" id="user_id" name="user_id" value="<?php echo $userDetail->user_id; ?>" class="required" >
                <input type="submit" value="Update" title="Update" class="btn"></td>
            </tr>
          </tbody>
        </table>
        <div class="clr"></div>
      </div>
    </form>
  </div>
  <!--dashboard_table End--> 
</div>
</div><!--#page-content-wrapper End-->
<script type="text/javascript">
    
 $.validator.addMethod("custom_password", function(value, element) {
      return this.optional(element) || !/^[a-zA-Z0-9 ]*$/.test(value);
 }, "Password must contain at least 1 special character.");
   
	   //Function to block the non numeric key
 function blockNonNumbers(obj, e, allowDecimal, allowNegative) {
           var key; var isCtrl = false; var keychar; var reg; if (window.event) { key = e.keyCode; isCtrl = window.event.ctrlKey }
           else if (e.which) { key = e.which; isCtrl = e.ctrlKey; }
           if (isNaN(key)) return true; keychar = String.fromCharCode(key); if (key == 8 || isCtrl) { return true; }
           reg = /\d/; var isFirstN = allowNegative ? keychar == '-' && obj.value.indexOf('-') == -1 : false; var isFirstD = allowDecimal ? keychar == '.' && obj.value.indexOf('.') == -1 : false; return isFirstN || isFirstD || reg.test(keychar);
}

$(document).ready(function(){

$(function () {
    $('form').on('submit', function (e) {
           e.preventDefault();
           if($("#editUser").valid()){
               
           $('#footer_loader').fadeIn();
            $.ajax({
              type: 'post',
              url: basePath+'/user/editUser',
              data: $('form').serialize(),
			  dataType:'json',
                           success: function (res) { 
                               
                                $('#footer_loader').fadeOut();
                           
                                if(res.status) {
				    window.location = basePath+'/user/userEdit/<?php echo $userDetail->user_id;?>';
				} else {
					$('#success_msg').html('<p class="alert alert-danger">'+res.message+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button></p>');
					if(res.error) {
						if(res.error.formerror) {
							for(var i in res.error.formerror) {
								$("#" + i + "_validate").html('<span  class="error">'+res.error.formerror[i]+'</span>');
							}
						}
					}
				}
      
                           }
            });
        }
    });
		
});

$("#editUser").validate({
    rules: {
        email:{
            required: true,
            remote: {
                    url: basePath+"/user/checkUserEmail",
                    type: "post",
                    data: {
                            email: function(){ return $("#email").val(); },
                            user_id: function(){ return '<?php echo $userDetail->user_id; ?>' },
			    <?php echo $this->security->get_csrf_token_name(); ?>: function(){ return "<?php echo $this->security->get_csrf_hash(); ?>"; }
                    }
            } 
        }
    },
    messages: {
        email:{
            remote :"Email already exists."
        }                 
    },
    errorElement: "span",
    errorPlacement: function (error, element) {
        var name = $(element).attr("name");
		//alert(name);
        $("#" + name + "_validate").html(error);
    }
});


});
</script>