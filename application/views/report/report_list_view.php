<?php
/*
* Version       : 1.0
* Filename      : report_list_view.php
* Purpose       : This file is used to show user dashboard content
*/
?>
<!--.mid_content_section .home_inner .new_penal start here -->

<div class="mid_content_section home_inner new_penal">
  <div class="container-fluid"><!--.container-fluid start here -->
    <div class="inner-block">
      <div class="inner_heading"> 
        <!-- flash message -->
        <?php if($this->session->flashdata('successMsg')) echo '<div class="alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'.$this->session->flashdata('successMsg').'</div>'; ?>
        <?php if($this->session->flashdata('errorMsg')) echo '<div class="alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'.$this->session->flashdata('errorMsg').'</div>'; ?>
        <!--// flash message -->
        <h1>Given Test List</h1>
      </div>
      <input id="test_search" class="fr" placeholder="Search">
      <?php
		if($reports) { ?>
      <table id="requests_view" class="display clr" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>Reference Number<span></span></th>
            <th class="left-align">Title<span></span></th>
            <th>Test Duration (minutes) <span></span></th>
            <th>Total Questions <span></span></th>
            <th>Marks Obtained<span></span></th>
            <th>Percentage<span></span></th>
            <th class="icon-align">Date<span></span></th>
          </tr>
        </thead>
        <?php
				foreach($reports as $row) { ?>
        <tr>
          <td class="icon-app"><?php echo $row->user_test_id ?></td>
          <td class="icon-app left-align"><a href="<?php echo base_url('report/status/'.$row->user_test_id) ;?>"><?php echo $row->category_title ?></a></td>
          <td class="icon-app"><?php echo $row->test_duration ?></td>
          <td class="icon-app"><?php echo $row->no_of_question ?></td>
          <td class="icon-app"><?php echo $row->max_marks?round($row->marks_obtained,2).'/'.$row->max_marks:'Not Specified' ?></td>
          <td class="icon-app"><?php echo $row->max_marks?round(($row->marks_obtained*100)/$row->max_marks,2).'%':'Not Specified' ?></td>
          <td class="icon-app"><?php echo $row->created_date ?></td>
        </tr>
        <?php 
				} ?>
      </table>
      <?php
		} else { ?>
      <div class="alert alert-danger">No test given yet.</div>
      <?php
		}?>
    </div>
  </div>
  <!--.container-fluid End here --> 
</div>
<!--.mid_content_section .home_inner .new_penal End here --> 
<script type="text/javascript">
   $(document).ready(function() { 
     var table = $('#requests_view').DataTable(
             {"bSort" : false,
             "bInfo" : false,
             "bLengthChange": false,
             "aoColumnDefs": [{ "bSortable": false, "aTargets": [-1]}],
             "order": [[ 0, "desc" ]], 
             });
        
      $('#test_search').on( 'keyup', function () {
         table
        .columns( 1 )
        .search( this.value )
        .draw();
} );
 $('#requests_view_filter').hide();
} );
      

</script> 
