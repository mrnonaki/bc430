<?php
require '../config.php';
require 'header.php';
if (!isset($_SESSION)) {
    session_start();
}

$post = isset($_POST['type']) ? $_POST['type'] : NULL;
$get = isset($_GET['type']) ? $_GET['type'] : NULL;

if ($post == 'addOrder') {
    $sql = 'SELECT * FROM customer WHERE id = "' . $_POST['cus'] . '"';
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $address = $row['address_no'] . ', ' . $row['address_district'] . ', ' . $row['address_amphoe'] . ', ' . $row['address_province'] . ', ' . $row['address_zipcode'];
    $cusid = $row['id'];

    $sum_price = 0;
    $sum_ship = 0;
    foreach ($_SESSION['cart'] as $cart) {
        $sql = 'SELECT * FROM category WHERE id = ' . $cart['id'];
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        if ($cart['quantity']) {
            $sum_price += $row['price'] * $cart['quantity'];
            $sum_ship += $row['ship'] * $cart['quantity'];
        }
    }
    $sum = $sum_price + $sum_ship;

    $emp_id = (isset($_SESSION['emp_uid'])) ? $_SESSION['emp_uid'] : NULL;

    $sql = "INSERT INTO orders SET
        customer = '" . $cusid . "',
        employee = '" . $emp_id . "',
        ship     = '" . $address . "',
        total    = '" . $sum . "',
        date     = CURRENT_TIMESTAMP,
        status   = '1'";
    $result = $conn->query($sql);
    $sql = 'SELECT * FROM orders ORDER BY id DESC';
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $orderid = $row['id'];
    foreach ($_SESSION['cart'] as $cart) {
        if ($cart['quantity']) {
            $sql = 'SELECT * FROM product WHERE category = ' . $cart['id'] . ' AND status = 1 ORDER BY datein';
            $result = $conn->query($sql);
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                if ($cart['quantity'] > $i) {
                    $sqll = 'INSERT INTO orderlist (orders, product, rate, ship) VALUES (' . $orderid . ', "' . $row['id'] . '", ' . $cart['price'] . ', ' . $cart['ship'] . ')';
                    $resultt = $conn->query($sqll);
                    $sqll = 'UPDATE product SET status = 2 WHERE id = \'' . $row['id'] . '\'';
                    $resultt = $conn->query($sqll);
                    $i++;
                }
            }
            $sql = 'SELECT * FROM category WHERE id = ' . $cart['id'];
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $ready = $row['ready'] - $cart['quantity'];
            $sql = 'UPDATE category SET ready = ' . $ready . ' WHERE id = ' . $cart['id'];
            $result = $conn->query($sql);
        }
    }
    unset($_SESSION['cart']);
    echo "<script>alert('O" . sprintf("%05d", $orderid) . ":  ระบบบันทึกข้อมูลเรียบร้อยแล้ว'); window.location.assign('orderlist.php')</script>";
}

if ($post == "addInvoice") {
    $orderid = $_POST['orderid'];
    echo $orderid;

    $sql_order = "SELECT * FROM orders WHERE id = '" . $orderid . "'";
    $result_order = $conn->query($sql_order);
    $row_order = $result_order->fetch_assoc();

    $amount = $row_order['total'] - $row_order['discount'];

    $sql_invoice = 'INSERT INTO invoice SET
        employee = "' . $_SESSION['emp_uid'] . '",
        date     = CURRENT_TIMESTAMP,
        duedate  = DATE_ADD(NOW(), INTERVAL 7 DAY),
        amount   = "' . $amount . '",
        status   = "1"';

    $result_invoice = $conn->query($sql_invoice);
    $invoice_id = $conn->insert_id;

    $sql_order = "UPDATE orders SET
        invoice = '" . $invoice_id . "'
    WHERE id = '" . $orderid . "'";
    $result_order = $conn->query($sql_order) or die($conn->error);

    if ($result_invoice && $result_order) {
        echo "<script>alert('I" . sprintf("%05d", $invoice_id) . ": บันทึกใบแจ้งหนี้เรียบร้อย'); window.location.assign('orderlist.php');</script>";
    } else {
        echo "<script>alert('ออกใบแจ้งหนี้ผิดพลาด'); window.location.assign('orderlist.php')</script>";
    }
}

if ($post == "cancelInvoice" && $_POST['id']) {
    $sql_c = "UPDATE orders 
        LEFT JOIN invoice ON orders.invoice = invoice.id SET 
        orders.status   = '0',
        invoice.status  = '0' WHERE orders.id = '" . $_POST['id'] . "'";
    $result_c = $conn->query($sql_c) or die($conn->error);
    echo '<script>alert("O' . sprintf("%05d", $_POST['id']) . ': ยกเลิกการสั่งซื้อเรียบร้อย");
         window.location.assign("orderlist.php")</script>';
}

