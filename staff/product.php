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
            $sql = 'UPDATE category SET issell = \'' . $issell . '\' WHERE id = ' . $id;
            $result = $conn->query($sql);
            if ($result) {
                echo '<script>alert("C' . sprintf("%05d", $id) . ': ระบบบันทึกข้อมูลเรียบร้อยแล้ว")</script>';
            }
        }

        $post = isset($_POST['type']) ? $_POST['type'] : NULL;
        $id = isset($_POST['id']) ? $_POST['id'] : NULL;
        $idnew = isset($_POST['idnew']) ? $_POST['idnew'] : NULL;
        $category = isset($_POST['category']) ? $_POST['category'] : NULL;
        $datein = isset($_POST['datein']) ? $_POST['datein'] : NULL;
        $status = isset($_POST['status']) ? $_POST['status'] : NULL;

        if ($post == 'addProduct') {
            $ids = explode("\n", str_replace("\r", "", $id));
            foreach ($ids as $id) {
                $sql = 'SELECT * FROM product WHERE id = \'' . $id . '\'';
                $result = $conn->query($sql);
                if ($result->num_rows != 0) {
                    echo '<script>alert("ระบบไม่สามารถบันทึกข้อมูลได้ : ' . $id . ' ซ้ำในระบบ")</script>';
                } else {
                    if (strlen($id) > 20) {
                        echo '<script>alert("ระบบไม่สามารถบันทึกข้อมูลได้ : กรอกข้อมูลไม่ถูกต้อง")</script>';
                    } else {
                        $sql = 'INSERT INTO product (id, category, datein, status)'
                            . ' VALUES (\'' . $id . '\', \'' . $category . '\', \'' . date("Y-m-d H:i:s", time()) . '\', \'1\')';
                        $result = $conn->query($sql);
                        if ($result) {
                            echo '<script>alert("' . $id . ': ระบบบันทึกข้อมูลเรียบร้อยแล้ว")</script>';
                        }
                    }
                }
            }
            include 'stockupdate.php';
        } elseif ($post == 'editProduct') {
            $sql = 'SELECT * FROM product WHERE id = \''.$idnew.'\'';
            $result = $conn->query($sql);
            if ($result->num_rows) {
                echo '<script>alert("' . $idnew . ': ซ้ำในระบบ")</script>';
            } else {
                $sql = 'UPDATE product SET id = \'' . $idnew . '\' WHERE id = \'' . $id . '\'';
                $result = $conn->query($sql);
                if ($result) {
                    echo '<script>alert("' . $id . ': ระบบอัพเดทข้อมูลเรียบร้อยแล้ว")</script>';
                }
            }
        } elseif ($post == 'delProduct') {
            $sql = 'SELECT * FROM product WHERE id = \'' . $id . '\'';
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $category = $row['category'];
            $sql = 'DELETE FROM product WHERE id = \'' . $id . '\'';
            $result = $conn->query($sql);
            if ($result) {
                echo '<script>alert("' . $id . ': ระบบลบข้อมูลเรียบร้อยแล้ว")</script>';
            }
            include 'stockupdate.php';
        }
        ?>
        <form method="get">
            <h1 class="text-center">จัดการสินค้า</h1>
            <p class="text-center">
                <button type="button" class="btn btn-success btn-sm" onclick="loadModal('addProduct')">เพิ่ม</button>
                <input type="text" name="search" placeholder="ค้นหาสินค้า" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>"><button type="submit" class="btn btn-primary btn-sm">ค้นหา</button>
            </p>
        </form>
    </div>
    <div>
        <table class="table table-striped table-bordered text-center">
            <thead>
                <tr>
                    <th scope="col" width="25%">S/N</th>
                    <th scope="col" width="20%">รหัสประเภท</th>
                    <th scope="col" width="23%">เข้าระบบ</th>
                    <th scope="col" width="15%">สถานะ</th>
                    <th scope="col" width="12%">คำสั่ง</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_GET['search'])) {
                    if ($_GET['search'][0] == 'T' && is_numeric($_GET['search'][1])) {
                        $search_id = (int) substr($_GET['search'], 1);
                        $sql = 'SELECT * FROM product WHERE category LIKE ' . $search_id . ' ORDER BY datein DESC';
                    } else {
                        $sql = 'SELECT * FROM product WHERE id LIKE "%' . $_GET['search'] . '%" OR category LIKE "%' . $_GET['search'] . '%" ORDER BY datein DESC';
                    }
                } else {
                    $sql = 'SELECT * FROM product ORDER BY datein DESC';
                }
                $result = $conn->query($sql);
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        switch ($row['status']) {
                            case 1:
                                $status = '<font color="#4CD267">พร้อมขาย</font';
                                break;
                            case 2:
                                $status = '<font color="#FBB70F">รอชำระ</font>';
                                break;
                            case 3:
                                $status = '<font color="#C058FE">รอจัดส่ง</font>';
                                break;
                            case 4:
                                $status = '<font color="#00953A">ขายแล้ว</font>';
                                break;
                            case 5:
                                $status = '<font color="#00B2D3">เคลมเข้า</font>';
                                break;
                            case 6:
                                $status = '<font color="#F26905">เคลมออก</font>';
                                break;
                            default:
                                $status = '';
                                break;
                        }
                        echo '<tr>' . "\n";
                        echo '<th scope="row" onclick="loadModal(\'showProduct\', \'' . $row['id'] . '\')">' . $row['id'] . '</th>' . "\n";
                        echo '<td onclick="loadModal(\'showCategory\', ' . $row['category'] . ')">T' . sprintf("%05d", $row['category']) . '</td>' . "\n";
                        echo '<td>' . date("d/m/Y H:i", strtotime($row['datein']."+543years")) . '</td>' . "\n";
                        echo '<td>' . $status . '</td>' . "\n";
                        echo '<td>' . "\n";
                        echo '<div class="dropdown">' . "\n";
                        echo '<button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-toggle="dropdown">ทำรายการ<span class="caret"></span></button>' . "\n";
                        echo '<ul class="dropdown-menu">' . "\n";
                        echo '<li><div class="dropdown-item" onclick="loadModal(\'editProduct\', \'' . $row['id'] . '\')">แก้ไข</div></li>' . "\n";
                        echo '<li><div class="dropdown-item" onclick="loadModal(\'delProduct\', \'' . $row['id'] . '\')">ลบ</div></li>' . "\n";
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