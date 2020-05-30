<?php
require '../config.php';
require 'header.php';

$post = isset($_POST['type']) ? $_POST['type'] : NULL;
$id = isset($_POST['id']) ? $_POST['id'] : NULL;

if ($post == 'getPayment') {
    $payment = isset($_POST['payment']) ? $_POST['payment'] : NULL;
    $ps = isset($_POST['ps']) ? $_POST['ps'] : NULL;
    if ($payment) {
        $sql = 'SELECT * FROM invoice WHERE id =' . $id;
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $sql = 'INSERT INTO receipt (customer, invoice, date, amount, ps) VALUE (' . $row['customer'] . ', ' . $row['id'] . ', \'' . date("Y-m-d H:i:s", time()) . '\', ' . $row['amount'] . ', \'' . $ps . '\')';
        $result = $conn->query($sql);
        $sql = 'SELECT * FROM receipt ORDER BY id DESC';
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $receiptid = $row['id'];
        $sql = 'UPDATE invoice SET payment = \'' . $payment . '\', ps = \'' . $ps . '\', paydate = \'' . date("Y-m-d H:i:s", time()) . '\', receipt = ' . $receiptid . ', status = 3 WHERE id =' . $id;
        $result = $conn->query($sql);
        $sql = 'SELECT * FROM orders WHERE invoice =' . $id;
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $sql = 'UPDATE product INNER JOIN orderlist ON product.id = orderlist.product SET product.status = 3 WHERE orderlist.orders = ' . $row['id'];
            $result = $conn->query($sql);
            $sql = 'UPDATE orders SET status = 3 WHERE id = ' . $row['id'];
            $result = $conn->query($sql);
        }
        echo '<script>alert("I' . sprintf("%05d", $id) . ': ระบบบันทึกข้อมูลเรียบร้อยแล้ว")</script>';
    } else {
        echo '<script>alert("ระบบไม่สามารถบันทึกข้อมูลได้ : กรอกข้อมูลไม่ถูกต้อง")</script>';
    }
}