if ($post == "addDelivery" && $_POST['id']) {
    $orderid = $_POST['id'];
    // $sql = "SELECT invoice FROM orders WHERE id = '$orderid'";
    // $result = $conn->query($sql);
    // $row = $result->fetch_assoc();

    $chk = $conn->query("SELECT track FROM orders WHERE track = '" . $_POST['track'] . "'");
    if ($chk->num_rows == 0) {

        $sql_order = "UPDATE orders SET
            dateship = CURRENT_TIMESTAMP,
            track = '" . $_POST['track'] . "',
            status = '4' WHERE id = '$orderid'";

        $sql_invoice = "UPDATE invoice
        INNER JOIN orders ON orders.invoice = invoice.id
        SET invoice.status = '4' WHERE orders.id = '$orderid'";

        $sql_product = "UPDATE product 
        INNER JOIN orderlist ON orderlist.product = product.id
        SET product.status = '4' WHERE orderlist.orders = '$orderid'";

        $q_order = $conn->query($sql_order);
        // $q_invoice = $conn->query($sql_invoice);
        $sql_product = $conn->query($sql_product);

        if ($q_order && $sql_product) {
            echo "<script>alert('" . $_POST['track'] . ": บันทึกการส่งเรียบร้อย'); window.location.assign('orderlist.php');</script>";
        } else {
            echo "ERROR";
        }
    } else {
        echo "<script>alert('" . $_POST['track'] . ": หมายเลขพัสดุซ้ำ');</script>";
    }
}
?>



