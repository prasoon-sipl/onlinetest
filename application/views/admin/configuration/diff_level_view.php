<?php
/*
* Version       : 1.0
* Filename      : question_categories_view.php
* Purpose       : This file is used to show questions categories page content
*/
?>
<script type="text/javascript">
$(document).ready(function(){
	$(".click_title").click(function(){
		//$(this).toggleClass("plus-img");
		$(".add_drems_section").slideToggle()
	});
});
</script>

<div id="page-content-wrapper"><!--#page-content-wrapper Start-->
  
  <div class="mid_content_section"> <a class="click_title fileUpload btn">Add New Difficulty Level</a>
    <div class="add_drems_section" style="display: none;"><!--dashboard_table Start-->
      <div id="success_msg"></div>
      <form id="addDiffLevel" name="addDiffLevel" novalidate>
        <div class="inbox">
	  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <table class="step-form small_view">
            <tbody>
              <tr>
                <td class="filed-one select-section"><label>Title:</label>
                  <input type="text" placeholder="Difficulty Level Title" name="difficulty_levels_title" id="difficulty_levels_title" class="required">
                  <span class="error_star">*</span> <span class="validatetext" id="difficulty_levels_title_validate">
                  <?php  echo form_error('difficulty_levels_title');?>
                  </span></td>
              </tr>
              <?php /*?><tr>
                          	<td>
                            	<label>Difficulty Preference:</label>
                                <input type="text" placeholder="Difficulty Preference" name="preference" id="preference" class="required">
                                <span class="error_star">*</span>
                                <span class="validatetext" id="preference_validate"><?php  echo form_error('preference_title');?></span>
                            </td>
                          </tr><?php */?>
              <tr class="button-section">
                <td ><input id="addDiffLevelbtn" type="button" value="Add" title="Add" class="btn"></td>
              </tr>
            </tbody>
          </table>
          <div class="clr"></div>
        </div>
      </form>
    </div>
    <!--dashboard_table End--> 
  </div>
  <div class="section-title">
    <h1 class="fl">Difficulty Levels </h1>
    <div class="clr"></div>
  </div>
  <div class="dashboard_table12" id="gird_section">
    <table id="diff_levels_list">
    </table>
    <div id="pager"></div>
  </div>
</div>
<!--#page-content-wrapper End-->

<script type="text/javascript">
		