// if ($post == 'addCategory') {
//     $sql = 'SELECT * FROM category WHERE name = \''.$name.'\'';
//     $result = $conn->query($sql);
//     if ($result->num_rows != 0) {
//         echo '<script>alert("ระบบไม่สามารถบันทึกข้อมูลได้ : ชื่อประเภทซ้ำในระบบ")</script>';
//     } else {
//         if (mb_strlen($name) > 50 || mb_strlen($unit) > 10 || $price >= 1000000 || $ship >= 10000 || $checkUpload != 'ok') {
//             echo '<script>alert("ระบบไม่สามารถบันทึกข้อมูลได้ : กรอกข้อมูลไม่ถูกต้อง")</script>';
//         } else {
//             $sql = 'INSERT INTO category (name, unit, price, ship, quantity, ready, issell)'
//             .' VALUES (\''.$name.'\', \''.$unit.'\', \''.$price.'\', \''.$ship.'\', \'0\', \'0\', \'0\')';
//             $result = $conn->query($sql);
//             if ($result) {
//                 $sql = 'SELECT * FROM category ORDER BY id DESC';
//                 $result = $conn->query($sql);
//                 $row = $result->fetch_assoc();
//                 if (move_uploaded_file($_FILES["pic"]["tmp_name"], "../images/category/".$row['id'].".jpg")) {
//                     echo '<script>alert("T'.sprintf("%05d", $row['id']).': ระบบบันทึกข้อมูลเรียบร้อยแล้ว")</script>';
//                 }
//             }
//         }
//     }
// } elseif ($post == 'editCategory') {
//     if (mb_strlen($name) > 50 || mb_strlen($unit) > 10 || $price >= 1000000 || $ship >= 10000) {
//         echo '<script>alert("ระบบไม่สามารถบันทึกข้อมูลได้ : กรอกข้อมูลไม่ถูกต้อง")</script>';
//     } else {
//         $sql = 'UPDATE category SET name = \''.$name.'\', unit = \''.$unit.'\', price = \''.$price.
//         '\', ship = \''.$ship.'\', issell = \''.$issell.'\' WHERE id = '.$id;
//         $result = $conn->query($sql);
//         if ($result) {
//             echo '<script>alert("T'.sprintf("%05d", $id).': ระบบบันทึกข้อมูลเรียบร้อยแล้ว")</script>';
//             if ($checkUpload = 'ok') {
//                 move_uploaded_file($_FILES["pic"]["tmp_name"], "../images/category/".$id.".jpg");
//             }
//         }
//     }
// } elseif ($post == 'delCategory') {
//     $sql = 'DELETE FROM category WHERE id = '.$id;
//     $result = $conn->query($sql);
//     if ($result) {
//         unlink('../images/category/'.$id.'.jpg');
//         echo '<script>alert("T'.sprintf("%05d", $id).': ระบบลบข้อมูลเรียบร้อยแล้ว")</script>';
//     }
// }
?>
<div class="container">
    <div>
        <form method="get">
            <h1 class="text-center">รายการหนี้</h1>
            <p class="text-center">
                <button type="button" class="btn btn-success btn-sm" onclick="window.location.assign('multiple_invoice.php')">เพิ่ม</button>
                <input type="text" name="search" placeholder="ค้นหาใบแจ้งหนี้" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>"><button type="submit" class="btn btn-primary btn-sm">ค้นหา</button>
            </p>
        </form>
    </div>
    <div>
        <table class="table table-striped table-bordered text-center">
            <thead>
                <tr>
                    <th scope="col" width="15%">เลขที่ใบแจ้งหนี้</th>
                    <th scope="col" width="10%">วันที่ออก</th>
                    <th scope="col" width="25%">ชื่อ-นามสกุล</th>
                    <th scope="col" width="20%">รายการสั่งซื้อ</th>
                    <th scope="col" width="10%">ยอดรวม</th>
                    <th scope="col" width="10%">สถานะ</th>
                    <th scope="col" width="10%">คำสั่ง</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_GET['search']) && $_GET['search'] != "") {
                    if (($_GET['search'][0] == 'I' || $_GET['search'][0] == 'i') && is_numeric($_GET['search'][1])) {
                        $search_id = (int) substr($_GET['search'], 1);
                        $sql = 'SELECT DISTINCT invoice.id AS id, invoice.date AS date, customer.id AS customerid, customer.name AS customername, invoice.amount AS amount, invoice.status AS status
                         FROM invoice INNER JOIN orders ON orders.invoice = invoice.id
                         INNER JOIN customer ON orders.customer = customer.id WHERE invoice.id LIKE ' . $search_id . ' ORDER BY invoice.id DESC';
                    } else {
                        $sql = 'SELECT DISTINCT invoice.id AS id, invoice.date AS date, customer.id AS customerid, customer.name AS customername, invoice.amount AS amount, invoice.status AS status FROM invoice 
                        INNER JOIN orders ON orders.invoice = invoice.id
                        INNER JOIN customer ON orders.customer = customer.id
                        WHERE invoice.id LIKE "%' . $_GET['search'] . '%" OR customer.name LIKE "%' . $_GET['search'] . '%" ORDER BY invoice.id DESC';
                    }
                } else {
                    $sql = 'SELECT DISTINCT invoice.id AS id, invoice.date AS date, customer.id AS customerid, customer.name AS customername, invoice.amount AS amount, invoice.status AS status FROM invoice 
                        INNER JOIN orders ON orders.invoice = invoice.id
                        INNER JOIN customer ON orders.customer = customer.id ORDER BY invoice.id DESC';
                }
                $result = $conn->query($sql) or die($conn->error);
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        switch ($row['status']) {
                            case 0:
                                $status = 'ยกเลิก';
                                $statusColor = 'red';
                                break;
                            case 1:
                                $status = 'รอแจ้งชำระ';
                                $statusColor = 'FBB70F';
                                break;
                            case 2:
                                $status = 'รอตรวจสอบ';
                                $statusColor = '009AF0';
                                break;
                            case 3:
                                $status = 'ชำระแล้ว';
                                $statusColor = '07C133';
                                break;
                            default:
                                $status = '';
                                $statusColor = '';
                                break;
                        }
                        echo '<tr>' . "\n";
                        echo '<th scope="row" onclick="loadModal(\'showInvoice\', ' . $row['id'] . ')">I' . sprintf("%05d", $row['id']) . '</th>' . "\n";
                        echo '<td scope="row">' . date("d/m/Y", strtotime($row['date'] . "+543years")) . '</td>' . "\n";
                        echo '<td style="text-align:left" scope="row" onclick="loadModal(\'showCustomer\', ' . $row['customerid'] . ')">' . $row['customername'] . '</td>' . "\n";
                        echo '<td>';
                        $sqll = 'SELECT * FROM orders WHERE invoice =  "' . $row['id'] . '"';
                        $resultt = $conn->query($sqll);
                        while ($roww = $resultt->fetch_assoc()) {
                            echo '<span scope="row" onclick="loadModal(\'showOrders\', ' . $roww['id'] . ')">O' . sprintf("%05d", $roww['id']) . '</span><br>' . "\n";
                        }
                        echo '</td>';
                        echo '<td class="text-right">' . number_format($row['amount'], 2, '.', ',') . '</td>' . "\n";
                        echo '<td><font color="' . $statusColor . '">' . $status . '</font></td>' . "\n";
                        echo '<td>' . "\n";
                        echo '<div class="dropdown">' . "\n";

                        switch ($row['status']) {
                            case 0:
                                break;
                            case 1:
                                echo '<button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-toggle="dropdown">ทำรายการ<span class="caret"></span></button>' . "\n";
                                echo '<ul class="dropdown-menu">' . "\n";
                                echo '<li><div class="dropdown-item" onclick="loadModal(\'payReceipt\', ' . $row['id'] . ')">รับชำระ</div></li>' . "\n";
                                echo '<li><div class="dropdown-item" onclick="loadModal(\'voidInvoice\', ' . $row['id'] . ')">ยกเลิก</div></li>' . "\n";
                                echo '</ul>' . "\n";
                                break;
                            case 2:
                                echo '<button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-toggle="dropdown">ทำรายการ<span class="caret"></span></button>' . "\n";
                                echo '<ul class="dropdown-menu">' . "\n";
                                echo '<li><div class="dropdown-item" onclick="loadModal(\'checkPayment\', ' . $row['id'] . ')">ตรวจสอบ</div></li>' . "\n";
                                echo '</ul>' . "\n";
                                break;
                            case 3:
                                echo '<button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-toggle="dropdown">ทำรายการ<span class="caret"></span></button>' . "\n";
                                echo '<ul class="dropdown-menu">' . "\n";
                                echo '<li><div class="dropdown-item" onclick="loadModal(\'showShip\', ' . $row['id'] . ')">การจัดส่ง</div></li>' . "\n";
                                echo '</ul>' . "\n";
                                break;
                            default:
                                break;
                        }

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