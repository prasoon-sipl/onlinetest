<?php
/*
* Version       : 1.0
* Filename      : user_view.php
* Purpose       : This file is used to show user
*/
?>
<script type="text/javascript">
$(document).ready(function(){
	$(".click_title").click(function(){
		$(".add_drems_section").slideToggle()
	});
});
</script>
<div  id="page-content-wrapper"><!--#page-content-wrapper Start-->
 
	<div class="mid_content_section">   
	<a class="click_title fileUpload btn">Add New User</a>
<?php if($this->session->flashdata('successMsg')) echo '<div class="alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'.$this->session->flashdata('successMsg').'</div>'; ?>
<?php if($this->session->flashdata('errorMsg')) echo '<div class="alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'.$this->session->flashdata('errorMsg').'</div>'; ?>

        <div class="add_drems_section" style="display: none;"><!--dashboard_table Start-->
            <div id="success_msg"></div>
                <form id="addUser" name="addUser" novalidate>
		    
                    <div class="inbox">
		      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                      <table class="step-form small_view">
	    	        <tbody>
                         <tr>
			     <td class="filed-one select-section">
                                <label>Full Name:</label>      
                                <input type="text" placeholder="Full Name" name="full_name" id="full_name" class="required" maxlength="100" minlength="2">
                                <span class="error_star">*</span>
                                <span class="validatetext" id="full_name_validate"></span>
                 	    </td>
                            <td class="filed-one select-section">
                                <label>Email:</label>      
                                <input  type="text" email="true" placeholder="Email" name="email" id="email" class="required" email="true" />
                                <span class="error_star">*</span>
                                <span class="validatetext" id="email_validate"></span>
                 	    </td>
                         </tr>
                         <tr>
                             
                              <td class="filed-one select-section">
                                <label>Password:</label>      
                                <input  type="text"  placeholder="Password" name="password" id="password" class="required" custom_password="true" minlength="6" maxlength="15" >
                                <span class="error_star">*</span>
                                <span class="validatetext" id="password_validate"></span>
                 	     </td>                            
                           </tr>
                           
                          <tr class="button-section">
            				<td > 
                            <input type="submit" value="Add" title="Add" class="btn">
            				</td>
                          </tr>
                         </tbody>
                       </table>

                <div class="clr"></div>		
            </div>
                </form>	
            </div><!--dashboard_table End-->
     </div> 
    
    
        <div class="section-title">
            <h1>User List</h1>
        </div>
        <div class="dashboard_table12" id="gird_section">
            <table id="user_list"></table>
            <div id="pager"></div>
        </div>
    </div><!--#page-content-wrapper End-->
        

<script type="text/javascript">
	   //Function to block the non numeric key
    function blockNonNumbers(obj, e, allowDecimal, allowNegative) {
           var key; var isCtrl = false; var keychar; var reg; if (window.event) { key = e.keyCode; isCtrl = window.event.ctrlKey }
           else if (e.which) { key = e.which; isCtrl = e.ctrlKey; }
           if (isNaN(key)) return true; keychar = String.fromCharCode(key); if (key == 8 || isCtrl) { return true; }
           reg = /\d/; var isFirstN = allowNegative ? keychar == '-' && obj.value.indexOf('-') == -1 : false; var isFirstD = allowDecimal ? keychar == '.' && obj.value.indexOf('.') == -1 : false; return isFirstN || isFirstD || reg.test(keychar);
	}
   	
