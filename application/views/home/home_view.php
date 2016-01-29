<?php
/*
* Version       : 1.0
* Filename      : home_view.php
* Purpose       : This file is used to show home page content
*/
?>
<script type="text/javascript">
  $(document).ready(function() {
    $("body").addClass("loginBanner");
  });
</script>

<div class="new-banner new-view"> </div>
<!--.mid_content_section .wellcome_penal Start-->
<div class="mid_content_section wellcome_penal">
  <div class="banner-txt">
    <h1> <?php echo SITE_NAME;?> </h1>
    <h4> Please login using below credentials. </h4>
    <?php
if(!$this->
session->
userdata('testSesUserId')) {?>
    <!--Login box-->
    <form id="loginformid" action="#" onsubmit="return loginRequest();" >
      <div id="login_message_validate" class="validatetext"> </div>
      <div class="login_fields">
        <?php
$formData =  explode('|',$this->
encrypt->
decode(get_cookie('user_auth_token')));
$userEmail = (isset($formData[0])? $formData[0] : ''); ?>

        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
        <p>
          <input type="text" name="user_email" id="user_email" class="required" maxlength="100" placeholder="Email Address" value="userdemo@onlinetest.com" />
          <span id="user_email_validate" class="validatetext"> </span> <span style="color:#fff;"> Email- <b> userdemo@onlinetest.com </b> </span> </p>
        <p class="password_box"> <span class="password_eye">
          <input type="password" name="password" id="password" class="required" maxlength="15" placeholder="Password"  value="u$erdemo"/>
          <a href="javascript:void(0)" id="pass_show" class="hide_pass"> &nbsp; </a> </span> <span id="password_validate" class="validatetext"> </span> <span style="color:#fff;"> Password- <b> u$erdemo </b> </span> </p>
        <p class="last">
          <button type="submit" id="submitlogin" class=" btn login_btn" data-loading-text="Verifying..." title="Login"> Login </button>
        </p>
        <div class="clr"> </div>
        <div class="form_submit" id="login_submit" style="display: none;"> <img src="
<?php echo IMAGES.'global-ajax-loader.gif';?>
"  width="42" title="Please wait" alt="Please wait"> </div>
      </div>
    </form>
    <!--Login box end--> 
    <script type="text/javascript">
      // Function for check validation for login form
      $("#loginformid").validate({
        rules: {
          password:{
            required: true,maxlength: 15,	minlength:6}
          ,
          user_email:{
            required: true, maxlength:100, email:true}
        }
        ,
        messages: {
          password:{
            required:"Required"}
          ,
          user_email:{
            required:"Required"}
        }
        ,
        errorElement: "span",
        errorPlacement: function (error, element) {
          var name = $(element).attr("name");
          $("#" + name + "_validate").html(error);
        }
      });
      // Function for  handle login request
      function loginRequest(){
        if ($("#loginformid").valid()) {
          
          $('#login_submit').fadeIn();
          
          $.ajax({
            url:basePath+'login/loginAjax',
            type:'post',
            dataType:'json',
            data:$('#loginformid').serialize(),
            success:function(data){
              //btn.button('reset');
              $('#login_submit').fadeOut();
              if(!data.status) {
                for(var value in data.message) {
                  if(data.message[value]!='')
                    $('#'+ value +'_validate').html(data.message[value]);
                }
                
              }
              else {
                if(reDirectTo!='')
                  window.location = reDirectTo;
                else {
                  if(!data.callbackurl)
                    window.location = basePath+'dashboard/';
                  else
                    window.location = data.callbackurl;
                }
              }
            }
          });
        }
        return false;
      }
      
      
    </script>
    <?php } ?>
  </div>
</div>
<!--.mid_content_section .wellcome_penal End-->
<div class="mid_content_section wellcome_penal"> 
  <!--.mid_content_section .wellcome_penal Start-->
  <div id="contain_wrapper" class="inner-block">
    <div class="row-fluid"> 
      <!--.row-fluid start-->
      <div class="span12 exam-loginview">
        <p> Online Test system is a php based web application to allows you to make powerful online tests and assessments in minutes. It provides a wide range of options to Administrator for creating a test including number of questions, total marks and total time according to his choice.
          Tests are fully customizable which can be modified to any extent by Administrator. Its easy User-friendly interface makes it stand apart from other softwares in this category.
          The notable features are, can accept plenty of questions and categories at a time, supports randomly selected questions, easy creation of multiple choice quizzes, result output both in natural score and rounded percentage at the end of each test and easy installation. </p>
      </div>
    </div>
    <!--.row-fluid start--> 
  </div>
</div>
<!--.mid_content_section .wellcome_penal End-->