<div class="container">
    <div>
        <form method="get">
            <h1 class="text-center">รายการสั่งซื้อ</h1>
            <p class="text-center">
                <button type="button" class="btn btn-success btn-sm" onclick="window.location.assign('multiple_orders.php');">เพิ่ม</button>
                <input type="text" name="search" placeholder="ค้นหาการสั่งซื้อ" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>"><button type="submit" class="btn btn-primary btn-sm">ค้นหา</button>
            </p>
        </form>
    </div>
    <div>
        <table class="table table-striped table-bordered text-center">
            <thead>
                <tr>
                    <th scope="col" width="12%">รหัสการสั่งซื้อ</th>
                    <th scope="col" width="10%">วันที่สั่งซื้อ</th>
                    <th scope="col" width="18%">ลูกค้า</th>
                    <th scope="col" width="18%">พนักงาน</th>
                    <th scope="col" width="13%">ยอดรวม (บาท)</th>
                    <th scope="col" width="13%">สถานะ</th>
                    <th scope="col" width="14%">คำสั่ง</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_GET['search']) && $_GET['search'] != "") {
                    if (($_GET['search'][0] == 'O' || $_GET['search'][0] == 'o') && is_numeric($_GET['search'][1])) {
                        $search_id = (int) substr($_GET['search'], 1);

                        $sql = 'SELECT orders.id id, orders.date date, orders.status, cus.id cus_id, cus.name cus_name, 
                        emp.id emp_id, emp.name emp_name, orders.total, orders.invoice FROM orders 
                            LEFT JOIN customer AS cus ON orders.customer = cus.id
                            LEFT JOIN employee AS emp ON orders.employee = emp.id
                        WHERE orders.id = "' . $search_id . '" ORDER BY id DESC';
                    } else {
                        $sql = 'SELECT orders.id id, orders.date date, orders.status, cus.id cus_id, cus.name cus_name, 
                        emp.id emp_id, emp.name emp_name, orders.total, orders.invoice FROM orders
                            LEFT JOIN customer AS cus ON orders.customer = cus.id
                            LEFT JOIN employee AS emp ON orders.employee = emp.id
                         WHERE orders.id LIKE "%' . $_GET['search'] . '%" OR cus.name LIKE "%' . $_GET['search'] . '%" OR emp.name LIKE "%' . $_GET['search'] . '%" ORDER BY orders.id DESC';
                    }
                } else {
                    $sql = 'SELECT orders.id id, orders.date date, orders.status, cus.id cus_id, cus.name cus_name, 
                                     emp.id emp_id, emp.name emp_name, orders.total, orders.invoice AS invoice
                             FROM orders 
                                LEFT JOIN customer AS cus ON orders.customer = cus.id
                                LEFT JOIN employee AS emp ON orders.employee = emp.id
                            ORDER BY orders.id DESC';
                }
                //$sql = 'SELECT * FROM orders ORDER BY id DESC';
                $result = $conn->query($sql) or die($conn->error);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>' . "\n";
                        echo '<th scope="row" onclick="loadModal(\'showOrders\', ' . $row['id'] . ')">O' . sprintf("%05d", $row['id']) . '</th>' . "\n";
                        echo '<td scope="row">' . date("d/m/Y", strtotime($row['date'] . "+543 years")) . '</td>' . "\n";
                        echo '<td class="text-left">C' . sprintf("%05d", $row['cus_id']) . ' - ' . $row['cus_name'] . '</td>';

                        if ($row['emp_id']) {
                            echo '<td class="text-left">E' . sprintf("%05d", $row['emp_id']) . ' - ' . $row['emp_name'] . '</td>';
                        } else {
                            echo "<td>-</td>";
                        }

                        echo '<td class="text-right">' . number_format($row['total'], 2) . '</td>' . "\n";
                        if ($row['status'] == 0) {
                            echo '<td class="text-center"><font color="red">ยกเลิกการสั่งซื้อ</font></td>' . "\n";
                        } else if ($row['status'] == 1) {
                            echo '<td class="text-center"><font color="#FBB70F">ยังไม่ชำระเงิน</font></td>' . "\n";
                        } else if ($row['status'] == 2) {
                            echo '<td class="text-center"><font color="#009AF0">รอตรวจสอบ</font></td>' . "\n";
                        } else if ($row['status'] == 3) {
                            echo '<td class="text-center"><font color="#BC07D9">รอจัดส่งสินค้า</font></td>' . "\n";
                        } else if ($row['status'] == 4) {
                            echo '<td class="text-center"><font color="#07C133">จัดส่งแล้ว</font></td>' . "\n";
                        }

                        echo '<td>' . "\n";
                        if ($row['status'] == 0) {
                            //echo "ยกเลิก";
                        } else if ($row['status'] == 1) {
                            echo '<button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-toggle="dropdown">ทำรายการ<span class="caret"></span></button>';
                            echo '<ul class="dropdown-menu">' . "\n";
                            if ($row['invoice'] == NULL) {
                                echo '<li><div class="dropdown-item" onclick="loadModal(\'addInvoice\', ' . $row['id'] . ')">ออกใบแจ้งหนี้</div></li>' . "\n";
                            } else {
                                echo '<li><div class="dropdown-item" onclick="loadModal(\'payReceipt\', ' . $row['invoice'] . ')">รับชำระ</div></li>' . "\n";
                            }
                            echo '<li><div class="dropdown-item"onclick="loadModal(\'cancelInvoice\', ' . $row['id'] . ')">ยกเลิกการสั่งซื้อ</div></li>' . "\n";
                            echo "</ul>";
                        } else if ($row['status'] == 2) {
                            echo '<button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-toggle="dropdown">ทำรายการ<span class="caret"></span></button>';
                            echo '<ul class="dropdown-menu">' . "\n";
                            echo '<li><div class="dropdown-item" onclick="loadModal(\'payReceipt\', ' . $row['invoice'] . ')">รับชำระ</div></li>' . "\n";
                            echo '<li><div class="dropdown-item" onclick="loadModal(\'cancelInvoice\', ' . $row['id'] . ')">ยกเลิกการสั่งซื้อ</div></li>' . "\n";
                        } else {
                            $sql_receipt = "SELECT invoice.receipt FROM invoice WHERE id = '" . $row['invoice'] . "'";
                            $q_receipt = $conn->query($sql_receipt);
                            $row_receipt = $q_receipt->fetch_assoc();
                            if ($row['status'] == 3) {
                                echo '<button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-toggle="dropdown">ทำรายการ<span class="caret"></span></button>';
                                echo '<ul class="dropdown-menu">' . "\n";
                                echo '<li><div class="dropdown-item" onclick="loadModal(\'addDelivery\', ' . $row['id'] . ')">บันทึกการส่ง</div></li>' . "\n";
                                echo '<li><div class="dropdown-item" onclick="loadModal(\'getReceipt\', ' . $row_receipt['receipt'] . ')">แสดงใบเสร็จ</div></li>' . "\n";
                            } else {
                                echo '<button class="btn btn-primary btn-sm" name="receipt" onclick="loadModal(\'getReceipt\', ' . $row_receipt['receipt'] . ')">แสดงใบเสร็จ</button>';
                            }
                        }
                        echo '</td>' . "\n";
                        echo '</tr>' . "\n";
                    }
                } else {
                    echo '<td colspan="7">ไม่พบข้อมูล</td>';
                }
                // $sql = 'SELECT * FROM `category` inner join `product` inner join `orderlist` inner join `orders` WHERE orders.id = orderlist.orders and product.id = orderlist.product and product.category = category.id and orders.customer = '.$_SESSION['uid'].' order by orders.id DESC';
                // $result = $conn->query($sql);
                // while($row = $result->fetch_assoc()){
                //     $data[$i]['id']
                //     print_r($row['id']);
                // }
                // if ($result) {
                //     while ($row = $result->fetch_assoc()) {
                //         print_r($row);
                //         echo '<tr>'."\n";
                //         echo '<td class="text-left">'.$row['invoice_id'].'</td>'."\n";
                //         echo '<td class="text-right">'.$row['date'].'</td>'."\n";
                //         echo '<td class="text-right">'.$row['date'].'</td>'."\n";
                //         echo '<td class="text-right">'.$row['total'].'</td>'."\n";
                //         if($row['status']){
                //             echo '<td class="text-right">ชำระเงินแล้ว</td>'."\n";
                //         }else{
                //             echo '<td class="text-right">ยังไม่ชำระเงิน</td>'."\n";
                //         }

                //         echo '<td>'."\n";
                //         echo '<button type="button" class="btn btn-primary btn-sm" onclick="loadModal(\'payInvoice\', '.$row['invoice_id'].')">แจ้งชำระเงิน</button>'."\n";
                //         echo '</td>'."\n";
                //         echo '</tr>'."\n";
                //     }
                // }
                ?>
            </tbody>
        </table>
    </div>
</div>
<div id="loadModal"></div>
<?php
require 'footer.php';
?>