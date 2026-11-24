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
        <h5>Add Font style</h5>
      </div><!-- kt-pagetitle -->

      <div class="kt-pagebody">

        <div class="card pd-20 pd-sm-40">

          <div class="form-layout">
          <form action="ins_font.php" method="post">
          <div class="col-xl-12 mg-t-25 mg-xl-t-0">
            <div class="card pd-20 pd-sm-40 form-layout form-layout-5">
              <h6 class="card-body-title">Add Font style</h6>
              <h2 id="chng" style="text-align:center;">YOUR TEXT STYLE </h2>
             <div class="row row-xs mg-t-20">
              
                      <label class="col-sm-4 form-control-label"><span class="tx-danger">*</span>Font Style</label>
                       <div class="col-sm-8 mg-t-10 mg-sm-t-0"> 
                        <select onchange ="change()" name="style" id="style" class="form-control select2" value=""  data-placeholder="select Service">
                            <option value="">Select Font Family</option>
                            <option value="monospace">monospace</option>
                            <option value="TimesNewRoman">TimesNewRoman</option>
                            <option value="cursive">cursive</option>
                            <option value="emoji">emoji</option>
                            <option value="fangsong">fangsong</option>
                            <option value="fantasy">fantasy</option>
                            <option value="inherit">inherit</option>
                            <option value="initial">initial</option>
                            <option value="math">math</option>
                            <option value="sans-serif">sans-serif</option>
                            <option value="serif">serif</option>
                        </select>
</div>
                    </div>
             <!--        
               <div class="row row-xs">
                <label class="col-sm-4 form-control-label"><span class="tx-danger">*</span>Headng</label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <input type="text"  id="Headng" name="Headng"  class="form-control"   style="margin-top: 23px;" value="testing testing testing">
                </div>
              </div> -->
                      
               
              
                    <br>
                    <br>
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
              </form>
                  </div><!-- form-layout-footer -->

                  <!-- form-layout-footer -->
                  <div class="table-wrapper">
                  <table id="datatable1" class="table display responsive nowrap">
                   <thead>
                      <tr>
                          <th>page Id</th>
                          <th>Font family</th>  
                          <th>Font Style</th>  
                      </tr>
                  </thead>
                  <?php
                  $sel="SELECT * FROM `font`";
                   $result = mysqli_query($con,$sel);
                  $i=1;
                  while($row = mysqli_fetch_array($result)) 
                  {
                   $id= $row[0];
                   $page= $row[1];
                   
                     
                  ?>
                   <tr>
                  <td><?php echo $id;?></td>
                  <td><?php echo $page;?></td>
                  <td style="font-family: <?php echo  $page; ?>;"> YOUR TEXT STYLE </td>
                 

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
                            
        
                           window.location.href = "Upadte_point.php?id="+rnum;
                        }
    </script>
    <script type="text/javascript">
      function change(){
         var san =document.getElementById('style').value;
        document.getElementById('chng').style.fontFamily=san;
      }
    </script>