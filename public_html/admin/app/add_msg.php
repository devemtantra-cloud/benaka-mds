<?php require_once __DIR__ . '/auth.php'; require_admin(); ?>
<?php include "header.php"; 
include '../database.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$branch_id=$_SESSION["branch"];
if(!isset($_SESSION['uname'])) 
{
    echo "<script>window.location='login.php';</script>";
}

?>
<style type="text/css">
textarea {
    overflow: auto;
    width: 150px;
    height: 33vh;
    resize: vertical;
}


</style>
     <div class="kt-mainpanel">
      <div class="kt-pagetitle">
        <h5>Add Message </h5>
      </div><!-- kt-pagetitle -->

      <div class="kt-pagebody">

        <div class="card pd-20 pd-sm-40">

          <div class="form-layout">
          <form action="add_msg_t.php" method="post">
          <div class="col-xl-6 mg-t-25 mg-xl-t-0">
            <div class="card pd-20 pd-sm-40 form-layout form-layout-5">
              <h6 class="card-body-title"></h6>
             
                
              <div class="row row-xs">
                <label class="col-sm-4 form-control-label"><span class="tx-dangerspan">Service Name</label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <select name="sr_type" id="sr_type"  pattern="[A-Za-z]+" title="letters only" required  class="form-control select2" data-placeholder="select Service">
                    <option>Select Service</option>
                    <?php
                    $sel = "select * from service";
                    $result = mysqli_query($con, $sel);
                    $i = 1;
                    while ($row = mysqli_fetch_array($result)) {
                    ?>
                      <option value="<?php echo $row["service_name"]; ?>"><?php echo $row["service_name"]; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div><!-- row -->
            
              <div class="row row-xs mg-t-20">
                <label class="col-sm-4 form-control-label"><span class="tx-danger">*</span> Message </label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <textarea  name="msg"   id='theText'  style="width:103%;"> </textarea>
                 
               
                  
                </div>
              </div>
 

           
              <div class="row row-xs mg-t-30">
                <div class="col-sm-8 mg-l-auto">
                  <div class="form-layout-footer">
                    <button class="btn btn-default mg-r-5" name="add" >Update</button>
                    <button class="btn btn-secondary">Cancel</button>
                  </form>
                  </div>
                  </div><!-- form-layout-footer -->
                </div><!-- col-8 -->
              </div>
            </div><!-- card -->
          </div><!-- col-6 -->
        </div><!-- row -->
      </div>
   
</body>
</html>
