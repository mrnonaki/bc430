<?php
require 'config.php';
require 'header.php';

if (isset($_GET['logout'])) {
    
    setcookie('uid', '', time() - 86400, '/');
    unset($_SESSION['uid']);
    unset($_SESSION['user']);
    unset($_SESSION['ban']);

    echo "<script> alert('ออกจากระบบเรียบร้อยแล้ว');
        window.location.assign('login.php');</script>";
    // header("Location: /login.php", TRUE, 301);
}

if (isset($_POST['submit'])) {
    $username = isset($_POST['username']) ? $_POST['username'] : NULL;
    $password = isset($_POST['password']) ? md5($_POST['password']) : NULL;
    if ($username && $password) {
        $sql = 'SELECT * FROM customer WHERE username = \'' . $username . '\'';
        $result = $conn->query($sql);
        if ($result->num_rows != 0) {
            $row = $result->fetch_assoc();
            if ($password == $row['password']) {
                $uid = $row['id'] . '-' . md5($row['id'] . $row['username'] . $row['password']);
                setcookie('uid', $uid, time() + 86400, '/');
                header("Location: /index.php", TRUE, 301);
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
<hr>
<h2 class="text-center">เข้าสู่ระบบ</h2>
<hr>
<div class="container">
    <div>
        <form id="login" method="post" autocomplete="off">
            <table class="table table-borderless text-center">
                <tr>
                    <td width="50%" class="text-right"><label>ชื่อผู้ใช้</label></td>
                    <td width="50%" class="text-left"><input name="username" type="text" value="<?php echo $username; ?>"></td>
                </tr>
                <tr>
                    <td width="50%" class="text-right"><label>รหัสผ่าน</label></td>
                    <td width="50%" class="text-left"><input name="password" type="password"></td>
                </tr>
                <tr>
                    <td colspan=2><a href="forgotpassword.php">ลืมรหัสผ่าน</a></td>
                </tr>
                <tr>
                    <td width="50%" class="text-right"><button name="submit" type="submit" class="btn btn-success">ยืนยัน</button></td>
                    <td width="50%" class="text-left"><button type="button" class="btn btn-warning" onclick="window.location.reload();">ล้างค่า</button></td>
                </tr>
            </table>
        </form>
    </div>
</div>
<?php
require 'footer.php';
?>