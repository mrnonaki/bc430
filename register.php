<?php
    require 'config.php';
    require 'header.php';

    if (isset($_COOKIE['registed'])) {
        header("Location: /login.php",TRUE,301);
    }
    if (isset($_POST['submit'])) {
        $username = isset($_POST['username']) ? $_POST['username'] : NULL;
        $password = isset($_POST['password']) ? $_POST['password'] : NULL;
        $passwordagain = isset($_POST['passwordagain']) ? $_POST['passwordagain'] : NULL;
        $name = isset($_POST['name']) ? $_POST['name'] : NULL;
        $tel = isset($_POST['tel']) ? $_POST['tel'] : NULL;
        $email = isset($_POST['email']) ? $_POST['email'] : NULL;
        $idth = isset($_POST['idth']) ? $_POST['idth'] : NULL;
        $address_no = isset($_POST['address_no']) ? $_POST['address_no'] : NULL;
        $address_district = isset($_POST['address_district']) ? $_POST['address_district'] : NULL;
        $address_amphoe = isset($_POST['address_amphoe']) ? $_POST['address_amphoe'] : NULL;
        $address_province = isset($_POST['address_province']) ? $_POST['address_province'] : NULL;
        $address_zipcode = isset($_POST['address_zipcode']) ? $_POST['address_zipcode'] : NULL;
        
        if (!$username || !$password || !$passwordagain || !$name || !$tel || !$email ||
        !$idth || !$address_no || !$address_district || !$address_amphoe || !$address_province || !$address_zipcode) {
            echo '<script>alert("ระบบไม่สามารถบันทึกข้อมูลได้ : กรอกข้อมูลไม่ครบถ้วน")</script>';
        } else {
            if ($password == $passwordagain) {
                $sqlpass = md5($password);
                $sql = 'SELECT * FROM customer WHERE username = \''.$username.'\'';
                $result = $conn->query($sql);
                if ($result->num_rows != 0) {
                    echo '<script>alert("ระบบไม่สามารถบันทึกข้อมูลได้ : ชื่อผู้ใช้ซ้ำในระบบ")</script>';
                } else {
                    if (strlen($username) > 20 || strlen($email) > 50 || mb_strlen($name) > 50 || strlen($tel) < 9 || strlen($tel) > 10 || strlen($idth) != 13) {
                        echo '<script>alert("ระบบไม่สามารถบันทึกข้อมูลได้ : กรอกข้อมูลไม่ถูกต้อง")</script>';
                    } else {
                        $sql = 'INSERT INTO customer (username, password, email, name, address_no, address_district, address_amphoe, address_province, address_zipcode, tel, idth, register, ban)'
                        .' VALUES (\''.$username.'\', \''.$sqlpass.'\', \''.$email.'\', \''.$name.'\', \''.$address_no.'\', \''.$address_district.'\', \''.$address_amphoe.'\', \''.$address_province.'\', \''.$address_zipcode.'\', \''.$tel.'\', \''.$idth.'\', \''
                        .date("Y-m-d H:i:s", time()).'\', \'0\')';
                        $result = $conn->query($sql);
                        if ($result) {
                            echo '<script>alert("ระบบบันทึกข้อมูลเรียบร้อยแล้ว")</script>';
                            setcookie('registed', '1', time() + 86400, '/');
                        }
                    }
                }
            } else {
                echo '<script>alert("ระบบไม่สามารถบันทึกข้อมูลได้ : รหัสผ่านที่กรอกไม่เหมือนกัน")</script>';
            }
        }

    }
