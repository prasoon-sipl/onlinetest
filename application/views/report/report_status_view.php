<?php
/*
* Version       : 1.0
* Filename      : report_status_view.php
* Purpose       : This file is used to show user dashboard content
*/
?>
<!-- mid content section start here -->

<div class="mid_content_section home_inner new_penal">
  <div class="container-fluid" >
    <?php if($this->session->flashdata('successMsg')) echo '<div class="alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'.$this->session->flashdata('successMsg').'</div>'; ?>
    <?php if($this->session->flashdata('errorMsg')) echo '<div class="alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'.$this->session->flashdata('errorMsg').'</div>'; ?>
    <div class="inner-block ">
      <div class="inner_heading">
        <h1>Details of Skills Test </h1>
      </div>
      <div class="terms-section">
        <table id="requests_view" class="reasult-display">
          <!--            	<tr>
                    <th>Title</th>
                    <th>Total Questions</th>
                    <th>Test Duration</th>
                    <th>Attempted Questions</th>
                    <th>Marks Obtained</th>
                    <th>Percentage</th>
                    
                </tr>-->
          <tr cl>
            <td><b>Test Title</b></td>
            <td><?php echo $report->category_title ?></td>
          </tr>
          <tr class="even">
            <td><b>Total Questions</b></td>
            <td><?php echo $report->no_of_question ?></td>
          </tr>
          <tr>
            <td><b>Test Duration</b></td>
            <td><?php echo $report->test_duration ?> minutes</td>
          </tr>
          <tr class="even">
            <td><b>Attempted Questions</b></td>
            <td><?php echo $report->questions_attempted ?></td>
          </tr>
          <tr>
            <td><b>Marks Obtained</b></td>
            <td><?php echo $report->max_marks?round($report->marks_obtained,2).'/'.$report->max_marks:'Not Calculated' ?></td>
          </tr>
          <tr class="even">
            <td><b>Percentage</b></td>
            <td><?php echo $report->max_marks?round(($report->marks_obtained*100)/$report->max_marks,2).'%':'Not Calculated' ?></td>
          </tr>
        </table>
        <a class="btn fr" href="<?php echo base_url('report') ?>">Go Back</a> </div>
    </div>
  </div>
</div>
