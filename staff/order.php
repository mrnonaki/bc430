<?php
    require '../config.php';
    require 'header.php';
?>
<div class="container">
    <div>
<?php
    $id = isset($_GET['id']) ? $_GET['id'] : NULL;
    $issell = $_GET['issell'] ? true : NULL;
    if ($id) {
        $sql = 'UPDATE category SET issell = \''.$issell.'\' WHERE id = '.$id;
        $result = $conn->query($sql);
        if ($result) {
            echo '<script>alert("T'.sprintf("%05d", $id).': ระบบบันทึกข้อมูลเรียบร้อยแล้ว")</script>';
        }
    }

    $post = isset($_POST['type']) ? $_POST['type'] : NULL;
    $id = isset($_POST['id']) ? $_POST['id'] : NULL;
    $name = isset($_POST['name']) ? $_POST['name'] : NULL;
    $unit = isset($_POST['unit']) ? $_POST['unit'] : NULL;
    $price = isset($_POST['price']) ? $_POST['price'] : NULL;
    $ship = isset($_POST['ship']) ? $_POST['ship'] : NULL;
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : NULL;
    $ready = isset($_POST['ready']) ? $_POST['ready'] : NULL;
    $issell = isset($_POST['issell']) ? true : NULL;

if (isset($_FILES["pic"]["name"])) {
    if (strtolower(pathinfo($_FILES["pic"]["name"],PATHINFO_EXTENSION)) == "jpg") {
        $checkUpload = 'jpg';
        if (getimagesize($_FILES["pic"]["tmp_name"])) {
            $checkUpload = 'img';
            if ($_FILES["pic"]["size"] <= 500000) {
                $checkUpload = 'ok';
            }
        }
    }
}

    if ($post == 'addCategory') {
        $sql = 'SELECT * FROM category WHERE name = \''.$name.'\'';
        $result = $conn->query($sql);
        if ($result->num_rows != 0) {
            echo '<script>alert("ระบบไม่สามารถบันทึกข้อมูลได้ : ชื่อประเภทซ้ำในระบบ")</script>';
        } else {
            if (mb_strlen($name) > 50 || mb_strlen($unit) > 10 || $price >= 1000000 || $ship >= 10000 || $checkUpload != 'ok') {
                echo '<script>alert("ระบบไม่สามารถบันทึกข้อมูลได้ : กรอกข้อมูลไม่ถูกต้อง")</script>';
            } else {
                $sql = 'INSERT INTO category (name, unit, price, ship, quantity, ready, issell)'
                .' VALUES (\''.$name.'\', \''.$unit.'\', \''.$price.'\', \''.$ship.'\', \'0\', \'0\', \'0\')';
                $result = $conn->query($sql);
                if ($result) {
                    $sql = 'SELECT * FROM category ORDER BY id DESC';
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    if (move_uploaded_file($_FILES["pic"]["tmp_name"], "../images/category/".$row['id'].".jpg")) {
                        echo '<script>alert("T'.sprintf("%05d", $row['id']).': ระบบบันทึกข้อมูลเรียบร้อยแล้ว")</script>';
                    }
                }
            }
        }
    } elseif ($post == 'editCategory') {
        if (mb_strlen($name) > 50 || mb_strlen($unit) > 10 || $price >= 1000000 || $ship >= 10000) {
            echo '<script>alert("ระบบไม่สามารถบันทึกข้อมูลได้ : กรอกข้อมูลไม่ถูกต้อง")</script>';
        } else {
            $sql = 'UPDATE category SET name = \''.$name.'\', unit = \''.$unit.'\', price = \''.$price.
            '\', ship = \''.$ship.'\', issell = \''.$issell.'\' WHERE id = '.$id;
            $result = $conn->query($sql);
            if ($result) {
                echo '<script>alert("T'.sprintf("%05d", $id).': ระบบบันทึกข้อมูลเรียบร้อยแล้ว")</script>';
                if ($checkUpload = 'ok') {
                    move_uploaded_file($_FILES["pic"]["tmp_name"], "../images/category/".$id.".jpg");
                }
            }
        }
    } elseif ($post == 'delCategory') {
        $sql = 'DELETE FROM category WHERE id = '.$id;
        $result = $conn->query($sql);
        if ($result) {
            unlink('../images/category/'.$id.'.jpg');
            echo '<script>alert("T'.sprintf("%05d", $id).': ระบบลบข้อมูลเรียบร้อยแล้ว")</script>';
        }
    }