/* Load Jq grid */		
jQuery(document).ready(function(){
		var userId='';
		var op = ":All;1:Yes;0:No";
		var arr = ['0','1'];
		var sym;
                var ds = ":All;1:Flat;0:Percent";
		var dsarr = ['0','1'];
		var dsym;
                var symb = '';
		
        grid = $("#user_list"),
        grid.jqGrid({
				
				url: basePath+'/user/userGrid',
				
				datatype: "json",
				
				colNames: ['user_id','full_name','email', 'Is Active', 'Created Date','Action'],
				
				colModel: [
						{
							name:'user_id',index:'user_id',sortable:true,search:false,hidden:true, formatter: function (cellvalue) { userId =cellvalue; }
						},
						{
							name:'full_name',index:'full_name',sortable:true,search:true 
						},
						{       name: 'email', index: 'email', width: 80, align: "left", search:true, sortable:true
						},
						{       name: 'is_active', index: 'is_active', width: 80, align: "center", search:true, sortable:false,formatter: function (cellvalue) { if (cellvalue == 1) { return 'Yes'; }else{ return 'No'; } }, stype:"select",searchoptions:{ value: op, sopt:['eq'], dataEvents :[{ type: 'change', fn: function(e) { var thisval = $(this).find('option:selected').text(); if(thisval=='1'){ sym= arr[1]; }else{ sym = arr[0]; } } }] }
						},
                                                {       name: 'created_date', index: 'created_date',align: "center",search:true,formatter:'date',formatoptions: { srcformat:'Y-m-D H:i:s', newformat:'<?php echo DATE_FORMAT; ?>'}, searchoptions:{sopt: ['dt']} 
                                                },
                                                {
                                                     name: 'Action',index: 'Action', align: "center",width: 30, search:false, sortable:false,key: false,formatter: function (cellvalue) { userStr = 'Are you really want to delete?'; return '<a href="'+basePath+'/user/userEdit/'+userId+'" class="editicon-view action-icon" >Edit user</a> / <a  class="deleteicon-view  action-icon" title="Delete"  onClick="var con = confirm(\''+userStr+'\'); if(!con)return false;" href="'+basePath+'/user/deleteUser/'+userId+'";">Delete</a>'; }
                                                }        
				],
				rowNum: 10,
				rowList: [10, 20,30,40],
				viewrecords: true,
				sortorder: 'desc',
				height: '100%',
				loadonce:false,
				autoencode: false,
				mtype: "POST",
				rownumbers: true,
				rownumWidth: 40,
				gridview: true,
				pager: '#pager',
				sortname: 'created_date',
				multiselect:false,
				filterToolbar: true,
				caption: "",
                                ignoreCase: true,
				autowidth: true,
				postData: { <?php echo $this->security->get_csrf_token_name(); ?>: function(){ return "<?php echo $this->security->get_csrf_hash(); ?>" }}
				
		});
		jQuery("#user_list").jqGrid('navGrid','#pager',{del:false,add:false,edit:false,search:false},{multipleSearch:false});
		jQuery("#user_list").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});

		/* Boot strap date picker */
                $('#gs_created_date').datepicker({
                        format: '<?php echo DATE_FORMAT_BOOT_STRAP; ?>',
                });
                /* Filter data after choose date */
                $('#gs_created_date').change(function () {
                        $("#user_list")[0].triggerToolbar();
                });
                
                           
});


$(function () {
    $('form').on('submit', function (e) {
		e.preventDefault();
        if($("#addUser").valid()){
            
         $('#footer_loader').fadeIn();
            $.ajax({
              type: 'post',
              url: basePath+'/user/addUser',
              data: $('form').serialize(),
			  dataType:'json',
                           success: function (res) {
                               $('#footer_loader').fadeOut();  
                              if(res.status) {
				  	$("#success_msg").html('<p class="alert alert-success" >New User added successfully. <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button></p>');
				  	$("#user_list").trigger("reloadGrid");
                                        $('#addUser')[0].reset();
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
$.validator.addMethod("custom_password", function(value, element) {
      return this.optional(element) || !/^[a-zA-Z0-9 ]*$/.test(value);
   }, "Password must contain at least 1 special character.");


$("#addUser").validate({
    rules: {
        email:{
            required: true,
            email:true,
            remote: {
                    url: basePath+"/user/checkUserEmail",
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
</script>