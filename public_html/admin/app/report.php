<?php require_once __DIR__ . '/auth.php'; require_admin(); ?>
<?php include "header.php"; 
include '../database.php';

 
?>

     <div class="kt-mainpanel">
      <div class="kt-pagetitle">
        <h5> Report </h5>
      </div><!-- kt-pagetitle -->

      <div class="kt-pagebody">

        <div class="card pd-20 pd-sm-40">

          <div class="form-layout">
         <form method="POST" action="btn_report.php">  
                <div class="wrapper">                                           
                <div class="panel panel-default sammacmedia">
                    <div class="panel-heading" id="div_select">
                        <center><span class="emp">Report  details</span></center><br>
                        
                        <label for="birthday">From:</label></br>
                        <input type="date"  class="textfdt" id="fdate" name="fdate">
                        <label for="birthday">To:</label>
                        <input type="date" class="textfdt" id="tdate" name="tdate">
                        <label for="birthday">Service Type</label>
                        <select type="text" class="textfdt" id="service_type" name="service_type">
                           <option value="All">All</option>
                          <!-- <option value="All">All</option> -->
                            <?php
                    $sel = "select * from service";
                    $result = mysqli_query($con, $sel);
                    $i = 1;
                    while ($row3 = mysqli_fetch_array($result)) {
                    ?>
                      <option value="<?php echo $row3["service_name"]; ?>"><?php echo $row3["service_name"]; ?>
                        
                      </option>
                    <?php } ?>
                        </select> 
                         <label for="birthday">Case Worker</label>
                             <select type="text" class="textfdt" id="case_worker" name="case_worker">
                              <option value="All">All</option>
                          <!-- <option value="All">All</option> -->
                            <?php
                    $sel_5= "select DISTINCT case_worker from client where case_worker !=''";
                    $result_5 = mysqli_query($con, $sel_5);
                    $i = 1;
                    while ($row5 = mysqli_fetch_array($result_5)) {
                    ?>
                      <option value="<?php echo $row5['case_worker']; ?>"><?php echo $row5['case_worker']; ?>
                      </option>
                    <?php } ?>
                        </select> 

                         <label for="birthday">Job Dtata</label>
                             <select type="text" class="textfdt" id="jobdata" name="jobdata">
                              <option value="All">All</option>
                          <!-- <option value="All">All</option> -->
                            <?php
                    $sel_1 = "select * from status ";
                    $result_1 = mysqli_query($con, $sel_1);
                    $i = 1;
                    while ($row4 = mysqli_fetch_array($result_1)) {
                    ?>
                      <option value="<?php echo $row4[1]; ?>"><?php echo $row4[1]; ?>
                        
                      </option>
                    <?php } ?>
                        </select> 
                        
                     

                        <center>
                    <input type="submit" name="btn_rpt" value="Generate PDF Report">
                </form>
                  </div><!-- form-layout-footer -->
                </div><!-- col-8 -->
              </div>
            </div><!-- card -->
          </div><!-- col-6 -->
        </div><!-- row -->
      </div>
</body>
</html>

        <style>
span.emp {
    text-align: center;
    font-size: 25px;
    font-weight: 700;
    font-family: inherit;
}
 .textfd{
  width: 45%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}
.textfdt{
    width: 14%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}
input[type=submit] {
  width: 22%;
  background-color:#007bff;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
input[type=button] {
  width: 15%;
  background-color: #007bff;
  color: white;
  padding: 15px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
input[type=submit]:hover {
  background-color:#007bff;
}

.form{
  border-radius: 5px;
  background-color: #f2f2f2;
  padding: 20px;
}
</style>
