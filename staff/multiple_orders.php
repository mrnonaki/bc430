<?php
require('header.php');
require('../config.php');
?>

<div class="container">
    <hr>
    <h2 class="text-center">ออกใบแจ้งหนี้</h2>
    <hr>
    <?php
    if (isset($_POST['cf_orders']) && isset($_POST['totals'])) {
        $sql = 'INSERT INTO invoice (employee, duedate, amount, status) VALUES(1, DATE_ADD(NOW(), INTERVAL 7 DAY), ' . $_POST['totals'] . ', 1)';
        $result = $conn->query($sql);
        $invoice_new = $conn->insert_id;
        foreach ($_POST['cf_orders'] as $value) {
            $sql = 'SELECT * FROM orders WHERE id = ' . $value;
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            if ($row['invoice'] != NULL) {
                $sql = 'UPDATE invoice SET status = 0 WHERE id = ' . $row['invoice'];
                $result = $conn->query($sql);
            }
            $sql = 'UPDATE orders SET invoice = ' . $invoice_new . ' WHERE id = ' . $value;
            $result = $conn->query($sql);
        }
        echo "<script>alert('I" . sprintf("%05d", $invoice_new) . ": บันทึกใบแจ้งหนี้เรียบร้อย'); window.location.assign('orderlist.php');</script>";
    } else if (isset($_POST['orders'])) {
        echo '<div class="offset-md-3 col-md-10" >';
        echo '<form class="" method="post">';
        echo '<div class="row"><div class="offset-md-2 col-md-5">';
        //echo "รหัสลูกค้า: ". $_GET['a']."<br>";
        $sql2 = "SELECT * FROM customer WHERE id = '" . $_POST['user'] . "'";
        $result2 = $conn->query($sql2);
        $row2 = $result2->fetch_assoc();

        echo "<b>รหัสลูกค้า : </b>C" . sprintf("%05d", $_POST['user']) . " <b>ชื่อลูกค้า : </b>" . $row2['name'] . "<br>";
        echo '<b>รายการที่ต้องการออกใบแจ้งหนี้</b><br>';
        $ids = 0;
        $totals = 0;
        foreach ($_POST['orders'] as $value) {
            $sql = 'SELECT * FROM orders WHERE id = ' . $value;
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            echo '<input type="hidden" name="cf_orders[]" value="' . $value . '">';
            echo 'O' . sprintf("%05d", $row['id']) . ' - ' . number_format($row['total'], 2) . ' บาท<br>';

            $ids++;
            $totals += $row['total'];
        }
        echo '<b>จำนวน ' . $ids . ' รายการ</b>​<br>';
        echo '<b>รวมทั้งสิ้น ' . number_format($totals, 2) . ' บาท</b><br>';
        echo '<div class="col-md-3 offset-md-2"><input type="hidden" name="totals" value="' . $totals . '">';
        echo '<br><input class="btn btn-primary btn-sm" type="submit" value="บันทึก">';
        echo '</div></div></div>';
        echo '</form></div>';
    } else {
        // echo '<div class="offset-md-4 col-md-10" >';
        // echo '<form class="" method="post"><div class="col-md-5" >';;
        // echo '<select name="user" style="width:100%" class="form-control" onchange="this.form.submit()">';
        // echo '<option>--- เลือกลูกค้า ---</option>';
        // $sql = 'SELECT * FROM customer';
        // $result = $conn->query($sql);
        // while ($row = $result->fetch_assoc()) {
        //     $selected = ($row['id'] == $_POST['user']) ? 'selected' : NULL;
        //     echo '<option value="' . $row['id'] . '" ' . $selected . '>C' . sprintf("%05d", $row['id']) . ' : ' . $row['name'] . '</option>';
        // }
        // echo '</select><br></div>';

    ?>

        <form method="POST" action="multiple_orders.php" class="">
            <div class="offset-md-4 col-md-6 form-inline">
                <input class="form-control" type="text" placeholder="กรอกข้อมูลลูกค้าที่ต้องการค้นหา" name="search_cus" style="width:250px;" />
                <input type="submit" name="submit" class="btn btn-primary" value="ค้นหา">
            </div>
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

            echo '<div class="mt-1 mb-3 row">
            <div class="offset-md-4 col-md-5">';
            $sql = 'SELECT o.total, o.id FROM orders AS o
            WHERE status = 1 AND customer =' . $_GET['user'];
            $result = $conn->query($sql);

            $sql2 = "SELECT * FROM customer WHERE id = '" . $_GET['user'] . "'";
            $result2 = $conn->query($sql2);
            $row2 = $result2->fetch_assoc();

            if ($result->num_rows > 0) {
                echo "<b>รหัสลูกค้า : </b>C" . sprintf("%05d", $_GET['user']) . " <b>ชื่อลูกค้า : </b>" . $row2['name'] . "<br>";
                echo "<b>กรุณาเลือกรายการสั่งซื้อที่ต้องการออกใบแจ้งหนี้</b><br>";
                echo "<input type='text' hidden name='user' value='" . $_GET['user'] . "'>";
            } else {
                echo "<b style='padding-left:60px;'>** ไม่พบข้อมูลรายการสั่งซื้อ **</b>";
            }
            while ($row = $result->fetch_assoc()) {
                echo '<input type="checkbox" name="orders[]" value=' . $row['id'] . '> O' . sprintf("%05d", $row['id']) . ' - ' . number_format($row['total'], 2) . ' บาท <br>';
            }
            echo '</div></hr></div>';
            if ($result->num_rows > 0) {
                echo '<div class="offset-md-5 col-md-2">&nbsp<input class="btn btn-primary" value="เลือก" type="submit"></div>';
            }
        }
        echo '</form>';
    }
?>
</div>

<?php

require("footer.php");

?>