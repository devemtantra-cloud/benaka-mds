<?php require_once __DIR__ . '/auth.php'; require_admin(); ?>
<?php include "header.php"; 
include '../database.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION))
{
    header("location:login.php"); 
}
?>
     <div class="kt-mainpanel">
      <div class="kt-pagetitle">
        <h5>Add Service</h5>
      </div><!-- kt-pagetitle -->

      <div class="kt-pagebody">

        <div class="card pd-20 pd-sm-40">

          <div class="form-layout">
          <form action="ins_branch.php" method="post">
          <div class="col-xl-6 mg-t-25 mg-xl-t-0">
            <div class="card pd-20 pd-sm-40 form-layout form-layout-5">
              <h6 class="card-body-title">Add Branch</h6>
                
              <div class="row row-xs">
                <label class="col-sm-4 form-control-label"><span class="tx-danger">*</span> Name:</label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <input type="text"type="text" id="name" name="name"  class="form-control" placeholder="Enter Branch"  style="margin-top: 23px;">
                </div>
              </div><!-- row -->
               <div class="row row-xs">
                <label class="col-sm-4 form-control-label"><span class="tx-danger">*</span>Branch_Address:</label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <input type="text"type="text" id="Branch_Address" name="Branch_Address"  class="form-control" placeholder="Enter Branch Address "  style="margin-top: 23px;">
                </div>
              </div>
               <div class="row row-xs">
                <label class="col-sm-4 form-control-label"><span class="tx-danger">*</span>Branch Name:</label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <input type="text"type="text" id="Branch_name" name="Branch_name"  class="form-control" placeholder="Enter Branch Name "  style="margin-top: 23px;">
                </div>
              </div>
               <div class="row row-xs">
                <label class="col-sm-4 form-control-label"><span class="tx-danger">*</span>Phone No:</label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <input type="text"type="text" id="Phone" name="Phone"  class="form-control" placeholder="Enter Phone no"  style="margin-top: 23px;">
                </div>
              </div>
               <div class="row row-xs">
                <label class="col-sm-4 form-control-label"><span class="tx-danger">*</span>Whats app No:</label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <input type="text"type="text" id="Whatsapp" name="Whatsapp"  class="form-control" placeholder="Enter Whatsapp No"  style="margin-top: 23px;">
                </div>
              </div>
                    <br>
                   <input type="submit" name="add" class="btn btn-success"  value="Add ">

              </form>
                  </div><!-- form-layout-footer -->
                  <div class="table-wrapper">
                  <table id="datatable1" class="table display responsive nowrap">
                   <thead>
                      <tr>
                          <th>Branch Id</th>
                          <th>Branch Name</th>
                          <th>Action</th>
                      </tr>
                  </thead>
                  <?php
                   $sel = "select * from branch";
                                            $result = mysqli_query($con, $sel);
                                            $i = 1;
                                            while ($row = mysqli_fetch_array($result)) {
                  ?>
                   <tr>
                  <td><?php echo $i;?></td>
                  <td><?php echo $row[3];?></td>
                   <td><button type="button" class="btn btn-danger bk-btn-sm" onclick="deleteAdmin(<?php echo $row[0]; ?>)">Delete</button> </td>
                  </tr>
                  <?php 
                $i++;
                  }
                  ?>
                </table>
              </div>
                </div><!-- col-8 -->
              </div>
            </div><!-- card -->
          </div><!-- col-6 -->
        </div><!-- row -->
      </div>
</body>
</html>
<script type="text/javascript">
    function deleteAdmin(id) {

        bkConfirm("Delete this item?", "This cannot be undone.", "Delete", true).then(function (ok) { if (ok) window.location.href = "delete_branchname.php?id=" + id; });

    }
    </script>