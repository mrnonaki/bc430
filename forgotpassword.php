<?php
    require 'config.php';
    require 'header.php';

    if (isset($_POST['submit'])) {
        $username = isset($_POST['username']) ? $_POST['username'] : NULL;
        $email = isset($_POST['email']) ? $_POST['email'] : NULL;
        if ($username && $email) {
            $sql = 'SELECT * FROM customer WHERE username = \''.$username.'\'';
            $result = $conn->query($sql);
            if ($result->num_rows != 0) {
                $row = $result->fetch_assoc();
                if ($email == $row['email']) {

                    $token = $row['id'].'-'.md5($row['id'].$row['username'].$row['email']);
                    $data = array (
                        "from" => "BC430 <no-reply@mailgun.mrnonaki.net>",
                        "to" => $row['name']." <".$row['email'].">",
                        "subject" => "BC430 - ลืมรหัสผ่าน",
                        "text" => "https://$weburl/resetpassword.php?token=$token"
                    );

                    $session = curl_init('https://api.mailgun.net/v3/mailgun.mrnonaki.net/messages');
                    curl_setopt($session, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                    curl_setopt($session, CURLOPT_USERPWD, $mailgunapi);
                    curl_setopt($session, CURLOPT_POST, true);
                    curl_setopt($session, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($session, CURLOPT_HEADER, false);
                    curl_setopt($session, CURLOPT_ENCODING, 'UTF-8');
                    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
                    $response = curl_exec($session);
                    curl_close($session);

                    echo '<script>alert("ระบบส่งอีเมล์สำหรับตั้งรหัสผ่านใหม่แล้ว")</script>';

                } else {
                    echo '<script>alert("อีเมล์ไม่ถูกต้อง")</script>';
                }
            } else {
                echo '<script>alert("ไม่พบชื่อผู้ใช้ในระบบ")</script>';
            }
        } else {
            echo '<script>alert("กรอกข้อมูลไม่ครบถ้วน")</script>';
        }
    }
?>
<hr><h2 class="text-center">ลืมรหัสผ่าน</h2><hr>
<div class="container">
    <div>
        <form id="forgot" method="post" autocomplete="off">
            <table class="table table-borderless text-center">
                <tr>
                    <td width="50%" class="text-right"><label>ชื่อผู้ใช้</label></td>
                    <td width="50%" class="text-left"><input name="username" type="text"></td>
                </tr>
                <tr>
                    <td width="50%" class="text-right"><label>อีเมล</label></td>
                    <td width="50%" class="text-left"><input name="email" type="email"></td>
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