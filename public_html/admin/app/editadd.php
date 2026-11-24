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
        <h5>Edit branch Address</h5>
      </div><!-- kt-pagetitle -->

      <div class="kt-pagebody">

        <div class="card pd-20 pd-sm-40">

        
                  <!-- form-layout-footer -->
                  <div class="table-wrapper">
                  <table id="datatable1" class="table display responsive nowrap">
                   <thead>
                      <tr>
                          <th>Branch Id</th>
                          <th> Name</th>
                          <th>Branch Address</th>
                          <th>Branch Name</th>
                          <th>Phone</th>
                          <th>Whatsapp </th>
                          <th>Action</th>
                      </tr>
                  </thead>
                  <?php
                  $sel="SELECT * FROM `branch`";
                   $result = mysqli_query($con,$sel);
                  $i=1;
                  while($row = mysqli_fetch_array($result)) 
                  {
                   $id= $row[0];
                   $page= $row[1];
                   $heading= $row[2];  
                   $points= $row[3];  
                    $arr_ph = $row[4];
                    $arr_ph1 = $row[5];
                     
                  ?>
                   <tr>
                  <td><?php echo $id;?></td>
                  <td><?php echo $page;?></td>
                  <td><?php echo $heading;?></td>
                  <td><?php echo $points;?></td>
                  <td><?php echo $arr_ph ;?></td>
                  <td><?php echo $arr_ph1 ;?></td>

                    <td><button type="button" class="btn bk-btn-ghost bk-btn-sm" onclick="updatecust(<?php echo $row[0]; ?>)">Edit</button> </td>
                   <!-- <td><button type="button" class="btn btn-danger bk-btn-sm" onclick="deleteAdmin(<?php echo $row[0];?>)">Delete</button> </td> -->
                   
                  </tr>
                  <?php 
                 
                }
                  ?>
                </table>
              </div>
              </div>
            </div><!-- card -->
          </div><!-- col-6 -->
        </div><!-- row -->
      </div>
</body>
</html>
<script type="text/javascript">
    function deleteAdmin(id) {

        bkConfirm("Delete this item?", "This cannot be undone.", "Delete", true).then(function (ok) { if (ok) window.location.href = "delete_point.php?id=" + id; });

    }
    </script>
    <script type="text/javascript">
     
         function updatecust(rnum)
                       {
                            
        
                           window.location.href = "update_addd.php?id="+rnum;
                        }
    </script>