?>
<hr><h2 class="text-center">สมัครสมาชิก</h2><hr>
<div class="container">
    <div>
        <form id="register" method="post" autocomplete="off">
            <table class="table table-borderless text-center">
                <tr>
                    <td width="50%" class="text-right"><label>ชื่อผู้ใช้</label></td>
                    <td width="50%" class="text-left"><input required name="username" required type="text" min="1" max="20" placeholder="อังกฤษ/ตัวเลข ไม่เกิน 20 ตัวอักษร" value="<?php echo $username;?>">*</td>
                </tr>
                <tr>
                    <td width="50%" class="text-right"><label>รหัสผ่าน</label></td>
                    <td width="50%" class="text-left"><input required name="password" placeholder="กรุณากรอกอย่างน้อย 8-16 ตัว" required type="password" placeholder="">*</td>
                </tr>
                <tr>
                    <td width="50%" class="text-right"><label>รหัสผ่านอีกครั้ง</label></td>
                    <td width="50%" class="text-left"><input required name="passwordagain" placeholder="กรุณากรอกให้ตรงกับรหัสผ่าน" required type="password" placeholder="">*</td>
                </tr>
                <th colspan=2></th>
                <tr>
                    <td width="50%" class="text-right"><label>ชื่อ - นามสกุล</label></td>
                    <td width="50%" class="text-left"><input required name="name" type="text" required placeholder="ไม่เกิน 50 ตัวอักษร" value="<?php echo $name;?>">*</td>
                </tr>
                <tr>
                    <td width="50%" class="text-right"><label>เบอร์โทร</label></td>
                    <td width="50%" class="text-left"><input required name="tel" type="tel" required minlength="9" maxlength="10" placeholder="ตัวเลขไม่เกิน 10 หลัก" value="<?php echo $tel;?>">*</td>
                </tr>
                <tr>
                    <td width="50%" class="text-right"><label>อีเมล</label></td>
                    <td width="50%" class="text-left"><input required name="email" type="email" required placeholder="sample@gmail.com" value="<?php echo $email;?>">*</td>
                </tr>
                <tr>
                    <td width="50%" class="text-right"><label>หมายเลขบัตรประชาชน</label></td>
                    <td width="50%" class="text-left"><input required name="idth" type="number" required placeholder="เลขบัตรประชาชน 13 หลัก" value="<?php echo $idth;?>">*</td>
                </tr>
                <th colspan=2></th>
                <tr>
                    <td width="50%" class="text-right"><label>บ้านเลขที่</label></td>
                    <td width="50%" class="text-left"><input required name="address_no" type="text" required placeholder="" value="<?php echo $address_no;?>">*</td>
                </tr>
                <tr>
                    <td width="50%" class="text-right"><label>ตำบล / แขวง</label></td>
                    <td width="50%" class="text-left"><input required name="address_district" type="text" required placeholder="" value="<?php echo $address_district;?>">*</td>
                </tr>
                <tr>
                    <td width="50%" class="text-right"><label>อำเภอ / เขต</label></td>
                    <td width="50%" class="text-left"><input required name="address_amphoe" type="text" required placeholder="" value="<?php echo $address_amphoe;?>">*</td>
                </tr>
                <tr>
                    <td width="50%" class="text-right"><label>จังหวัด</label></td>
                    <td width="50%" class="text-left"><input required name="address_province" type="text" required placeholder="" value="<?php echo $address_province;?>">*</td>
                </tr>
                <tr>
                    <td width="50%" class="text-right"><label>รหัสไปรษณีย์</label></td>
                    <td width="50%" class="text-left"><input required name="address_zipcode" minlength="5" maxlength="5" type="number" required placeholder="" value="<?php echo $address_zipcode;?>">*</td>
                </tr>
                <th colspan=2></th>
                <tr>
                    <td width="50%" class="text-right"><button name="submit" type="submit" class="btn btn-success">ยืนยัน</button></td>
                    <td width="50%" class="text-left"><button type="button" class="btn btn-warning" onclick="window.location.reload();">ล้างค่า</button></td>
                </tr>
            </table>
        </form>
    </div>
</div>
<script src="js/JQL.min.js"></script>
<script src="js/typeahead.bundle.js"></script>
<script src="js/jquery.Thailand.min.js"></script>
<script>
    $.Thailand({
        database: 'js/db.json',
        $district: $('#register [name="address_district"]'),
        $amphoe: $('#register [name="address_amphoe"]'),
        $province: $('#register [name="address_province"]'),
        $zipcode: $('#register [name="address_zipcode"]'),
    });
</script>
<?php
    require 'footer.php';
?>