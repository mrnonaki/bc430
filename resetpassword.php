<?php
    require 'config.php';
    require 'header.php';

    if (isset($_GET['token'])) {
        $token = isset($_GET['token']) ? $_GET['token'] : NULL;
		$posid = strpos($token, '-');
        $id = substr($token, 0, $posid);
        $sql = 'SELECT * FROM customer WHERE id = \''.$id.'\'';
        $result = $conn->query($sql);
        if ($result->num_rows != 0) {
            $row = $result->fetch_assoc();
            if ($token == $row['id'].'-'.md5($row['id'].$row['username'].$row['email'])) {
                echo '
<hr><h2 class="text-center">ตั้งรหัสผ่านใหม่</h2><hr>
<div class="container">
    <div>
        <form id="reset" method="post" autocomplete="off">
            <table class="table table-borderless text-center">
                <tr>
                    <td width="50%" class="text-right"><label>รหัสผ่านใหม่</label></td>
                    <td width="50%" class="text-left"><input name="password" type="password"></td>
                </tr>
                <tr>
                    <td width="50%" class="text-right"><label>รหัสผ่านใหม่อีกครั้ง</label></td>
                    <td width="50%" class="text-left"><input name="passwordagain" type="password"></td>
                </tr>
                <tr>
                    <td width="50%" class="text-right"><button name="submit" type="submit" class="btn btn-success">ยืนยัน</button></td>
                    <td width="50%" class="text-left"><button type="button" class="btn btn-warning" onclick="window.location.reload();">ล้างค่า</button></td>
                </tr>
            </table>
        </form>
    </div>
</div>
                ';
            } else {
                echo '<script>alert("token ไม่ถูกต้อง")</script>';
            }
        }
    }

    if (isset($_POST['submit'])) {
        $password = isset($_POST['password']) ? $_POST['password'] : NULL;
        $passwordagain = isset($_POST['passwordagain']) ? $_POST['passwordagain'] : NULL;
        if ($password == $passwordagain) {
            $sql = 'UPDATE customer SET password = \''.md5($password).'\' WHERE id = \''.$id.'\'';
            $result = $conn->query($sql);
            if ($result) {
                echo '<script>alert("ระบบบันทึกข้อมูลเรียบร้อยแล้ว")</script>';
            }
        } else {
            echo '<script>alert("รหัสผ่านที่กรอกไม่เหมือนกัน")</script>';
        }
    }

    require 'footer.php';
?>