jQuery(document).ready(function(){
    var op = ":All;1:Yes;0:No";
    var arr = ['0','1'];
    var sym;

    
    grid = $("#diff_levels_list"),

    editSettings = {
        //recreateForm:true,
        jqModal:true,
        reloadAfterSubmit:true,
        closeOnEscape:true,
        savekey: [true,13],
        closeAfterEdit:false,
        afterComplete:function (response, postdata, formid) {
			//console.log(response);
			var resObj = jQuery.parseJSON( response.responseText );	
			//console.log(resObj);
			if(resObj.status) {
				$('#cData').trigger('click');
				
				bootbox.alert(resObj.message, function() {});
			} else {
				var formError;
				if(resObj.error) {
					formError='<ul>';
					for(var i in resObj.error.formerror) {
						if(resObj.error.formerror[i]!='');
							formError+='<li>'+resObj.error.formerror[i]+'</li>';
					}
					formError+='<ul>';
				}
				formError = '<p class="alert alert-danger" ><h2>'+resObj.message+'<h2>'+formError+'</p>';
				bootbox.alert(formError, function() {});
			}
        },
        editData: {}
    },
    grid.jqGrid({
                url: basePath+'/configuration/diffLevelGrid',
                datatype: "json",
                colNames: ['CSRF Token', 'Diff Level Id', 'Title','Preference', 'Created Date', 'Updated Date', 'Is Active'],
                colModel: [
		     {
                        name: '<?php echo $this->security->get_csrf_token_name(); ?>',hidden:true, editable: true, editrules: { required: true },formatter: function () { return '<?php echo $this->security->get_csrf_hash(); ?>'; }
                    },
                    {
                            name: 'difficulty_levels_id', index: 'difficulty_levels_id', align: "center", search:true, sortable:true,key: true,formatter: function (cellvalue) {  return cellvalue; }
                    },
                    {
                            name:'difficulty_levels_title',index:'difficulty_levels_title', width: 120, align: "left", editable: true, editrules: { required: true }, sortable:true,search:true
                    },
					{
                            name:'preference',index:'preference', align: "left", editable: false, editrules: { required: true }, sortable:true,search:true
                    },
                    {
                            name: 'created_date', index: 'created_date', width: 120, align: "center",search:true,align: "center",formatter:'date',formatoptions: { srcformat:'Y-m-D H:i:s', newformat:'<?php echo DATE_FORMAT; ?>'}, searchoptions:{sopt: ['dt']} 
                    },
                    {
                            name: 'updated_date', index: 'updated_date', width: 120, align: "center",search:true,align: "center",formatter:'date',formatoptions: { srcformat:'Y-m-D H:i:s', newformat:'<?php echo DATE_FORMAT; ?>'}, searchoptions:{sopt: ['dt']} 
                    },
                    {name: 'is_active', index: 'is_active',editable: true, sortable:true, width: 100, align: "center",search:true,sort:false,edittype:"select",editoptions:{value:"1:Yes;0:No"},				    formatter: function (cellvalue) {       
                         if (cellvalue == 1) {
                         	return 'Yes';	
                         }else{
                            return 'No';
                         }
                    },stype:"select",searchoptions:{
                                    value: op,
                                    sopt:['eq'],
                                    dataEvents :[
						{type: 'change', fn: function(e) {
						var thisval = $(this).find('option:selected').text();
						if(thisval=='1'){
								sym= arr[1];
						}else{
								sym = arr[0];
						}
                                    }
                                    }]
                    }
                    }
                ],
                rowNum: 10,
                rowList: [10, 20,30,40],
                viewrecords: true,
                sortorder: 'asc',
                height: '100%',
                loadonce:false,
                autoencode: false,
                mtype: "POST",
                rownumbers: true,
                rownumWidth: 40,
                gridview: true,
                pager: '#pager',
                sortname: 'preference',
                multiselect:false,
                filterToolbar: true,
                caption: "",
                editurl: basePath+'/configuration/editDiffLevels',
                ignoreCase: true,
                autowidth: true,
		postData: { <?php echo $this->security->get_csrf_token_name(); ?>: function(){ return "<?php echo $this->security->get_csrf_hash(); ?>" }}
    });
	grid.jqGrid('navGrid','#pager',{del:false,add:false,edit:true,search:false},{multipleSearch:false}).jqGrid('sortableRows',{
				stop: function (ev, ui) {
					var diffIds = '';
					var pageNo = grid.getGridParam('page');
					var perPage = $('.ui-pg-selbox').val();
					seq = grid.jqGrid('getDataIDs');
					for (var i = 0; i < seq.length; i++) {
						rowData = grid.getRowData(seq[i]);
						if(diffIds != '')diffIds = diffIds + ',';						
						diffIds = diffIds+rowData.difficulty_levels_id;
					}
					jQuery.ajax({
						type: 'POST',
						url: basePath+'/configuration/sortDiffLevels',
						data: { diffIds: diffIds, perPage: pageNo, pageNo:pageNo}
 							
						}).done(function( response ) {
							bootbox.alert('Rows has been sorted successfully!', function() {
								grid.trigger("reloadGrid");
							});
						}
					);	
				}
		});
    grid.jqGrid('navGrid','#pager',{del:false,add:false},editSettings,{},{},{multipleSearch:false});
    //grid.jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
});


$(function () {
    $('#addDiffLevelbtn').on('click', function () {
		//e.preventDefault();
                
        if($("#addDiffLevel").valid()){
            $.ajax({
              type: 'post',
              url: basePath+'/configuration/addDiffLevel',
              data: $('#addDiffLevel').serialize(),
			  dataType:'json',
              success: function (res) {
                 if(res.status) {
		    $("#success_msg").html('<p class="alert alert-success" >'+res.message+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button></p>');
		    $("#diff_levels_list").trigger("reloadGrid");
                    $('#addDiffLevel')[0].reset();
		} else {
                    $('#success_msg').html('<p class="alert alert-danger">'+res.message+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button></p>');
		     if(res.error) {
			if(res.error.formerror) {
			for(var i in res.error.formerror) {
			  $("#" + i + "_validate").html('<span for="difficulty_levels_title" class="error">'+res.error.formerror[i]+'</span>');
			}
		       }
		     }
	        }
      
              }
            });
        }
    });
		
});
$('#difficulty_levels_title').blur(function(){
	$('#success_msg').html('');
});


$("#addDiffLevel").validate({
    rules: {
        difficulty_levels_title:{
            required: true,
            remote: {
                    url: basePath+"/configuration/checkDiffLevels",
                    type: "post",
                    data: {
                            difficulty_levels_title: function(){ return $("#difficulty_levels_title").val(); },
			    <?php echo $this->security->get_csrf_token_name(); ?>: function(){ return "<?php echo $this->security->get_csrf_hash(); ?>"; }
                    }
            } 
        }
    },
    messages: {
        difficulty_levels_title:{
            remote :"Category name already exists."
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