<?php
/*
* Version       : 1.0
* Filename      : question_import_view.php
* Purpose       : This file is used to import questions
*/
?>
<script type="text/javascript" src="<?php echo base_url(JS.'jquery.form.js'); ?>"></script>
<!-- extenal css links sent from controller -->

<div  id="page-content-wrapper"><!--#page-content-wrapper Start-->
  <div class="mid_content_section">
    <h1 class="click_title">Import Question <a  title="" href="<?php echo base_url(DOCS.'sample.xls');?>" class="fileUpload btn fr"><img src="<?php echo base_url(IMAGES.'downloading_icon.png'); ?>" width="20" title="Download sample file" alt="Download sample file">&nbsp;&nbsp;&nbsp;&nbsp;Download sample file</a></h1>
    <?php if($this->session->flashdata('successMsg')) echo '<div class="alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'.$this->session->flashdata('successMsg').'</div>'; ?>
    <?php if($this->session->flashdata('errorMsg')) echo '<div class="alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'.$this->session->flashdata('errorMsg').'</div>'; ?>
    <div class="add_drems_section"><!--dashboard_table Start-->
      <div id="success_msg"></div>
      <form id="doc_form" method="post" enctype="multipart/form-data" action='<?php echo base_url(ADMIN.'questions/uploadexcel')?>'>
	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
        <table class="step-form small_view">
          <tr>
            <td class="filed-one"><div class="browse_cover">
                <input name="document" id="document" type="file" class="upload" />
                <span id="doc_message" class="upload_msg error"></span>
                <div class="clr"></div>
                <div class="upload-box">
                  <input type="button" value="Upload" class="fileUpload btn" id="document_upload">
                  <a style="display:none;" id="add_link" title="" href="" class="fileUpload btn"><img src="<?php echo base_url(IMAGES.'downloading_icon.png'); ?>" title="Download error file" alt="Download error file">Download error file</a> </div>
                <div style="display:none;"  class="form_submit" id="doc_load"><img alt="Please wait" title="Please wait" src="<?php echo base_url(IMAGES.'global-ajax-loader.gif');?>"> <span id="doc_percentage"></span></div>
              </div></td>
            <td class="filed-two">Upload the Excel sheet Max size 2 MB. <br>
              Allowed type .xls, .xlsx</td>
          </tr>
        </table>
        <div id="suss_msg"> </div>
      </form>
    </div>
    <!--dashboard_table End--> 
  </div>
</div>
<!--#page-content-wrapper End--> 
<script type="text/javascript">
   $(document).ready(function() {
   //upload doc button code
    
     $('#document_upload').click(function(){
      $('#doc_message').html('');
     
      if($('#document').val()==''){
        $('#doc_message').html('<p>Required<p>');
        return false;
      }else{
        
        //validate the doc file
        var file = $('#document')[0].files[0];
        var fileName = $('#document').val();
	var exts = ['xls','xlsx'];
	// split file name at dot
	var fileExt = fileName.split('.');
	// reverse name to check extension
	fileExt = fileExt.reverse();
        // check file type is valid as given in 'exts' array
	if ( ! ($.inArray ( fileExt[0].toLowerCase(), exts ) > -1 )){
          $('#doc_message').html('<p>Only ".xls, .xlsx formats are allowed.</p>');
	  return false;
        }else if((parseInt(file.size)/1024) > 2048){
	  $('#doc_message').html('<p>Document size must 2 MB or below.<p>');
	  return false;
	}
      }
      $('#doc_form').submit();
    });
    
    var docOptions = {
        beforeSend: function()
        {
          //clear everything
          $("#doc_load").show();
          $("#doc_percentage").html("0%");
     
        },
        uploadProgress: function(event, position, total, percentComplete)
        {
          //alert(percentComplete);
          $("#doc_percentage").html(percentComplete+'%');                
        },
        success: function()
        {
          $("#doc_percentage").html('100%');
        },
        complete: function(response)
        {
          $("#doc_percentage").html('');
          if (response.responseText == 'logout') { //check user login
             $("#doc_load").hide('');
              bootbox.alert('You have been signed off! Please login again.', function() {
              window.location = basePath+'login';
            });
          }
          if(response.responseText == 'uploaded'){
            $("#doc_form")[0].reset();
            //$('#doc_load').hide();
            //get uploaded doc
            getErrorFileUrl();
            
          }else {
            $("#doc_load").hide('');
            $("#doc_message").html(response.responseText);
          } 
        },
        error: function()
        {
          $("#doc_load").hide('');
          $("#doc_message").html('An error was encountered while uploading, please try again later.');
          
        }
      };
      $('#doc_form').ajaxForm(docOptions);
      
      // to show the uploaded Doc
  function getErrorFileUrl() {
    
    $("#doc_load").show();
    $.ajax({
      url: basePath+'/questions/checkError',
      type: "POST",
      data:{ <?php echo $this->security->get_csrf_token_name(); ?> : '<?php echo $this->security->get_csrf_hash(); ?>'},
      dataType: "json",
      success: function(response){
        $('#doc_load').hide();        
        if(response){
              $("#add_link").attr("href",basePath+'/questions/getErrorFile');
              $("#add_link").show();               
        }else
         $("#suss_msg").html('<p id="suss_msg" class="alert-success alert">Questions added successfully.</p>');   
       }
    });
  }
      
      
  });
  </script>