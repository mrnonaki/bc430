<?php
    require 'config.php';
    require 'header.php';

    $sql = 'SELECT * FROM customer WHERE id = '.$_SESSION['uid'];
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $username = $row['username'];
    $name = $row['name'];
    $tel = $row['tel'];
    $email = $row['email'];
    $idth = $row['idth'];
    $address_no = $row['address_no'];
    $address_district = $row['address_district'];
    $address_amphoe = $row['address_amphoe'];
    $address_province = $row['address_province'];
    $address_zipcode = $row['address_zipcode'];

    if (isset($_POST['submit'])) {
        $name = isset($_POST['name']) ? $_POST['name'] : $row['name'];
        $tel = isset($_POST['tel']) ? $_POST['tel'] : $row['tel'];
        $email = isset($_POST['email']) ? $_POST['email'] : $row['email'];
        $address_no = isset($_POST['address_no']) ? $_POST['address_no'] : $row['address_no'];
        $address_district = isset($_POST['address_district']) ? $_POST['address_district'] : $row['address_district'];
        $address_amphoe = isset($_POST['address_amphoe']) ? $_POST['address_amphoe'] : $row['address_amphoe'];
        $address_province = isset($_POST['address_province']) ? $_POST['address_province'] : $row['address_province'];
        $address_zipcode = isset($_POST['address_zipcode']) ? $_POST['address_zipcode'] : $row['address_zipcode'];
        
        if (!$name || !$tel || !$email || !$address_no || !$address_district || !$address_amphoe || !$address_province || !$address_zipcode) {
            echo '<script>alert("ระบบไม่สามารถบันทึกข้อมูลได้ : กรอกข้อมูลไม่ครบถ้วน")</script>';
        } else {
            if (strlen($email) > 50 || mb_strlen($name) > 50 || strlen($tel) < 9 || strlen($tel) > 10) {
                echo '<script>alert("ระบบไม่สามารถบันทึกข้อมูลได้ : กรอกข้อมูลไม่ถูกต้อง")</script>';
            } else {
                $sql = 'UPDATE customer SET name = \''.$name.'\', tel = \''.$tel.'\', email = \''.$email.'\', address_no = \''.$address_no.'\', address_district = \''.$address_district.'\', address_amphoe = \''.$address_amphoe.'\', address_province = \''.$address_province.'\', address_zipcode = \''.$address_zipcode.'\' WHERE id = '.$_SESSION['uid'];
                $result = $conn->query($sql);
                if ($result) {
                    echo '<script>alert("ระบบบันทึกข้อมูลเรียบร้อยแล้ว")</script>';
                }
            }
            if ((isset($_POST['password']) && $_POST['password'] != "") && isset($_POST['passwordagain'])) {
                $password = $_POST['password'];
                $passwordagain = $_POST['passwordagain'];
                if ($password == $passwordagain) {
                    $sqlpass = md5($password);
                    $sql = 'UPDATE customer SET password = \''.$sqlpass.'\' WHERE id = '.$_SESSION['uid'];
                    $result = $conn->query($sql);
                    if ($result) {
                        echo '<script>alert("ระบบบันทึกรหัสใหม่เรียบร้อยแล้ว")</script>';
                    }
                } else {
                    echo '<script>alert("ระบบไม่สามารถบันทึกข้อมูลได้ : รหัสผ่านที่กรอกไม่เหมือนกัน")</script>';
                }
            }
        }
    }
?>
<hr><h2 class="text-center">แก้ไขสมาชิก</h2><hr>
<div class="container">
    <div>
        <form id="register" method="post" autocomplete="off">
            <table class="table table-borderless text-center">
                <tr>
                    <td width="50%" class="text-right"><label>ชื่อผู้ใช้</label></td>
                    <td width="50%" class="text-left"><input required name="username" type="text" placeholder="อังกฤษ/ตัวเลข ไม่เกิน 20 ตัวอักษร" value="<?php echo $username;?>" disabled></td>
                </tr>
                <tr>
                    <td width="50%" class="text-right"><label>รหัสผ่าน</label></td>
                    <td width="50%" class="text-left"><input name="password" type="password" placeholder=""></td>
                </tr>
                <tr>
                    <td width="50%" class="text-right"><label>รหัสผ่านอีกครั้ง</label></td>
                    <td width="50%" class="text-left"><input name="passwordagain" type="password" placeholder=""></td>
                </tr>
                <th colspan=2></th>
                <tr>
                    <td width="50%" class="text-right"><label>ชื่อ - นามสกุล</label></td>
                    <td width="50%" class="text-left"><input required name="name" type="text" placeholder="ไม่เกิน 50 ตัวอักษร" value="<?php echo $name;?>">*</td>
                </tr>
                <tr>
                    <td width="50%" class="text-right"><label>เบอร์โทร</label></td>
                    <td width="50%" class="text-left"><input required name="tel" type="tel" minlength="9" maxlength="10" placeholder="ตัวเลขไม่เกิน 10 หลัก" value="<?php echo $tel;?>">*</td>
                </tr>
                <tr>
                    <td width="50%" class="text-right"><label>อีเมล</label></td>
                    <td width="50%" class="text-left"><input required name="email" type="email" placeholder="sample@gmail.com" value="<?php echo $email;?>">*</td>
                </tr>
                <tr>
                    <td width="50%" class="text-right"><label>หมายเลขบัตรประชาชน</label></td>
                    <td width="50%" class="text-left"><input required name="idth" type="number" placeholder="เลขบัตรประชาชน 13 หลัก" value="<?php echo $idth;?>" disabled>*</td>
                </tr>
                <th colspan=2></th>
                <tr>
                    <td width="50%" class="text-right"><label>บ้านเลขที่</label></td>
                    <td width="50%" class="text-left"><input required name="address_no" type="text" placeholder="" value="<?php echo $address_no;?>">*</td>
                </tr>
                <tr>
                    <td width="50%" class="text-right"><label>ตำบล / แขวง</label></td>
                    <td width="50%" class="text-left"><input required name="address_district" type="text" placeholder="" value="<?php echo $address_district;?>">*</td>
                </tr>
                <tr>
                    <td width="50%" class="text-right"><label>อำเภอ / เขต</label></td>
                    <td width="50%" class="text-left"><input required name="address_amphoe" type="text" placeholder="" value="<?php echo $address_amphoe;?>">*</td>
                </tr>
                <tr>
                    <td width="50%" class="text-right"><label>จังหวัด</label></td>
                    <td width="50%" class="text-left"><input required name="address_province" type="text" placeholder="" value="<?php echo $address_province;?>">*</td>
                </tr>
                <tr>
                    <td width="50%" class="text-right"><label>รหัสไปรษณีย์</label></td>
                    <td width="50%" class="text-left"><input required name="address_zipcode" type="number" placeholder="" value="<?php echo $address_zipcode;?>">*</td>
                </tr>
                <th colspan=2></th>
                <tr>
                    <td width="50%" class="text-right"><button name="submit" type="submit" class="btn btn-success">ยืนยัน</button></td>
                    <td width="50%" class="text-left"><button type="button" class="btn btn-warning" onclick="window.location.reload();">คืนค่า</button></td>
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