<?php require_once __DIR__ . '/auth.php'; require_admin(); ?>
<?php include "header.php"; 
include '../database.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$branch_id=$_SESSION["branch"];
if(!isset($_SESSION['uname'])) 
{
    echo "<script>window.location='login.php';</script>";
}
$id = $_GET['id'];
?>
     <div class="kt-mainpanel">
      <div class="kt-pagetitle">
        <h5>Update Customer</h5>
      </div><!-- kt-pagetitle -->

      <div class="kt-pagebody">

        <div class="card pd-20 pd-sm-40">

          <div class="form-layout">
          <form action="update_pnt.php" method="post">
          <div class="col-xl-6 mg-t-25 mg-xl-t-0">
            <div class="card pd-20 pd-sm-40 form-layout form-layout-5">
              <h6 class="card-body-title">Update Customer</h6>
              <?php
              $result = mysqli_query($con,"SELECT * FROM rules where id='$id'");
                $i=1;
                while($row = mysqli_fetch_array($result)) {
                   $points= $row["points"];  
                    
              ?>
               <input type="hidden" value="<?php echo $row['id']; ?>" id="id" name="id">.
                
              <div class="row row-xs">
                <label class="col-sm-4 form-control-label"><span class="tx-danger">*</span> Page Name :</label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <input type="text"type="text" id="name" name="pname" value="<?php echo $row['page']; ?>" onchange="namevalidate(this)" class="form-control"  style="margin-top: 23px;">
                </div>
              </div><!-- row -->
               <div class="row row-xs">
                <label class="col-sm-4 form-control-label"><span class="tx-danger">*</span> Page heading :</label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">

                  <input type="text"type="text" id="v_no" name="pheading" value="<?php echo $row['heading']; ?>" onchange="srnovalidate(this)" class="form-control"   style="margin-top: 23px;">
                </div>
              </div><!-- row -->
              <div class="row row-xs mg-t-20">
                <label class="col-sm-4 form-control-label"><span class="tx-danger">*</span> Points </label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <textarea  name="point"  style="display:none" id='theText' value=" <?php  echo $points ?>"> <?php  echo $points ?></textarea>
                 
                  <textarea name="points"  onkeyup='writeText(this)' id='taYourText'  rows="10" cols="50" class="form-control"  value="">
                   <?php  echo $points ?>
                  </textarea>
                 
                  
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
    <!-- <?php }?> -->
</body>
</html>
             <script>
  let t = 'sample';

  let writeText = (ele) => {
    t = ele.value;
    document.getElementById('theText').innerHTML = 
      t.replace(/\n\r?/g, '~');
  }
  </script>