?>
        <form method="get">
            <h1 class="text-center">ใบแจ้งหนี้</h1>
            <p class="text-center">
                <button type="button" class="btn btn-success btn-sm" onclick="loadModal('addCategory')">เพิ่ม</button>
                <input type="text" name="search" placeholder="ค้นหาประเภท"
                    value="<?php echo isset($_GET['search']) ? $_GET['search'] : '';?>"><button type="submit"
                    class="btn btn-primary btn-sm">ค้นหา</button>
            </p>
        </form>
    </div>
    <div>
        <table class="table table-striped table-bordered text-center">
            <thead>
                <tr>
                    <th scope="col" width="10%">รหัส</th>
                    <th scope="col" width="10%">รูป</th>
                    <th scope="col" width="30%">ชื่อ</th>
                    <th scope="col" width="10%">จำนวน</th>
                    <th scope="col" width="10%">หน่วยนับ</th>
                    <th scope="col" width="10%">ราคา</th>
                    <th scope="col" width="10%">สถานะ</th>
                    <th scope="col" width="10%">คำสั่ง</th>
                </tr>
            </thead>
            <tbody>
<?php
    if (isset($_GET['search'])) {
        if ($_GET['search'][0] == 'T' && is_numeric($_GET['search'][1])) {
            $search_id = (int)substr($_GET['search'], 1);
            $sql = 'SELECT * FROM category WHERE id LIKE '.$search_id.' ORDER BY id DESC';
        } else {
            $sql = 'SELECT * FROM category WHERE id LIKE "%'.$_GET['search'].'%" OR name LIKE "%'.$_GET['search'].'%" ORDER BY id DESC';
            }
    } else {
        $sql = 'SELECT * FROM category ORDER BY id DESC';
    }
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            echo '<tr>'."\n";
            echo '<th scope="row" onclick="loadModal(\'showCategory\', '.$row['id'].')">T'.sprintf("%05d", $row['id']).'</th>'."\n";
            echo '<td><img src="/images/category/'.$row['id'].'.jpg" class="img-fluid"></td>'."\n";
            echo '<td class="text-left">'.$row['name'].'</td>'."\n";
            echo '<td class="text-right">'.$row['ready'].'</td>'."\n";
            echo '<td>'.$row['unit'].'</td>'."\n";
            echo '<td class="text-right">'.number_format($row['price'], 2, '.', ',').'</td>'."\n";
            echo $row['issell'] ? "<td>พร้อมขาย</td>\n" : "<td>ไม่พร้อมขาย</td>\n";
            echo '<td>'."\n";
            echo '<div class="dropdown">'."\n";
            echo '<button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-toggle="dropdown">ทำรายการ<span class="caret"></span></button>'."\n";
            echo '<ul class="dropdown-menu">'."\n";
            echo '<li><div class="dropdown-item" onclick="loadModal(\'addProduct\', '.$row['id'].')">เพิ่มสินค้า</div></li>'."\n";
            echo '<li><div class="dropdown-item" onclick="loadModal(\'editCategory\', '.$row['id'].')">แก้ไข</div></li>'."\n";
            if ($row['issell']) {
                echo '<li><a class="dropdown-item" href="?id='.$row['id'].'&issell=0">ไม่พร้อมขาย</a></li>'."\n";
            } else {
                echo '<li><a class="dropdown-item" href="?id='.$row['id'].'&issell=1">พร้อมขาย</a></li>'."\n";
            }
            echo '<li><div class="dropdown-item" onclick="loadModal(\'delCategory\', '.$row['id'].')">ลบ</div></li>'."\n";
            echo '</ul>'."\n";
            echo '</div>'."\n";
            echo '</td>'."\n";
            echo '</tr>'."\n";
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