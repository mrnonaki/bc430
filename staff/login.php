<!DOCTYPE html>
<html lang="en">

<head>
	<title>เข้าสู่ระบบ</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../css/bootstrap-4.3.1.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../css/font-awesome.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../fonts/icon-font.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../css/animate.css">
	<!--===============================================================================================-->
	<!-- <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css"> -->
	<!--===============================================================================================-->
	<!-- <link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css"> -->
	<!--===============================================================================================-->
	<!-- <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css"> -->
	<!--===============================================================================================-->
	<!-- <link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css"> -->
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../css/util.css">
	<link rel="stylesheet" type="text/css" href="../css/main.css">
	<!--===============================================================================================-->
</head>
<?php
require('../config.php');
if (!isset($_SESSION)) {
	session_start();
}

if (isset($_GET['logout'])) {

	setcookie('emp_uid', '', time() - 86400, '/');
	unset($_SESSION['emp_uid']);
	unset($_SESSION['emp_user']);
	unset($_SESSION['emp_role']);
	unset($_SESSION['emp_ban']);

	echo "<script> alert('ออกจากระบบเรียบร้อยแล้ว');
        window.location.assign('login.php');</script>";
	// header("Location: /login.php", TRUE, 301);
}

if (isset($_SESSION['emp_uid'])) {
	echo "<script>window.location.assign('index.php');</script>";
}

if (isset($_POST['submit'])) {
	$username = isset($_POST['username']) ? $_POST['username'] : NULL;
	$password = isset($_POST['password']) ? md5($_POST['password']) : NULL;
	if ($username && $password) {
		$sql = 'SELECT * FROM employee WHERE username = \'' . $username . '\'';
		$result = $conn->query($sql);
		if ($result->num_rows != 0) {
			$row = $result->fetch_assoc();
			if ($password == $row['password']) {
				if ($row['ban'] == 0) {
					$uid = $row['id'] . '-' . md5($row['id'] . $row['username'] . $row['password']);
					setcookie('emp_uid', $uid, time() + 86400, '/');
					header("Location: index.php", TRUE, 301);
				} else {
					echo '<script>alert("ไม่สามารถเข้าสู่ระบบได้ : ผู้ใช้ถูกระงับการใช้งาน");</script>';
				}
			} else {
				echo '<script>alert("ไม่สามารถเข้าสู่ระบบได้ : รหัสผ่านไม่ถูกต้อง")</script>';
			}
		} else {
			echo '<script>alert("ไม่สามารถเข้าสู่ระบบได้ : ไม่พบชื่อผู้ใช้ในระบบ")</script>';
		}
	} else {
		echo '<script>alert("ไม่สามารถเข้าสู่ระบบได้ : กรอกข้อมูลไม่ครบถ้วน")</script>';
	}
}
?>

<body>
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100 p-l-85 p-r-85 p-t-55 p-b-55">
				<form class="login100-form validate-form flex-sb flex-w" action="" method="POST">
					<span class="login100-form-title p-b-32">
						Staff Login
					</span>

					<span class="txt1 p-b-11">
						Username
					</span>
					<div class="wrap-input100 validate-input m-b-36" data-validate="Username is required">
						<input class="input100" autocomplete="off" type="text" name="username">
						<span class="focus-input100"></span>
					</div>

					<span class="txt1 p-b-11">
						Password
					</span>
					<div class="wrap-input100 validate-input m-b-12" data-validate="Password is required">
						<input class="input100" type="password" name="password">
						<span class="focus-input100"></span>
					</div>

					<!-- <div class="flex-sb-m w-full p-b-48">
						<div class="contact100-form-checkbox">
							<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
							<label class="label-checkbox100" for="ckb1">
								Remember me
							</label>
						</div>

						<div>
							<a href="#" class="txt3">
								Forgot Password?
							</a>
						</div>
					</div> -->

					<div class="container-login100-form-btn" style="margin-left:20px;">
						<button class="login100-form-btn" name="submit">
							เข้าสู่ระบบ
						</button>&nbsp&nbsp
						<button class="login100-form-btn" type="reset">
							ล้างค่า
						</button>
					</div>

				</form>
			</div>
		</div>
	</div>

	<div id="dropDownSelect1"></div>
</body>

</html>