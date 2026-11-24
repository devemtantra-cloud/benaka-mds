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
        <h5>Feedback List</h5>
      </div><!-- kt-pagetitle -->

      <div class="kt-pagebody">

        <div class="card pd-20 pd-sm-40">

                  <!-- form-layout-footer -->
                  <div class="table-wrapper">
                  <table id="datatable1" class="table display responsive nowrap">
                   <thead>
                      <tr>
                          <th>page Id</th>
                          <th>customer Name</th>  
                          <th>Comment</th>  
                           <th>Rate</th>  
                            <th>Branch Name</th>
                            <th>Phone Number</th>  
                      </tr>
                  </thead>
                  <?php
                   $sel="SELECT * FROM `feeback`";
                   $result = mysqli_query($con,$sel);
                  $i=1;
                  while($row = mysqli_fetch_array($result)) 
                  {
                   $c_name= $row[1];
                   $comment= $row[2];
                   $rate= $row[3];
                   $bname= $row[5];
                   $ph= $row[6];
                   
                     
                  ?>
                   <tr>
                  <td><?php echo $i;?></td>
                  <td><?php echo $c_name;?></td>
                  <td><?php echo $comment;?></td>
                  <td><?php echo $rate;?></td>
                  <td><?php echo $bname;?></td>
                  <td><?php echo $ph;?></td>
                 
                   
                  </tr>
                  <?php 
                  $i++;
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

        bkConfirm("Delete this item?", "This cannot be undone.", "Delete", true).then(function (ok) { if (ok) window.location.href = "delete_point.php?t=feedback&id=" + id; });

    }
    </script>
    <script type="text/javascript">
     
         function updatecust(rnum)
                       {
                            
        
                           window.location.href = "Upadte_point.php?id="+rnum;
                        }
    </script>
    <script type="text/javascript">
      function change(){
         var san =document.getElementById('style').value;
        document.getElementById('chng').style.fontFamily=san;
      }
    </script>