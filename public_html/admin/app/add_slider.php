<?php require_once __DIR__ . '/auth.php'; require_admin(); ?>
<?php include "header.php";
include '../database.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION)) {
  header("location:login.php");
}
?>

<div class="kt-mainpanel">
  <div class="kt-pagetitle">
    <h5>Add Slider</h5>
  </div><!-- kt-pagetitle -->

  <div class="kt-pagebody">

    <div class="card pd-20 pd-sm-40">

      <div class="form-layout">
        <form action="upload_image.php" method="post" enctype="multipart/form-data">
          <div class="col-xl-10 mg-t-25 mg-xl-t-0">
            <div class="card pd-20 pd-sm-40 form-layout form-layout-5">

              <div class="row row-xs">
                <label class="col-sm-4 form-control-label"><span class="tx-danger">*</span>Add image:</label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">

                  <input type="file" name="choosefile" value="" />

                  <div>

                  </div>
                </div>
              </div><!-- row -->
              <br><br>
              <input type="submit" name="uploadfile" class="btn btn-success" value="Upload ">
        </form>
      </div><!-- form-layout-footer -->

      <div class="table-wrapper">
        <table id="datatable1" class="table display responsive nowrap">
          <thead>
            <tr>
              <th>Id</th>
              <th>Image</th>
              <th>Action</th>
            </tr>
          </thead>
          <?php
          $sel = "select * from slider";
          $result = mysqli_query($con, $sel);
          $i = 1;
          while ($row = mysqli_fetch_array($result)) {
          ?>
            <tr>
              <td><?php echo $row["id"]; ?></td>
              <td><img src="<?php echo $row["filename"]; ?>" width="120px" height="120px"> </td>
              <td><button type="button" class="btn btn-danger bk-btn-sm" onclick="deleteSlider(<?php echo $row['id']; ?>)">Delete</button> </td>
            </tr>
          <?php
          }
          ?>
        </table>
      </div>

    </div><!-- col-8 -->
  </div>
</div>

<script type="text/javascript">
  function deleteSlider(id) {

    bkConfirm("Delete this item?", "This cannot be undone.", "Delete", true).then(function (ok) { if (ok) window.location.href = "delete_slider.php?id=" + id; });

  }
</script>

</body>

</html>