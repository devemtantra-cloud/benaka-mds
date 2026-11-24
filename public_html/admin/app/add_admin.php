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
        <h5>Add Branch Admin</h5>
    </div><!-- kt-pagetitle -->

    <div class="kt-pagebody">

        <div class="card pd-20 pd-sm-40">

            <div class="form-layout">
                <form action="add_admin_process.php" method="post">
                    <div class="col-md-12 mg-t-25 mg-xl-t-0">
                        <div class="card pd-20 pd-sm-40 form-layout form-layout-5">

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="uname" class="required">User name <span class="required">*</span></label> <input class="form-control" name="uname" id="uname" type="text" maxlength="100">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="utype" class="required">User type <span class="required">*</span></label>
                                        <select class="form-control" id="utype" name="utype">
                                            <option value="MA">Super admin</option>
                                            <option value="A">Admin</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="password" class="required">Password <span class="required">*</span></label> <input class="form-control" name="password" id="password" type="password" maxlength="100" autocomplete="new-password">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="branch" class="required">Branch <span class="required">*</span></label>

                                        <select class="form-control" id="branch" name="branch">
                                            <?php
                                            $sel = "select * from branch";
                                            $result = mysqli_query($con, $sel);
                                            $i = 1;
                                            while ($row = mysqli_fetch_array($result)) {
                                            ?>
                                                <option value="<?php echo $row["branch_id"]; ?>"><?php echo $row["branch_name"]; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <input type="submit" class="btn btn-success" value="Submit">
                        </div>
                    </div>
                </form>


                <div class="table-wrapper">
                    <table id="datatable1" class="table display responsive nowrap">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>User Name</th>
                                <th>User Type</th>
                                <th>Branch</th>
                                <th>Action</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <?php
                        $sel = "select * from login";
                        $result = mysqli_query($con, $sel);
                        $i = 1;
                        while ($row = mysqli_fetch_array($result)) {
                            $id = $row['branch'];
                            if ($id) {
                                $query = "select * from branch where branch_id='$id'";
                                $res = mysqli_query($con, $query);
                                $row1 = mysqli_fetch_row($res);
                        ?>
                                <tr>
                                    <td><?php echo $row["id"]; ?></td>
                                    <td><?php echo $row["uname"]; ?></td>
                                    <td><?php echo $row["utype"]; ?></td>
                                    <td><?php echo $row1[3]; ?></td>
                                    <td><button type="button" class="btn bk-btn-ghost bk-btn-sm" onclick="editAdmin(<?php echo $row['id'];?>)">Change Password</button> </td>
                                    <td><button type="button" class="btn btn-danger bk-btn-sm" onclick="deleteAdmin(<?php echo $row['id']; ?>)">Delete</button> </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </table>
                </div>



            </div><!-- form-layout-footer -->
        </div><!-- col-8 -->
    </div>
</div>

<script type="text/javascript">
    function deleteAdmin(id) {

        bkConfirm("Delete this item?", "This cannot be undone.", "Delete", true).then(function (ok) { if (ok) window.location.href = "delete_admin.php?id=" + id; });

    }

    function editAdmin(id) {

        window.location.href = "edit_admin.php?id=" + id;

    }
</script>

</body>

</html>