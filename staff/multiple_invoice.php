<?php
require("header.php");
require('../config.php');
?>
<script>
    $(function() {
        var start_date = new Date(2019, 4, 1);
        var end_date = new Date();
        // Report Selector
        $('.datepicker').datepicker({
            language: 'th-th', //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
            format: 'dd/mm/yyyy',
            disableTouchKeyboard: true,
            todayBtn: false,
            clearBtn: false,
            closeBtn: false,
            //daysOfWeekDisabled: [0],
            endDate: end_date,
            startDate: start_date,
            autoclose: true, //Set เป็นปี พ.ศ.
            inline: true
        }) //กำหนดเป็นวันปัจุบัน       
    });
</script>
<div class="container">
    <hr>
    <h2 class="text-center">ออกใบเสร็จรับเงิน</h2>
    <hr>

    <?php
    if (isset($_POST['cf_invoice']) && isset($_POST['totals']) && isset($_POST['date'])) {
        $sql = isset($_POST['ps']) ? 'INSERT INTO receipt (employee, date, amount, ps) VALUES(1, CURRENT_TIMESTAMP, "' . $_POST['totals'] . '", "' . $_POST['ps'] . '")' : 'INSERT INTO receipt (employee, date, amount) VALUES(1, "' . $_POST['date'] . '", "' . $_POST['totals'] . '")';
        $result = $conn->query($sql);
        $receipt_new = $conn->insert_id;
        foreach ($_POST['cf_invoice'] as $value) {
            $sql = 'UPDATE invoice SET receipt = ' . $receipt_new . ', status = 3, paydate = CURRENT_TIMESTAMP, payment = \'' . $_POST['date'] . '\' WHERE id = ' . $value;
            $result = $conn->query($sql);
            $sql = 'SELECT orders.id AS OrdersID FROM orders INNER JOIN invoice ON orders.invoice = invoice.id WHERE invoice.id =' . $value;
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                $sqll = 'UPDATE orders SET status = 3 WHERE id =' . $row['OrdersID'];
                $resultt = $conn->query($sqll);
                $sqll = 'UPDATE product INNER JOIN orderlist ON product.id = orderlist.product INNER JOIN orders ON orderlist.orders = orders.id SET product.status = 3 WHERE orders.id = ' . $row['OrdersID'];
                $resultt = $conn->query($sqll);
            }
        }
    } else if (isset($_POST['invoice'])) {
        echo '<div class="offset-md-3 col-md-10" >';
        echo '<form method="post">';
        echo '<div class="row"><div class="offset-md-2 col-md-5">';

        $sql2 = "SELECT * FROM customer WHERE id = '" . $_POST['user'] . "'";
        $result2 = $conn->query($sql2);
        $row2 = $result2->fetch_assoc();

        echo "<b>รหัสลูกค้า : </b>C" . sprintf("%05d", $_POST['user']) . " <b>ชื่อลูกค้า : </b>" . $row2['name'] . "<br>";
        echo '<b>รายการที่ต้องการออกใบเสร็จรับเงิน</b><br>';
        $ids = 0;
        $totals = 0;
        foreach ($_POST['invoice'] as $value) {
            $sql = 'SELECT * FROM invoice WHERE id = ' . $value;
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            echo '<input type="hidden" name="cf_invoice[' . $ids . ']" value="' . $value . '">';
            echo 'I' . sprintf("%05d", $row['id']) . ' - ' . number_format($row['amount'], 2) . '<br>';
            $ids++;
            $totals += $row['amount'];
        }
        echo '<b>จำนวน ' . $ids . ' รายการ​</b><br>';
        echo '<b>รวมทั้งสิ้น ' . number_format($totals, 2) . ' บาท</b><br>';
        echo '<input type="hidden" name="totals" value="' . $totals . '">';
        echo '<br><div class="row">
            <label class="control-label mt-1">วันที่ชำระ :* </label>
            <div class="col-md-7">';

        echo '<input type="text" required class="datepicker form-control" name="date">';
        echo '</div></div>';
        echo '<br><div class="row">
        <label class="control-label mt-1">หมายเหตุ : </label>
        <div class="col-md-7">';
        echo '<textarea class="form-control" name="ps"></textarea>';
        echo '</div></div><div class="mt-3 col-md-3 offset-md-2">';
        echo '<input class="btn btn-primary btn-sm" value="บันทึก" type="submit" >';
        echo '</div></form></div></div>';
    } else {
        // echo '<div class="offset-md-4 col-md-10" >';
        // echo '<form method="post"><div class="col-md-5">';
        // echo '<select name="user" class="form-control" onchange="this.form.submit()">';
        // echo '<option>--- เลือกลูกค้าที่ต้องการ ---</option>';
        // $sql = 'SELECT * FROM customer';
        // $result = $conn->query($sql);
        // while ($row = $result->fetch_assoc()) {
        //     $selected = ($row['id'] == $_POST['user']) ? 'selected' : NULL;
        //     echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['name'] . '</option>';
        // }
        // echo '</select><br></div>';
        // if (isset($_POST['user'])) {
        //     $sql = 'SELECT invoice.id AS InvoiceID, invoice.amount AS InvoiceAmount FROM invoice INNER JOIN orders ON invoice.id = orders.invoice INNER JOIN customer ON orders.customer = customer.id WHERE invoice.status = 1 AND customer.id =' . $_POST['user'] . ' GROUP BY invoice.id';
        //     // echo $sql;
        //     $result = $conn->query($sql);
        //     if ($result->num_rows > 0) {
        //         echo "<b>กรุณาเลือกใบแจ้งหนี้ที่ต้องการออกใบเสร็จรับเงิน</b><br>";
        //     }
        //     while ($row = $result->fetch_assoc()) {
        //         echo '<input type="checkbox"  name="invoice[]" value=' . $row['InvoiceID'] . '>&nbspI' . sprintf("%05d", $row['InvoiceID']) . ' - ' . number_format($row['InvoiceAmount'], 2) . '<br>';
        //     }
        // }

        // echo '<div class="offset-md-2 col-md-2"><input class="btn btn-primary" type="submit" value="เลือก"></div>';
        // echo '</form>';

    ?>

        <form method="POST" action="multiple_invoice.php">
            <div class="offset-md-3 col-md-6 form-inline">
                <input class="form-control" type="text" placeholder="กรอกข้อมูลลูกค้าที่ต้องการค้นหา" name="search_cus" style="width:85%;" />
                <input type="submit" name="submit" class="btn btn-primary" value="ค้นหา">
            </div>
            <!-- </form> -->
            <hr>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $search = $_POST['search_cus'] ? $_POST['search_cus'] : NULL;

                if ($search != NULL || $search != "") {
                    $sql = "SELECT * FROM customer WHERE customer.id LIKE '%" . $search . "%'
        OR name LIKE '%" . $search . "%'
        OR tel LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%'";
                } else {
                    $sql = "SELECT * FROM customer";
                }
                $result = $conn->query($sql);
            ?>
                <table class="table table-striped table-bordered">
                    <tr class="text-center">
                        <th width="15%">รหัสลูกค้า</th>
                        <th width="30%">ชื่อ-นามสกุล</th>
                        <th width="25%">อีเมล</th>
                        <th width="20%">เบอร์โทรศัพท์</th>
                        <th width="10%">คำสั่ง</th>
                    </tr>
                <?php
                while ($row = $result->fetch_array()) {
                    echo "<tr>";
                    echo "<td align='center'>C" . sprintf('%05d', $row['id']) . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['tel'] . "</td>";
                    echo "<td align='center'><a class='btn btn-primary' href='?user=" . $row['id'] . "'>เลือก</a></td>";
                    echo "</tr>";
                }
                echo '</table>';
            }
                ?>

            <?php

            if (isset($_GET['user'])) {

                $sql2 = "SELECT * FROM customer WHERE id = '" . $_GET['user'] . "'";
                $result2 = $conn->query($sql2);
                $row2 = $result2->fetch_assoc();

                echo '<div class="mt-1 mb-3 row">
            <div class="col-md-5 offset-md-3">';
                $sql = 'SELECT invoice.id AS InvoiceID, invoice.amount AS InvoiceAmount FROM invoice INNER JOIN orders ON invoice.id = orders.invoice INNER JOIN customer ON orders.customer = customer.id WHERE invoice.status = 1 AND customer.id =' . $_GET['user'] . ' GROUP BY invoice.id';
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    echo "<b>รหัสลูกค้า : </b>C" . sprintf("%05d", $_GET['user']) . " <b>ชื่อลูกค้า : </b>" . $row2['name'] . "<br>";
                    echo "<b>กรุณาเลือกรายการสั่งซื้อที่ต้องการออกใบเสร็จรับเงิน</b><br>";
                }
                while ($row = $result->fetch_assoc()) {
                    echo '<input type="checkbox"  name="invoice[]" value=' . $row['InvoiceID'] . '>&nbspI' . sprintf("%05d", $row['InvoiceID']) . ' - ' . number_format($row['InvoiceAmount'], 2) . '<br>';
                }
                echo "<input type='text' hidden name='user' value='" . $_GET['user'] . "'>";
                echo '<div class="col-md-3 offset-md-2"><input type="hidden" name="totals" value="' . $totals . '">';
                echo '</div></hr></div>';
                if ($result->num_rows > 0) {
                    echo '<div class="offset-md-5 col-md-2">&nbsp<input class="btn btn-primary" value="เลือก" type="submit"></div>';
                }
            }
            echo '</form>';
        }
            ?>
</div>
<hr>
<?php

require("footer.php");
?>