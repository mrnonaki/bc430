<?php
require 'config.php';
require 'header.php';

$post = isset($_POST['type']) ? $_POST['type'] : NULL;

if ($post == 'addOrder') {
    if (isset($_SESSION['address'])) {
        $address = $_SESSION['address'];
    } else {
        $sql = 'SELECT * FROM customer WHERE id = ' . $_SESSION['uid'];
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $address = $row['address_no'] . ', ' . $row['address_district'] . ', ' . $row['address_amphoe'] . ', ' . $row['address_province'] . ', ' . $row['address_zipcode'];
    }
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
    $sql = 'INSERT INTO invoice (date,duedate,amount,status) VALUES(' . 'CURRENT_TIMESTAMP, DATE_ADD(NOW(), INTERVAL 7 DAY),' . $sum . ', 1)';
    $result = $conn->query($sql);
    $sql = 'SELECT * FROM invoice ORDER BY id DESC';
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $invoiceid = $row['id'];
    $sql = 'INSERT INTO orders (customer,invoice, ship, total, date, status) VALUES (' . $_SESSION['uid'] . ',' . $invoiceid . ', \'' . $address . '\', ' . $sum . ', CURRENT_TIMESTAMP, 1)';
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

if ($post == "cancelInvoice" && $_POST['id']) {
    $sql_c = "UPDATE orders 
        LEFT JOIN invoice ON orders.invoice = invoice.id SET 
        orders.status   = '0',
        invoice.status  = '0' WHERE orders.id = '" . $_POST['id'] . "'";
    $result_c = $conn->query($sql_c);
    echo '<script>alert("O' . sprintf("%05d", $_POST['id']) . ': ยกเลิกการสั่งซื้อเรียบร้อย");
        window.location.assign("orderlist.php")</script>';
}
?>
<div class="container">
    <div>
        <form method="get">
            <h1 class="text-center">รายการสั่งซื้อ</h1>
            <p class="text-center">
                <input type="text" name="search" placeholder="ค้นหาการสั่งซื้อ" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>"><button type="submit" class="btn btn-primary btn-sm">ค้นหา</button>
            </p>
        </form>
    </div>
    <div>
        <table class="table table-striped table-bordered text-center">
            <thead>
                <tr>
                    <th scope="col" width="15%">รหัสการสั่งซื้อ</th>
                    <th scope="col" width="10%">วันที่สั่งซื้อ</th>
                    <th scope="col" width="30%">รายการ</th>
                    <th scope="col" width="15%">ยอดรวม (บาท)</th>
                    <th scope="col" width="15%">สถานะ</th>
                    <th scope="col" width="15%">คำสั่ง</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_GET['search']) && $_GET['search']  != "") {
                    if (($_GET['search'][0] == 'O' || $_GET['search'][0] == 'o') && is_numeric($_GET['search'][1])) {
                        $search_id = (int) substr($_GET['search'], 1);
                        $sql = 'SELECT * FROM orders WHERE customer = ' . $_SESSION['uid'] . ' AND id LIKE ' . $search_id . ' ORDER BY id DESC';
                    } else {
                        $sql = 'SELECT * FROM orders WHERE customer = ' . $_SESSION['uid'] . ' AND id LIKE "%' . $_GET['search'] . '%"  ORDER BY id DESC';
                    }
                } else {
                    $sql = 'SELECT * FROM orders WHERE customer = ' . $_SESSION['uid'] . ' ORDER BY id DESC';
                }
                // $sql = 'SELECT * FROM orders WHERE customer = ' . $_SESSION['uid'] . ' ORDER BY id DESC';
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>' . "\n";
                    echo '<th scope="row" onclick="loadModal(\'showOrders\', ' . $row['id'] . ')">O' . sprintf("%05d", $row['id']) . '</th>' . "\n";
                    echo '<td scope="row">' . date("d/m/Y", strtotime($row['date']."+543 years")) . '</td>' . "\n";
                    echo '<td scope="row" class="text-left">';
                    $sqll = 'SELECT product.id, category.name FROM orders INNER JOIN orderlist INNER JOIN product INNER JOIN category WHERE orders.id = orderlist.orders AND orderlist.product = product.id AND product.category = category.id AND orders.customer = ' . $_SESSION['uid'] . ' AND orders.id = ' . $row['id'] . ' ORDER BY category.id';
                    $resultt = $conn->query($sqll);
                    while ($roww = $resultt->fetch_assoc()) {
                        echo '- ' . $roww['name'] . ' (' . $roww['id'] . ') <br>';
                    }
                    echo '</td>' . "\n";

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
                        // echo "ยกเลิก";
                    } else if ($row['status'] == 1) {
                        echo '<button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-toggle="dropdown">ทำรายการ<span class="caret"></span></button>';
                        echo '<ul class="dropdown-menu">' . "\n";
                        echo '<li><div class="dropdown-item" onclick="loadModal(\'downloadInvoice\', ' . $row['invoice'] . ')">ออกใบแจ้งหนี้</div></li>' . "\n";
                        echo '<li><div class="dropdown-item"onclick="loadModal(\'payInvoice\', ' . $row['invoice'] . ')">แจ้งชำระเงิน</div></li>' . "\n";
                        echo '<li><div class="dropdown-item"onclick="loadModal(\'cancelInvoice\', ' . $row['invoice'] . ')">ยกเลิกการสั่งซื้อ</div></li>' . "\n";
                        echo "</ul>";
                    } else if ($row['status'] == 2) {
                        echo 'รอตรวจสอบ';
                    } else {
                        $row_receipt = $conn->query("SELECT receipt FROM invoice WHERE id = '" . $row['invoice'] . "'")->fetch_assoc();
                        echo '<button class="btn btn-primary btn-sm" type="button" onclick="loadModal(\'getReceipt\', ' . $row_receipt['receipt'] . ')">แสดงใบเสร็จ</button>' . "\n";
                    }
                    echo '</td>' . "\n";
                    echo '</tr>' . "\n";
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