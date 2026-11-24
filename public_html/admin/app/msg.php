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
        <h5>Add Service Messages</h5>
      </div><!-- kt-pagetitle -->

      <div class="kt-pagebody">

        <div class="card pd-20 pd-sm-40">

          <div class="form-layout">
          <!-- <form action="ins_rule.php" method="post">
          <div class="col-xl-12 mg-t-25 mg-xl-t-0">
            <div class="card pd-20 pd-sm-40 form-layout form-layout-5">
              <h6 class="card-body-title">Add Service Rules</h6>
                
             <div class="row row-xs mg-t-20">
                      <label class="col-sm-4 form-control-label"><span class="tx-danger">*</span>Menu Heading</label>
                       <div class="col-sm-8 mg-t-10 mg-sm-t-0"> 
                        <select name="menu" id="menu" class="form-control select2" value=""  data-placeholder="select Service">
                            <option value="Driving school">Driving school</option>
                            <option value="Veichle Insurance">Veichle Insurance</option>
                            <option value="Veichle Loan">Veichle Loan</option>
                            <option value="Emission Analysis">Emission Analysis</option>
                            <option value="Auto Consultancy">Auto Consultancy</option>
                         
                        </select>
                      </div>
                    </div> -->
                    
               <div class="row row-xs">
              <!--   <label class="col-sm-4 form-control-label"><span class="tx-danger">*</span>Headng</label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <input type="text"type="text" id="Headng" name="Headng"  class="form-control"   style="margin-top: 23px;">
                </div>
              </div>
              <div >
                <textarea style="display:none" name="points" id='theText' value=""></textarea>
     
                </div>
               <div class="row row-xs">
                <label class="col-sm-4 form-control-label"><span class="tx-danger">*</span>Point</label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <textarea type="textarea" onkeyup='writeText(this)' id='taYourText' type="text"  name="pointss"  class="form-control"  style="margin-top: 23px;"></textarea>
                </div>
              </div>
              
                    <br>
                   <input type="submit" name="add" class="btn btn-success"  value="Add ">
<script>
  let t = 'sample';

  let writeText = (ele) => {
    t = ele.value;
    document.getElementById('theText').innerHTML = 
      t.replace(/\n\r?/g, '~');
  }
</script>
              </form> -->
                  </div><!-- form-layout-footer -->

                  <!-- form-layout-footer -->
                  <a class="btn btn-primary" href="add_msg.php"><i class="icon ion-plus"></i> Add message</a>
                  <div class="table-wrapper">
                  <table id="datatable1" class="table display responsive nowrap">
                   <thead>
                      <tr>
                          <th>SL No</th>
                          <th>Service Name</th>
                          <th>Message</th>
                          
                          <th>Action</th>
                          <th>Action</th>
                      </tr>
                  </thead>
                  <?php
                  $sel="SELECT * FROM `msg` where type='2'";
                   $result = mysqli_query($con,$sel);
                  $i=1;
                  while($row = mysqli_fetch_array($result)) 
                  {
                   $id= $row[0];
                   $page= $row[1];
                   $heading= $row[2];    
                  ?>
                   <tr>
                  <td><?php echo $id;?></td>
                  <td><?php echo $page;?></td>
                  <td style="min-width:340px;line-height:1.5"><?php echo nl2br(htmlspecialchars(str_ireplace('%0a', "\n", (string) $heading))); ?></td>
                    <td><button type="button" class="btn bk-btn-ghost bk-btn-sm" onclick="updatecust(<?php echo $row['id']; ?>)">Edit</button> </td>
                   <td><button type="button" class="btn btn-danger bk-btn-sm" onclick="deleteAdmin(<?php echo $row['id'];?>)">Delete</button> </td>
                   
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
                           window.location.href = "msg_edit.php?id="+rnum;
                        }
    </script>