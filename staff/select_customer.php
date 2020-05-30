<?php

require('header.php');
require('../config.php');

if (!isset($_SESSION['cart'])) {
    echo "<script>alert('กรุณาเลือกสินค้าที่ต้องการสั่ง'); window.location.assign('cart_staff.php');</script>";
    exit();
}

?>
<div class="container" style="margin-top:25px;">
    <h1 class="text-center"><?= $page_name ?></h1>
    <hr>
    <form method="POST" class="form-inline">
        <div class="offset-md-3 col-md-6">
            <input class="form-control" type="text" placeholder="กรอกข้อมูลลูกค้าที่ต้องการค้นหา" name="search_cus" style="width:85%;" />
            <input type="submit" name="submit" class="btn btn-primary" value="ค้นหา">
        </div>
    </form>
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
            echo "<td align='center'><a class='btn btn-primary' href='cart_staff.php?cus=" . $row['id'] . "'>เลือก</a></td>";
            echo "</tr>";
        }
    }
        ?>
</div>