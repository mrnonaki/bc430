<?php
require '../config.php';
require 'header.php';
?>
<div class="container">
    <div>
        <?php
        $id = isset($_GET['id']) ? $_GET['id'] : NULL;
        $ban = $_GET['ban'] ? true : NULL;
        if ($id) {
            $sql = 'UPDATE customer SET ban = \'' . $ban . '\' WHERE id = ' . $id;
            $result = $conn->query($sql);
            if ($result) {
                echo '<script>alert("C' . sprintf("%05d", $id) . ': ระบบบันทึกข้อมูลเรียบร้อยแล้ว");
                window.location.assign("customer.php");</script>';
            }
        }

        $post = isset($_POST['type']) ? $_POST['type'] : NULL;
        $id = isset($_POST['id']) ? $_POST['id'] : NULL;
        $username = isset($_POST['username']) ? $_POST['username'] : NULL;
        $password = isset($_POST['password']) ? md5($_POST['password']) : NULL;
        $email = isset($_POST['email']) ? $_POST['email'] : NULL;
        $name = isset($_POST['name']) ? $_POST['name'] : NULL;
        $address_no = isset($_POST['address_no']) ? $_POST['address_no'] : NULL;
        $address_district = isset($_POST['address_district']) ? $_POST['address_district'] : NULL;
        $address_amphoe = isset($_POST['address_amphoe']) ? $_POST['address_amphoe'] : NULL;
        $address_province = isset($_POST['address_province']) ? $_POST['address_province'] : NULL;
        $address_zipcode = isset($_POST['address_zipcode']) ? $_POST['address_zipcode'] : NULL;
        $tel = isset($_POST['tel']) ? $_POST['tel'] : NULL;
        $idth = isset($_POST['idth']) ? $_POST['idth'] : NULL;
        $register = isset($_POST['register']) ? $_POST['register'] : NULL;
        $ban = isset($_POST['ban']) ? true : NULL;

        if ($post == 'addCustomer') {
            $email2username = substr($email, 0, strpos($email, '@'));
            $idth2password = md5($idth);
            $sql = 'SELECT * FROM customer WHERE username = \'' . $email2username . '\'';
            $result = $conn->query($sql);
            if ($result->num_rows != 0) {
                $sql = 'SELECT * FROM customer';
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    if ($row['username'] == $email2username) {
                        echo '<script>alert("ระบบไม่สามารถบันทึกข้อมูลได้ : ชื่อผู้ใช้ซ้ำในระบบ")</script>';
                        break;
                    } elseif ($row['email'] == $email) {
                        echo '<script>alert("ระบบไม่สามารถบันทึกข้อมูลได้ : อีเมล์ซ้ำในระบบ")</script>';
                        break;
                    } elseif ($row['idth'] == $idth) {
                        echo '<script>alert("ระบบไม่สามารถบันทึกข้อมูลได้ : เลขบัตรประชาชนซ้ำในระบบ")</script>';
                        break;
                    }
                }
                echo '<script>alert("ระบบไม่สามารถบันทึกข้อมูลได้ : ข้อมูลซ้ำซ้อน")</script>';
            } else {
                if (strlen($email2username) > 20 || strlen($email) > 50 || mb_strlen($name) > 50 || strlen($tel) < 9 || strlen($tel) > 10 || strlen($idth) != 13) {
                    echo '<script>alert("ระบบไม่สามารถบันทึกข้อมูลได้ : กรอกข้อมูลไม่ถูกต้อง")</script>';
                } else {
                    $sql = 'INSERT INTO customer (username, password, email, name, address_no, address_district, address_amphoe, address_province, address_zipcode, tel, idth, register, ban)'
                        . ' VALUES (\'' . $email2username . '\', \'' . $idth2password . '\', \'' . $email . '\', \'' . $name . '\', \'' . $address_no . '\', \'' . $address_district . '\', \'' . $address_amphoe . '\', \'' . $address_province . '\', \'' . $address_zipcode . '\', \'' . $tel . '\', \'' . $idth . '\', \'' . date("Y-m-d H:i:s", time()) . '\', \'0\')';
                    $result = $conn->query($sql);
                    if ($result) {
                        $sql = 'SELECT * FROM customer ORDER BY id DESC';
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        echo '<script>alert("C' . sprintf("%05d", $row['id']) . ': ระบบบันทึกข้อมูลเรียบร้อยแล้ว")</script>';
                    }
                }
            }
        } elseif ($post == 'editCustomer') {
            if (/*strlen($username) > 20 || */strlen($email) > 50 || mb_strlen($name) > 50 || strlen($tel) < 9 || strlen($tel) > 10 || strlen($idth) != 13) {
                echo '<script>alert("ระบบไม่สามารถบันทึกข้อมูลได้ : กรอกข้อมูลไม่ถูกต้อง")</script>';
            } else {
                if ($password) {
                    $sql = 'UPDATE customer SET password = \'' . $password . '\', email = \'' . $email . '\', name = \'' . $name
                        . '\', address_no = \'' . $address_no . '\', address_district = \'' . $address_district . '\', address_amphoe = \'' . $address_amphoe . '\', address_province = \'' . $address_province . '\', address_zipcode = \'' . $address_zipcode . '\', tel = \'' . $tel . '\', idth = \'' . $idth . '\', ban = \'' . $ban . '\' WHERE id = ' . $id;
                    $result = $conn->query($sql);
                } else {
                    $sql = 'UPDATE customer SET email = \'' . $email . '\', name = \'' . $name
                        . '\', address_no = \'' . $address_no . '\', address_district = \'' . $address_district . '\', address_amphoe = \'' . $address_amphoe . '\', address_province = \'' . $address_province . '\', address_zipcode = \'' . $address_zipcode . '\', tel = \'' . $tel . '\', idth = \'' . $idth . '\', ban = \'' . $ban . '\' WHERE id = ' . $id;
                    $result = $conn->query($sql);
                }
                if ($result) {
                    echo '<script>alert("C' . sprintf("%05d", $id) . ': ระบบบันทึกข้อมูลเรียบร้อยแล้ว")</script>';
                }
            }
        } elseif ($post == 'delCustomer') {
            $sql = 'DELETE FROM customer WHERE id = ' . $id;
            $result = $conn->query($sql);
            if ($result) {
                echo '<script>alert("C' . sprintf("%05d", $id) . ': ระบบลบข้อมูลเรียบร้อยแล้ว")</script>';
            }
        }
        ?>
        <form method="get">
            <h1 class="text-center">จัดการลูกค้า</h1>
            <p class="text-center">
                <button type="button" class="btn btn-success btn-sm" onclick="loadModal('addCustomer')">เพิ่ม</button>
                <input type="text" name="search" placeholder="ค้นหาลูกค้า" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>"><button type="submit" class="btn btn-primary btn-sm">ค้นหา</button>
            </p>
        </form>
    </div>
    <div>
        <table class="table table-striped table-bordered text-center">
            <thead>
                <tr>
                    <th scope="col" width="10%">รหัสลูกค้า</th>
                    <th scope="col" width="10%">วันที่สมัคร</th>
                    <th scope="col" width="35%">ชื่อ-นามสกุล</th>
                   <!--  <th scope="col" width="25%">อีเมล</th> -->
                    <th scope="col" width="10%">เบอร์โทร</th>
                    <th scope="col" width="10%">สถานะ</th>
                    <th scope="col" width="10%">คำสั่ง</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_GET['search'])) {
                    if (($_GET['search'][0] == 'C' || $_GET['search'][0] == 'c') && is_numeric($_GET['search'][1])) {
                        $search_id = (int) substr($_GET['search'], 1);
                        $sql = 'SELECT * FROM customer WHERE id LIKE ' . $search_id . ' ORDER BY id DESC';
                    } else {
                        $sql = 'SELECT * FROM customer WHERE id LIKE "%' . $_GET['search'] . '%" OR username LIKE "%' . $_GET['search'] . '%" OR name LIKE "%' . $_GET['search'] . '%" OR tel LIKE "%' . $_GET['search'] . '%" OR email LIKE "%' . $_GET['search'] . '%" OR idth LIKE "%' . $_GET['search'] . '%" ORDER BY id DESC';
                    }
                } else {
                    $sql = 'SELECT * FROM customer ORDER BY id DESC';
                }
                $result = $conn->query($sql);
                if ($result) {
                    while ($row = $result->fetch_assoc()) {

                        



                        echo '<tr>' . "\n";
                        echo '<th scope="row" onclick="loadModal(\'showCustomer\', ' . $row['id'] . ')">C' . sprintf("%05d", $row['id']) . '</th>' . "\n";
                        echo '<td>' . date("d/m/Y", strtotime($row['register']."+543years")) . '</td>' . "\n";
                        echo '<td style="text-align:left;">' . $row['name'] . '</td>' . "\n";
                       // echo '<td style="text-align:left;">' . $row['email'] . '</td>';
                        echo '<td>' . $row['tel'] . '</td>' . "\n";
                        echo $row['ban'] ? "<td><font color='red'>ระงับ</font></td>\n" : "<td><font color='#4CD267'>ปกติ</font></td>\n";
                        echo '<td>' . "\n";
                        echo '<div class="dropdown">' . "\n";
                        echo '<button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-toggle="dropdown">ทำรายการ<span class="caret"></span></button>' . "\n";
                        echo '<ul class="dropdown-menu">' . "\n";
                        echo '<li><div class="dropdown-item" onclick="loadModal(\'editCustomer\', ' . $row['id'] . ')">แก้ไข</div></li>' . "\n";
                        if ($row['ban']) {
                            echo '<li><a class="dropdown-item" href="?id=' . $row['id'] . '&ban=0" onclick="if(confirm(\'ต้องการยกเลิกระงับลูกค้าใช่หรือไม่\')) return true; else return false;">ยกเลิกระงับ</a></li>' . "\n";
                        } else {
                            echo '<li><a class="dropdown-item" href="?id=' . $row['id'] . '&ban=1" onclick="if(confirm(\'ต้องการระงับลูกค้าใช่หรือไม่\')) return true; else return false;">ระงับ</a></li>' . "\n";
                        }
                        echo '<li><div class="dropdown-item" onclick="loadModal(\'delCustomer\', ' . $row['id'] . ')">ลบ</div></li>' . "\n";
                        echo '</ul>' . "\n";
                        echo '</div>' . "\n";
                        echo '</td>' . "\n";
                        echo '</tr>' . "\n";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<div id="loadModal"></div>
<?php
require 'footer.php';
?>