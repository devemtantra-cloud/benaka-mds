<?php require_once __DIR__ . '/auth.php'; require_admin(); ?>
<?php
include '../database.php';
require_once __DIR__ . '/ui.php';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (isset($_POST['sub'])) {
	$uname = trim($_POST['fname']);
	$psw = $_POST['pass'];
	$ok = false;
	if ($uname === '') {
		$msg = 'Username is required';
	} elseif ($psw !== '' && !password_column_fits_hash($con)) {
		$msg = 'Run migration.sql before changing passwords';
	} else {
		try {
			if ($psw === '') {
				// Blank password field keeps the current password.
				db_query($con, "UPDATE `login` SET `uname` = ? WHERE `id` = ?", 'si', [$uname, $id]);
			} else {
				db_query($con, "UPDATE `login` SET `uname` = ?, `password` = ? WHERE `id` = ?", 'ssi', [$uname, password_hash($psw, PASSWORD_DEFAULT), $id]);
			}
			$ok = true;
			$msg = $psw === '' ? 'User updated' : 'User updated — new password saved';
		} catch (mysqli_sql_exception $e) {
			$msg = 'Not saved — that username is already taken';
		}
	}
	done($msg, 'add_admin.php', $ok ? 'success' : 'error');
}

include "header.php";

$stmt = mysqli_prepare($con, "SELECT `uname` FROM `login` WHERE `id` = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);
$name = $row ? $row['uname'] : '';
	?>
	<!-- html-->
		<!doctype html>
<html lang="en">
  <head>
  	<title>Login 10</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" href="css_f/style.css">

	</head>
	<body class="img js-fullheight" style="background-color:#3b6998;">
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-5">
					<h2 class="heading-section">Change Passsword </h2>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-4">
					<div class="login-wrap p-0">
		      	
		      	<form action="" method='post' class="signin-form">
		      		<div class="form-group">
		      			<input type="text" class="form-control" name="fname" placeholder="Username" value="<?php echo htmlspecialchars($name); ?>" required>
		      		</div>
	            <div class="form-group">
	              <input id="password-field" type="password" name="pass" class="form-control" value="" placeholder="New password (leave blank to keep current)" autocomplete="new-password">
	              <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
	            </div>
	            <div class="form-group">
	            	<button type="submit" name="sub" class="form-control btn btn-primary submit px-3">Change</button>
	            </div>
	            <div class="form-group d-md-flex">
	            	<div class="w-50">
		            	<!-- <label class="checkbox-wrap checkbox-primary">Remember Me
									  <input type="checkbox" checked>
									  <span class="checkmark"></span>
									</label>
								</div>
								<div class="w-50 text-md-right">
									<a href="#" style="color: #fff">Forgot Password</a>
								</div> -->
	            </div>
	          </form>
		      </div>
				</div>
			</div>
		</div>
	</section>

  <script src="js_f/main.js"></script>

	</body>
</html>


	<!-- end -->