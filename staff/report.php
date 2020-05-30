<head>
    <link href="../css/bootstrap-4.3.1.css" rel="stylesheet">
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap-4.3.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    <title><?= ($_POST['report_name']) ? $_POST['report_name'] : "-" ?> - ระบบจำหน่ายอุปกรณ์คอมพิวเตอร์</title>
</head>
<?php
session_start();

function cut_date($input)
{
    $str_cut =  str_replace("/", "", $input); // ตัด "/"

    $year = substr(substr(($str_cut), 4, 4) - 543, 2, 2); // ตัดปี
    $month = substr($str_cut, 2, 2);
    $date = substr($str_cut, 0, 2);

    $result = $date . $month . $year;
    return $result;
}

function full_month($input)
{
    $month_array = array(
        "01" => "มกราคม",
        "02" => "กุมภาพันธ์",
        "03" => "มีนาคม",
        "04" => "เมษายน",
        "05" => "พฤษภาคม",
        "06" => "มิถุนายน",
        "07" => "กรกฎาคม",
        "08" => "สิงหาคม",
        "09" => "กันยายน",
        "10" => "ตุลาคม",
        "11" => "พฤศจิกายน",
        "12" => "ธันวาคม",
    );

    $result = $month_array[$input];

    return $result;
}

require '../config.php';
if (isset($_POST['type'])) {
    $type = $_POST['type'];
} else if (isset($_GET['type'])) {
    $type = $_GET['type'];
} else {
    $type = NULL;
}
if (isset($_POST['date'])) {
    $date = cut_date($_POST['date']);
} else if (isset($_GET['date'])) {
    $date = $_GET['date'];
} else {
    $date = NULL;
}
if (isset($_POST['todate'])) {
    $todate = cut_date($_POST['todate']);
} else if (isset($_GET['todate'])) {
    $todate = $_GET['todate'];
} else {
    $todate = NULL;
}

function fulldate_thai($dates)
{

    $d = substr($dates, 0, 2);
    $m = substr($dates, 3, 2);
    $y = substr($dates, 6, 4);

    if ($d < 10) {
        $d = substr($d, 1, 1);
    }
    if ($m < 10) {
        $m = substr($m, 1, 1);
    }

    $months = array(
        '',
        'มกราคม',
        'กุมภาพันธ์',
        'มีนาคม',
        'เมษายน',
        'พฤษภาคม',
        'มิถุนายน',
        'กรกฎาคม ',
        'สิงหาคม',
        'กันยายน',
        'ตุลาคม',
        'พฤศจิกายน',
        'ธันวาคม',
    );

    if ($dates == "") {
        return "";
    } else {
        return $d . " " . $months[$m] . " พ.ศ. " . ($y + 543);
    }
}

///////////////////////////////////////////////////////////////////////////////

if ($type == 'orders_daily' && $date && $todate) {
    $day = substr($date, 0, 2);
    $month = substr($date, 2, 2);
    $year = substr($date, 4, 2);
    $today = substr($todate, 0, 2);
    $tomonth = substr($todate, 2, 2);
    $toyear = substr($todate, 4, 2);
    $OrdersStatus0 = 0;
    $OrdersStatus1 = 0;
    $OrdersStatus2 = 0;
    $OrdersStatus3 = 0;
    $OrdersStatus4 = 0;
    $totalOrdersStatus0 = 0;
    $totalOrdersStatus1 = 0;
    $totalOrdersStatus2 = 0;
    $totalOrdersStatus3 = 0;
    $totalOrdersStatus4 = 0;
    $Orders = 0;
    $totalOrders = 0;
    echo '<center>' . "\n";
    echo '<h3 style="padding-top:40px;">รายงานการสั่งซื้อประจำวัน</h3>';
    echo '<h4>ตั้งแต่วันที่ ' . fulldate_thai($day . '-' . $month . '-' . ($year + 2000)) . ' ถึงวันที่ ' . fulldate_thai($today . '-' . $tomonth . '-' . ($toyear + 2000)) . '</h4><br>' . "\n";
    echo '<table width="1350px" align="center"><tr style="border-bottom:1px solid;"><td align="right" colspan="10">';
    echo 'วันที่พิมพ์ ' . fulldate_thai(date("d-m-Y")) . '<br>' . "\n" . '</td></tr>';
    echo '</center>' . "\n";
    echo '
            <tr style="border-bottom:1px solid;">
                <th height="30px">วันที่สั่งซื้อ</th>
                <th class="text-center">รหัสการสั่งซื้อ</th>
                <th>ชื่อ-นามสกุล</th>
                <th>รายการ</th>
                <th>จำนวน</th>
                <th>หน่วยนับ</th>
                <th class="text-center">ราคาต่อหน่วย (บาท)</th>
                <th class="text-center">ค่าส่งต่อหน่วย (บาท)</th>
                <th>สถานะ</th>
                <th>ยอดชำระ (บาท)</th>
            <tr/>
    ' . "\n";
    $sql = 'SELECT orders.date AS OrdersDate, orders.id AS OrdersID, customer.name AS CustomerName, orders.total AS OrdersTotal, orders.status AS OrdersStatus  FROM orders INNER JOIN customer ON orders.customer = customer.id WHERE date >= \'20' . $year . '-' . $month . '-' . $day . ' 00:00:00\' AND date <= \'20' . $toyear . '-' . $tomonth . '-' . $today . ' 23:59:59\' ORDER BY orders.id';
    $result = $conn->query($sql);
    if ($result->num_rows == 0) {
        echo '<script>alert("ไม่พบข้อมูลที่ต้องการค้นหา"); window.close();</script>';
        exit();
    }
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        if (date("d/m/Y", strtotime($row['OrdersDate'] . "+543years")) != $loopDate_before) {
            if ($i) {
                echo '
                    <tr style="border-top:1px solid; border-bottom:1px solid; background:#DFE1DF">
                        <td colspan="8"></td>
                        <td style="text-align:right; padding-right:40px;"><font><b>ราคารวม</td>
                        <td style="text-align:right; padding-right:40px;"><font><b>' . number_format($totalDate, 2) . '</td>
                    </tr>
                ';
            }
            $totalDate = 0;
            $loopDate = date("d/m/Y", strtotime($row['OrdersDate'] . "+543years"));
        } else {
            $loopDate = NULL;
        }
        $sqll = 'SELECT category.id AS CategoryID, category.name AS CategoryName, COUNT(category.id) AS CategoryCount, category.unit AS CategoryUnit, orderlist.rate AS OrderlistRate, orderlist.ship AS OrderlistShip FROM orderlist INNER JOIN product ON orderlist.product = product.id INNER JOIN category ON product.category = category.id WHERE orderlist.orders = ' . $row['OrdersID'] . ' GROUP BY category.id ORDER BY category.id';
        $resultt = $conn->query($sqll);
        $j = 0;
        while ($roww = $resultt->fetch_assoc()) {
            echo '                <tr>' . "\n";
            if ($j) {
                echo '
                    <td></td>
                    <td></td>
                    <td></td>
                ';
            } else {
                echo '
                    <td>' . $loopDate . '</td>
                    <td class="text-center">' . sprintf("%05d", $row['OrdersID']) . '</td>
                    <td>' . $row['CustomerName'] . '</td>
                ';
            }
            echo '
                    <td>' . $roww['CategoryName'] . '</td>
                    <td class="text-center">' . $roww['CategoryCount'] . '</td>
                    <td>' . $roww['CategoryUnit'] . '</td>
                    <td class="text-right" style="padding-right:20px;">' . number_format($roww['OrderlistRate'], 2) . '</td>
                    <td class="text-right" style="padding-right:20px;">' . number_format($roww['OrderlistShip'], 2) . '</td>
                    ';
            if ($j) {
                echo '
                    <td></td>
                    <td></td>
                ';
            } else {
                $totalDate += $row['OrdersTotal'];
                if ($row['OrdersStatus'] == 0) {
                    $OrdersStatus0++;
                    $color_order = "red";
                    $totalOrdersStatus0 += $row['OrdersTotal'];
                    $OrdersStatus = '<font color="red">ยกเลิกการสั่งซื้อ</font>';
                } else if ($row['OrdersStatus'] == 1) {
                    $OrdersStatus1++;
                    $totalOrdersStatus1 += $row['OrdersTotal'];
                    $color_order = "FBB70F";
                    $OrdersStatus = '<font color="#FBB70F">รอแจ้งชำระ</font>';
                } else if ($row['OrdersStatus'] == 2) {
                    $OrdersStatus2++;
                    $color_order = "00B6F1";
                    $totalOrdersStatus2 += $row['OrdersTotal'];
                    $OrdersStatus = '<font color="00B6F1">รอตรวจสอบ</font>';
                } else if ($row['OrdersStatus'] == 3) {
                    $OrdersStatus3++;
                    $color_order = "BC07D9";
                    $totalOrdersStatus3 += $row['OrdersTotal'];
                    $OrdersStatus = '<font color="BC07D9">รอส่งสินค้า</font>';
                } else if ($row['OrdersStatus'] == 4) {
                    $OrdersStatus4++;
                    $color_order = "4CD267";
                    $totalOrdersStatus4 += $row['OrdersTotal'];
                    $OrdersStatus = '<font color="#4CD267">ชำระแล้ว</font>';
                }
                $Orders++;
                $totalOrders += $row['OrdersTotal'];
                echo '
                    <td>' . $OrdersStatus . '</td>
                    <td style="text-align:right; padding-right:40px;"><font color="' . $color_order . '">' . number_format($row['OrdersTotal'], 2) . '</font></td>
                ';
            }
            echo '</tr>' . "\n";
            $j++;
        }
        $loopDate_before = date("d/m/Y", strtotime($row['OrdersDate'] . "+543years"));
        $i++;
    }
    echo '
        <tr style="border-top:1px solid; border-bottom:1px solid; background:#DFE1DF;">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align:right; padding-right:40px;"><b><font>ราคารวม</font></b></td>
            <td style="text-align:right; padding-right:40px;"><font><b>' . number_format($totalDate, 2) . '</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><font color="red"><b>รวมยกเลิกการสั่งซื้อทั้งหมด</font></td>
            <td style="text-align:right; padding-right:40px;"><b><font color="red">' . $OrdersStatus0 . ' &nbspรายการ</font></td>
            <td style="text-align:right; padding-right:40px;"><b><font color="red">' . number_format($totalOrdersStatus0, 2) . '</font></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><font color="FBB70F"><b>รวมรอแจ้งชำระทั้งหมด</font></td>
            <td style="text-align:right; padding-right:40px;"><b><font color="FBB70F">' . $OrdersStatus1 . ' &nbspรายการ</font></td>
            <td style="text-align:right; padding-right:40px;"><b><font color="FBB70F">' . number_format($totalOrdersStatus1, 2) . '</font></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><font color="00B6F1"><b>รวมรอตรวจสอบทั้งหมด</font></td>
            <td style="text-align:right; padding-right:40px;"><b><font color="00B6F1">' . $OrdersStatus2 . ' &nbspรายการ</font></td>
            <td style="text-align:right; padding-right:40px;"><b><font color="00B6F1">' . number_format($totalOrdersStatus2, 2) . '</font></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><font color="BC07D9"><b>รวมรอส่งสินค้าทั้งหมด</font></td>
            <td style="text-align:right; padding-right:40px;"><b><font color="BC07D9">' . $OrdersStatus3 . ' &nbspรายการ</font></td>
            <td style="text-align:right; padding-right:40px;" style="text-align:right; padding-right:40px;"><b><font color="BC07D9">' . number_format($totalOrdersStatus3, 2) . '</font></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><font color="4CD267"><b>รวมชำระแล้วทั้งหมด</font></td>
            <td style="text-align:right; padding-right:40px;"><b><font color="4CD267">' . $OrdersStatus4 . '&nbsp รายการ</font></td>
            <td style="text-align:right; padding-right:40px;"><b><font color="4CD267">' . number_format($totalOrdersStatus4, 2) . '</font></td>
        </tr>
        <tr style="border-bottom:1px solid;">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><font color=""><b>รวมราคาทั้งหมด</font></td>
            <td style="text-align:right; padding-right:40px;"><b>' . $Orders . ' &nbspรายการ</td>
            <td style="text-align:right; padding-right:40px;"><b>' . number_format($totalOrders, 2) . '</td>
        </tr>
    ';
    echo '          </table>' . "\n" . '<br>';
}

////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_POST['month']) && isset($_POST['year'])) {
    $date = "00" . $_POST['month'] . $_POST['year'];
}

if ($type == 'orders_monthly' && $date) {

    $month = substr($date, 2, 2);
    $year = substr($date, 4, 2);
    $OrdersStatus0 = 0;
    $OrdersStatus1 = 0;
    $OrdersStatus2 = 0;
    $OrdersStatus3 = 0;
    $OrdersStatus4 = 0;
    $totalOrdersStatus0 = 0;
    $totalOrdersStatus1 = 0;
    $totalOrdersStatus2 = 0;
    $totalOrdersStatus3 = 0;
    $totalOrdersStatus4 = 0;
    $Orders = 0;
    $totalOrders = 0;
    echo '<center><h3 style="padding-top:40px;">รายงานการสั่งซื้อประจำเดือน</h3>' . "\n";
    echo '<h4>ประจำเดือน ' . full_month($month) . ' พ.ศ. ' . ($year + (2000 + 543)) . '</h4><br>' . "\n" . '</center>';
    echo '<table  align="center" width="650px"><tr><td colspan="3">';
    echo '<td colspan="2" align="right">วันที่พิมพ์ ' .  fulldate_thai(date("d-m-Y")) . '</td></tr>';
    echo '  <tr style="border-top:1px solid; border-bottom:1px solid; height:30px;">
                <th style="text-align:center;">วันที่สั่งซื้อ</th>
                <th class="text-center">รหัสการสั่งซื้อ</th>
                <th>ชื่อ-นามสกุล</th>
                <th>สถานะ</th>
                <th class="text-center">ยอดชำระ (บาท)</th>
            <tr/>
    ' . "\n";
    $sql = 'SELECT orders.date AS OrdersDate, orders.id AS OrdersID, customer.name AS CustomerName, orders.total AS OrdersTotal, orders.status AS OrdersStatus  FROM orders INNER JOIN customer ON orders.customer = customer.id WHERE date >= \'20' . $year . '-' . $month . '-01 00:00:00\' AND date <= \'20' . $year . '-' . $month . '-31 23:59:59\' ORDER BY orders.id';
    $result = $conn->query($sql);
    if ($result->num_rows == 0) {
        echo '<script>alert("ไม่พบข้อมูลที่ต้องการค้นหา"); window.close();</script>';
        exit();
    }
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        if (date("d/m/Y", strtotime($row['OrdersDate'] . "+543years")) != $loopDate_before) {
            if ($i) {
                echo '
                    <tr style="border-top:1px solid; border-bottom:1px solid; background:#DFE1DF;">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><b>ราคารวม</td>
                        <td style="text-align:right; padding-right:35px;"><b>' . number_format($totalDate, 2) . '</td>
                    </tr>
                ';
            }
            $totalDate = 0;
            $loopDate = date("d/m/Y", strtotime($row['OrdersDate'] . "+543years"));
        } else {
            $loopDate = NULL;
        }
        $sqll = 'SELECT category.id AS CategoryID, category.name AS CategoryName, COUNT(category.id) AS CategoryCount, category.unit AS CategoryUnit, orderlist.rate AS OrderlistRate, orderlist.ship AS OrderlistShip FROM orderlist INNER JOIN product ON orderlist.product = product.id INNER JOIN category ON product.category = category.id WHERE orderlist.orders = ' . $row['OrdersID'] . ' GROUP BY category.id ORDER BY category.id';
        $resultt = $conn->query($sqll);
        $j = 0;
        while ($roww = $resultt->fetch_assoc()) {
            echo '                <tr>' . "\n";
            if ($j) {
                echo '
                    <td></td>
                    <td></td>
                    <td></td>
                ';
            } else {
                echo '
                    <td style="text-align:center;"<font color=""></font>' . $loopDate . '</td>
                    <td align="center">O' . sprintf("%05d", $row['OrdersID']) . '</td>
                    <td>' . $row['CustomerName'] . '</td>
                ';
            }
            if ($j) {
                echo '
                    <td></td>
                    <td></td>
                ';
            } else {
                $totalDate += $row['OrdersTotal'];
                if ($row['OrdersStatus'] == 0) {
                    $OrdersStatus0++;
                    $totalOrdersStatus0 += $row['OrdersTotal'];
                    $OrdersStatus = '<font color="red">ยกเลิกการสั่งซื้อ</font>';
                    $color = "red";
                } else if ($row['OrdersStatus'] == 1) {
                    $OrdersStatus1++;
                    $totalOrdersStatus1 += $row['OrdersTotal'];
                    $OrdersStatus = '<font color="FBB70F">รอแจ้งชำระ</font>';
                    $color = "FBB70F";
                } else if ($row['OrdersStatus'] == 2) {
                    $OrdersStatus2++;
                    $totalOrdersStatus2 += $row['OrdersTotal'];
                    $OrdersStatus = '<font color="00B6F1">รอตรวจสอบ</font>';
                    $color = "00B6F1";
                } else if ($row['OrdersStatus'] == 3) {
                    $OrdersStatus3++;
                    $totalOrdersStatus3 += $row['OrdersTotal'];
                    $OrdersStatus = '<font color="BC07D9">รอส่งสินค้า</font>';
                    $color = "BC07D9";
                } else if ($row['OrdersStatus'] == 4) {
                    $OrdersStatus4++;
                    $totalOrdersStatus4 += $row['OrdersTotal'];
                    $OrdersStatus = '<font color="4CD267">ชำระแล้ว</font>';
                    $color = "4CD267";
                }
                $Orders++;
                $totalOrders += $row['OrdersTotal'];
                echo '
                    <td>' . $OrdersStatus . '</td>
                    <td style="text-align:right; padding-right:35px;"><font color="' . $color . '">' . number_format($row['OrdersTotal'], 2) . '</font></td>
                ';
            }
            echo '</tr>' . "\n";
            $j++;
        }
        $loopDate_before = date("d/m/Y", strtotime($row['OrdersDate'] . "+543years"));
        $i++;
    }
    echo '
        <tr style="border-bottom:1px solid; background:#DFE1DF; border-top:1px solid;"> 
            <td></td>
            <td></td>
            <td></td>
            <td><b>ราคารวม</td>
            <td style="text-align:right; padding-right:35px;"><b>' . number_format($totalDate, 2) . '</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td><b><font color="red">รวมยกเลิกการสั่งซื้อทั้งหมด</font></td>
            <td><b><font color="red">' . $OrdersStatus0 . ' รายการ</font></td>
            <td style="text-align:right; padding-right:35px;"><b><font color="red">' . number_format($totalOrdersStatus0, 2) . '</font></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td><b><font color="FBB70F">รวมรอแจ้งชำระทั้งหมด</font></td>
            <td><b><font color="FBB70F">' . $OrdersStatus1 . ' รายการ</font></td>
            <td style="text-align:right; padding-right:35px;"><b><font color="FBB70F">' . number_format($totalOrdersStatus1, 2) . '</font></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td><b><font color="00B6F1">รวมรอตรวจสอบทั้งหมด</font></td>
            <td><b><font color="00B6F1">' . $OrdersStatus2 . ' รายการ</font></td>
            <td style="text-align:right; padding-right:35px;"><b><font color="00B6F1">' . number_format($totalOrdersStatus2, 2) . '</font></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td><b><font color="BC07D9">รวมรอส่งสินค้าทั้งหมด</font></td>
            <td><b><font color="BC07D9">' . $OrdersStatus3 . ' รายการ</font></td>
            <td style="text-align:right; padding-right:35px;"><b><font color="BC07D9">' . number_format($totalOrdersStatus3, 2) . '</font></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td><b><font color="4CD267">รวมชำระแล้วทั้งหมด</font></td>
            <td><b><font color="4CD267">' . $OrdersStatus4 . ' รายการ</font></td>
            <td style="text-align:right; padding-right:35px;"><b><font color="4CD267">' . number_format($totalOrdersStatus4, 2) . '</font></td>
        </tr>
        <tr style="border-bottom:1px solid;">
            <td></td>
            <td></td>
            <td><b>รวมราคาทั้งหมด</font></td>
            <td><b>' . $Orders . ' รายการ</font></td>
            <td style="text-align:right; padding-right:35px;"><b>' . number_format($totalOrders, 2) . '</font></td>
        </tr>
    ';
    echo '          </table>' . "\n";
}

//////////////////////////////////////////////////////////////////////////////////////////////

if (isset($_POST['month']) && isset($_POST['year'])) {
    $date = "00" . $_POST['month'] . $_POST['year'];
}

if ($type == 'invoice_monthly' && $date) {
    $month = substr($date, 2, 2);
    $year = substr($date, 4, 2);
    $Invoice = 0;
    $totalInvoice = 0;
    echo '<center>' . "\n";
    echo '<h3 style="padding-top:40px;">รายงานหนี้ค้างชำระประจำเดือน</h3>';
    echo '<h4>เดือน ' . full_month($month) . ' พ.ศ. ' . ($year + (2000 + 543)) . "</h4><br>" . "\n";
    echo '<table align="center" width="970px">';
    echo '<tr><td colspan="5"></td><td colspan="3" align="right">วันที่พิมพ์ ' .  fulldate_thai(date("d-m-Y")) . "</td></tr>";
    echo '</center>' . "\n";
    echo '
    <tr style="border-top:1px solid; border-bottom:1px solid; height:30px;">
        <th style="text-align:center;">วันกำหนดชำระ</th>
        <th style="text-align:center;">วันที่ออกใบแจ้งหนี้</th>
        <th style="text-align:center;">เลขที่ใบแจ้งหนี้</th>
        <th style="text-align:center;">รหัสการสั่งซื้อ</th>
        <th style="text-align:center;">วันที่สั่งซื้อ</th>
        <th>ชื่อ-นามสกุล</th>
        <th>เบอร์โทร</th>
        <th style="text-align:center;">ยอดชำระ (บาท)</th>
    <tr/>
    ' . "\n";
    $sql = 'SELECT invoice.duedate AS InvoiceDue, invoice.date AS InvoiceDate, invoice.id AS InvoiceID, orders.id AS OrdersID, orders.date AS OrdersDate, customer.name AS CustomerName, customer.tel AS CusTel, orders.total AS OrdersTotal FROM invoice INNER JOIN orders ON invoice.id = orders.invoice INNER JOIN customer ON orders.customer = customer.id WHERE invoice.date >= \'20' . $year . '-' . $month . '-01 00:00:00\' AND invoice.date <= \'20' . $year . '-' . $month . '-31 23:59:59\' AND invoice.status = 1 ORDER BY invoice.id, orders.id';
    $result = $conn->query($sql);
    if ($result->num_rows == 0) {
        echo '<script>alert("ไม่พบข้อมูลที่ต้องการค้นหา"); window.close();</script>';
        exit();
    }
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        if (date("d/m/Y", strtotime($row['InvoiceDue'] . "+543years")) != $loopDate_before) {
            if ($i) {
                echo '
<tr style="border-top:1px solid; border-bottom:1px solid; background:#DFE1DF;">
    <td colspan="2"></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td><b>ราคารวม</td>
    <td style="text-align:right; padding-right:15px;"><b>' . number_format($totalDate, 2) . '</td>
</tr>
                ';
            }
            $totalDate = 0;
            $loopDate = date("d/m/Y", strtotime($row['InvoiceDue'] . "+543years"));
        } else {
            $loopDate = NULL;
        }
        echo '<tr>' . "\n";
        echo '<td style="text-align:center;">' . $loopDate . '</td>';
        if ($row['InvoiceID'] != $loopInvoice_before) {
            echo '
<td style="text-align:center;">' . date("d/m/Y", strtotime($row['InvoiceDate'] . "+543years")) . '</td>
<td style="text-align:center;">I' . sprintf("%05d", $row['InvoiceID']) . '</td>
            ';
        } else {
            echo '
<td></td>
<td></td>
            ';
        }
        $totalDate += $row['OrdersTotal'];
        $Invoice++;
        $totalInvoice += $row['OrdersTotal'];
        echo '
<td style="text-align:center;">O' . sprintf("%05d", $row['OrdersID']) . '</td>
<td style="text-align:center;">' . date("d/m/Y", strtotime($row['OrdersDate'] . "+543years")) . '</td>
<td>' . $row['CustomerName'] . '</td>
<td>' . $row['CusTel'] . '</td>
<td style="text-align:right; padding-right:15px;">' . number_format($row['OrdersTotal'], 2) . '</td>
        ';
        echo '</tr>' . "\n";
        $loopDate_before = date("d/m/Y", strtotime($row['InvoiceDue'] . "+543years"));
        $loopInvoice_before = $row['InvoiceID'];
        $i++;
    }
    echo '
<tr style="border-top:1px solid; border-bottom:1px solid; background:#DFE1DF;">
    <td colspan="2"></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td><b>ราคารวม</td>
    <td style="text-align:right; padding-right:15px;"><b>' . number_format($totalDate, 2) . '</td>
</tr>
<tr>
    <td colspan="2"></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>
<tr style="border-bottom:1px solid;">
    <td colspan="2"></td>
    <td></td>
    <td></td>
    <td></td>
    <td><b>ราคารวมทั้งหมด</td>
    <td><b>' . $Invoice . ' รายการ</td>
    <td style="text-align:right; padding-right:15px;"><b>' . number_format($totalInvoice, 2) . '</td>
</tr>
<tr>
    <td colspan="2"></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>
<tr>
    <td colspan="8"><font color="red">หมายเหตุ เป็นหนี้ค้างชำระที่ออกในเดือน '.full_month($month) . ' พ.ศ. ' . ($year + (2000 + 543)).'</font></td>
</tr>
    ';
    echo '</table>' . "\n";
}

if ($type == 'ship_daily' && $date && $todate) {
    $day = substr($date, 0, 2);
    $month = substr($date, 2, 2);
    $year = substr($date, 4, 2);
    $today = substr($todate, 0, 2);
    $tomonth = substr($todate, 2, 2);
    $toyear = substr($todate, 4, 2);
    $total = 0;
    echo '<center>' . "\n";
    echo '<h3 style="padding-top:40px;">รายงานการจัดส่งสินค้าประจำวัน</h3>';
    echo '<h4>ตั้งแต่วันที่ ' . fulldate_thai($day . '-' . $month . '-' . ($year + 2000)) . ' ถึงวันที่ ' . fulldate_thai($today . '-' . $tomonth . '-' . ($toyear + 2000)) . '</h4><br>' . "\n";
    echo '<table width="1300px" border="0" align="center"><tr style="border-bottom:1px solid;"><td align="right" colspan="8">';
    echo 'วันที่พิมพ์ ' . fulldate_thai(date("d-m-Y")) . '<br>' . "\n" . '</td></tr>';
    echo '</center>' . "\n";
    echo '
            <tr style="border-bottom:1px solid; text-align:center">
                <th height="30px">วันที่จัดส่ง</th>
                <th class="text-center">หมายเลขพัสดุ</th>
                <th>รหัสการสั่งซื้อ</th>
                <th>ชื่อ-นามสกุล</th>
                <th>สถานที่จัดส่ง</th>
                <th>รายการ</th>
                <th>จำนวน</th>
                <th>หน่วยนับ</th>
            <tr/>
    ' . "\n";
    $sql = 'SELECT orders.dateship AS OrdersShip, orders.track AS OrdersTrack, orders.id AS OrdersID, customer.name AS CustomerName, orders.ship AS OrdersAddress FROM orders INNER JOIN customer ON orders.customer = customer.id WHERE orders.status = 4 AND date >= \'20' . $year . '-' . $month . '-' . $day . ' 00:00:00\' AND date <= \'20' . $toyear . '-' . $tomonth . '-' . $today . ' 23:59:59\' ORDER BY orders.dateship';
    $result = $conn->query($sql);
    if ($result->num_rows == 0) {
        echo '<script>alert("ไม่พบข้อมูลที่ต้องการค้นหา"); window.close();</script>';
        exit();
    }
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        //print_r($row);
        if (date("d/m/Y", strtotime($row['OrdersShip'] . "+543years")) != $loopDate_before) {
            if ($i) {
                echo '
                    <tr style="border-top:1px solid; border-bottom:1px solid; background:#DFE1DF">
                        <td colspan="6"></td>
                        <td style="text-align:right; padding-right:40px;"><b>รวม</b></td>
                        <td style="text-align:right; padding-right:40px;"><font><b>' . $totalDate . ' รายการ</td>
                    </tr>
                ';
            }
            $totalDate = 0;
            $loopDate = date("d/m/Y", strtotime($row['OrdersShip'] . "+543years"));
        } else {
            $loopDate = NULL;
        }
        $sqll = 'SELECT category.name AS CategoryName, COUNT(category.id) AS CategoryCount, category.unit AS CategoryUnit FROM orderlist INNER JOIN product ON orderlist.product = product.id INNER JOIN category ON product.category = category.id WHERE orderlist.orders = ' . $row['OrdersID'] . ' GROUP BY category.id ORDER BY category.id';
        $resultt = $conn->query($sqll);
        $j = 0;
        while ($roww = $resultt->fetch_assoc()) {
            // print_r($roww);
            echo '                <tr>' . "\n";
            if ($j) {
                echo '
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                ';
            } else {
                echo '
                    <td>' . $loopDate . '</td>
                    <td class="text-center">' . $row['OrdersTrack'] . '</td>
                    <td class="text-center">O' . sprintf("%05d", $row['OrdersID']) . '</td>
                    <td>' . $row['CustomerName'] . '</td>
                    <td>' . $row['OrdersAddress'] . '</td>
                ';
            }
            echo '
                    <td>' . $roww['CategoryName'] . '</td>
                    <td class="text-center">' . $roww['CategoryCount'] . '</td>
                    <td>' . $roww['CategoryUnit'] . '</td>
                    ';
            if ($j) {
                echo '
                    <td></td>
                    <td></td>
                ';
            } else {
                $totalDate++;
                $total++;
                // echo '
                //     <td>' . $OrdersStatus . '</td>
                //     <td style="text-align:right; padding-right:40px;"><font color="'. $color_order .'">' . number_format($row['OrdersTotal'], 2) . '</font></td>
                // ';
            }
            echo '</tr>' . "\n";
            $j++;
        }
        $loopDate_before = date("d/m/Y", strtotime($row['OrdersShip'] . "+543years"));
        $i++;
    }
    echo '
        <tr style="border-top:1px solid; border-bottom:1px solid; background:#DFE1DF;">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align:right; padding-right:40px;"><b><font>รวม</font></b></td>
            <td style="text-align:right; padding-right:40px;"><font><b>' . $totalDate . ' รายการ</td>
        </tr>
        <tr style="border-top:1px solid; border-bottom:1px solid; background:#DFE1DF;">
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align:right; padding-right:40px;"><b><font>รวมทั้งหมด</font></b></td>
        <td style="text-align:right; padding-right:40px;"><font><b>' . $total . ' รายการ</td>
    </tr>
    ';
    echo '          </table>' . "\n" . '<br>';
}

if ($type == 'pay_daily' && $date && $todate) {
    $day = substr($date, 0, 2);
    $month = substr($date, 2, 2);
    $year = substr($date, 4, 2);
    $today = substr($todate, 0, 2);
    $tomonth = substr($todate, 2, 2);
    $toyear = substr($todate, 4, 2);
    $total = 0;
    echo '<center>' . "\n";
    echo '<h3 style="padding-top:40px;">รายงานการรับชำระประจำวัน</h3>';
    echo '<h4>ตั้งแต่วันที่ ' . fulldate_thai($day . '-' . $month . '-' . ($year + 2000)) . ' ถึงวันที่ ' . fulldate_thai($today . '-' . $tomonth . '-' . ($toyear + 2000)) . '</h4><br>' . "\n";
    echo '<table width="1000px" border="0" align="center"><tr style="border-bottom:1px solid;"><td align="right" colspan="10">';
    echo 'วันที่พิมพ์ ' . fulldate_thai(date("d-m-Y")) . '<br>' . "\n" . '</td></tr>';
    echo '</center>' . "\n";
    echo '
            <tr style="border-bottom:1px solid; text-align:center;">
              <th>วันที่ชำระ</th>
              <th>เลขที่ใบเสร็จ</th>
              <th>ชื่อ-นามสกุล</th>
              <th>เลขที่ใบแจ้งหนี้</th>
              <th>วันที่กำหนดชำระ</th>
              <th>ยอดชำระ (บาท)</th>
              <th>ยอดชำระรวม (บาท)</th>
            <tr/>
    ' . "\n";
    $sql = 'SELECT invoice.paydate AS InvoicePay, receipt.id AS ReceiptID, customer.name AS customerName, receipt.amount AS ReceiptAmount FROM receipt INNER JOIN invoice ON receipt.id = invoice.receipt INNER JOIN orders ON invoice.id = orders.invoice INNER JOIN customer ON orders.customer = customer.id WHERE invoice.paydate >= \'20' . $year . '-' . $month . '-' . $day . ' 00:00:00\' AND invoice.paydate <= \'20' . $toyear . '-' . $tomonth . '-' . $today . ' 23:59:59\' GROUP BY receipt.id ORDER BY invoice.paydate';
    $result = $conn->query($sql);
    // echo $sql;
    // if ($result->num_rows == 0) {
    //     echo '<script>alert("ไม่พบข้อมูลที่ต้องการค้นหา"); window.close();</script>';
    //     exit();
    // }
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        // print_r($row);
        if (date("d/m/Y", strtotime($row['InvoicePay'] . "+543years")) != $loopDate_before) {
            if ($i) {
                echo '<tr><tr style="border-top:1px solid; border-bottom:1px solid; background:#DFE1DF;"><td colspan="5"></td><td style="text-align:right; padding-right:20px;"><b>รวม</td><td style="text-align:right; padding-right:20px;"><b>' . number_format($totalDate, 2) . '</td></tr>';
            }
            $totalDate = 0;
            $loopDate = date("d/m/Y", strtotime($row['InvoicePay'] . "+543years"));
        } else {
            $loopDate = NULL;
        }
        $sqll = 'SELECT invoice.id AS InvoiceID, invoice.duedate AS InvoiceDue, invoice.amount AS InvoiceAmount FROM invoice WHERE invoice.receipt = ' . $row['ReceiptID'] . ' ORDER BY invoice.id';
        //  echo $sqll;
        $resultt = $conn->query($sqll);
        $j = 0;
        while ($roww = $resultt->fetch_assoc()) {
            //  print_r($roww);
            echo '                <tr>' . "\n";
            if ($j) {
                echo '
                    <td></td>
                    <td></td>
                    <td></td>
                ';
            } else {
                echo '
                    <td>' . $loopDate . '</td>
                    <td style="text-align:center">R' . sprintf("%05d", $row['ReceiptID']) . '</td>
                    <td>' . $row['customerName'] . '</td>
                ';
            }
            echo '
                    <td align="center">I' . sprintf("%05d", $roww['InvoiceID']) . '</td>
                    <td align="center">' . date("d/m/Y", strtotime($roww['InvoiceDue'] . "+543years")) . '</td>
                    <td style="text-align:right; padding-right:20px;">' . number_format($roww['InvoiceAmount'], 2) . '</td>
                    ';
            if ($j) {
                echo '
                    <td></td>
                    <td></td>
                ';
            } else {
                echo '<td style="text-align:right; padding-right:20px;">' . number_format($row['ReceiptAmount'], 2) . '</td>';
                $totalDate += $row['ReceiptAmount'];
                $total += $row['ReceiptAmount'];
            }
            echo '</tr>' . "\n";
            $loopReceipt_before = $row['ReceiptID'];
            $j++;
        }
        $loopDate_before = date("d/m/Y", strtotime($row['InvoicePay'] . "+543years"));
        $i++;
    }
    echo '<tr style="border-top:1px solid; border-bottom:1px solid; background:#DFE1DF;"><td colspan="5"></td><td style="text-align:right; padding-right:20px;"><b>รวม</td><td style="text-align:right; padding-right:20px;"><b>' . number_format($totalDate, 2) . '</td></tr>';
    echo '<tr style="border-top: 1px solid; border-bottom:1px solid; background:#DFE1DF;"><td colspan="5"></td><td style="text-align:right; padding-right:20px;"><b>รวมทั้งหมด</td><td style="text-align:right; padding-right:20px;"><b>' . number_format($total, 2) . '</td></tr>';
    echo '          </table>' . "\n" . '<br>';
}

if ($type == 'pay_monthly' && $date) {
    $month = substr($date, 2, 2);
    $year = substr($date, 4, 2);
    $total = 0;
    echo '<center>' . "\n";
    echo '<h3 style="padding-top:40px;">รายงานการรับชำระประจำเดือน</h3>';
    echo '<h4>เดือน ' . full_month($month) . ' พ.ศ. ' . ($year + (2000 + 543)) . "</h4><br>" . "\n";
    echo '<table width="1000px" align="center"><tr style="border-bottom:1px solid;"><td align="right" colspan="10">';
    echo 'วันที่พิมพ์ ' . fulldate_thai(date("d-m-Y")) . '<br>' . "\n" . '</td></tr>';
    echo '</center>' . "\n";
    echo '
            <tr style="border-bottom:1px solid; text-align:center;">
              <th>วันที่ชำระ</th>
              <th>เลขที่ใบเสร็จ</th>
              <th>ชื่อ-นามสกุล</th>
              <th>เลขที่ใบแจ้งหนี้</th>
              <th>วันที่กำหนดชำระ</th>
              <th>ยอดชำระ (บาท)</th>
              <th>ยอดชำระรวม (บาท)</th>
            <tr/>
    ' . "\n";
    $sql = 'SELECT invoice.paydate AS InvoicePay, receipt.id AS ReceiptID, customer.name AS customerName, receipt.amount AS ReceiptAmount FROM receipt INNER JOIN invoice ON receipt.id = invoice.receipt INNER JOIN orders ON invoice.id = orders.invoice INNER JOIN customer ON orders.customer = customer.id WHERE invoice.paydate >= \'20' . $year . '-' . $month . '-01 00:00:00\' AND invoice.paydate <= \'20' . $year . '-' . $month . '-31 23:59:59\' GROUP BY receipt.id ORDER BY invoice.paydate';
    $result = $conn->query($sql);
    //echo $sql;
    // if ($result->num_rows == 0) {
    //     echo '<script>alert("ไม่พบข้อมูลที่ต้องการค้นหา"); window.close();</script>';
    //     exit();
    // }
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        // print_r($row);
        if (date("d/m/Y", strtotime($row['InvoicePay'] . "+543years")) != $loopDate_before) {
            $loopDate = date("d/m/Y", strtotime($row['InvoicePay'] . "+543years"));
            if ($i) {
                $totalDates[$i] = $loopDate_before . '_' . $totalDate;
                echo '<tr><tr style="border-top:1px solid;  border-bottom:1px solid; background:#DFE1DF;"><td colspan="5"></td><td style="text-align:right;"><b>รวม</td><td style="text-align:right; padding-right:15px;"><b>' . number_format($totalDate, 2) . '</td></tr>';
            }
            $totalDate = 0;
        } else {
            $loopDate = NULL;
        }
        $sqll = 'SELECT invoice.id AS InvoiceID, invoice.duedate AS InvoiceDue, invoice.amount AS InvoiceAmount FROM invoice WHERE invoice.receipt = ' . $row['ReceiptID'] . ' ORDER BY invoice.id';
        //  echo $sqll;
        $resultt = $conn->query($sqll);
        $j = 0;
        while ($roww = $resultt->fetch_assoc()) {
            //  print_r($roww);
            echo '                <tr>' . "\n";
            if ($j) {
                echo '
                    <td></td>
                    <td></td>
                    <td></td>
                ';
            } else {
                echo '
                    <td>' . $loopDate . '</td>
                    <td class="text-center">R' . sprintf("%05d", $row['ReceiptID']) . '</td>
                    <td>' . $row['customerName'] . '</td>
                ';
            }
            echo '
                    <td align="center">I' . sprintf("%05d", $roww['InvoiceID']) . '</td>
                    <td class="text-center">' . date("d/m/Y", strtotime($roww['InvoiceDue'] . "+543years")) . '</td>
                    <td style="text-align:right; padding-right:10px;">' . number_format($roww['InvoiceAmount'], 2) . '</td>
                    ';
            if ($j) {
                echo '
                    <td></td>
                    <td></td>
                ';
            } else {
                echo '<td style="text-align:right; padding-right:15px;">' . number_format($row['ReceiptAmount'], 2) . '</td>';
                $totalDate += $row['ReceiptAmount'];
                $total += $row['ReceiptAmount'];
            }
            echo '</tr>' . "\n";
            $loopReceipt_before = $row['ReceiptID'];
            $j++;
        }
        $loopDate_before = date("d/m/Y", strtotime($row['InvoicePay'] . "+543years"));
        $i++;
    }
    $totalDates[$i] = $loopDate_before . '_' . $totalDate;
    echo '<tr style="border-top:1px solid; border-bottom:1px solid; background:#DFE1DF;"><td colspan="5"></td><td style="text-align:right;"><b>รวม</td><td style="text-align:right; padding-right:15px;"><b>' . number_format($totalDate, 2) . '</td></tr>';
    echo '<tr style="border-top: 1px solid; border-bottom:1px solid; background:#DFE1DF;"><td colspan="5"></td><td style="text-align:right;"><b>รวมทั้งหมด</td><td style="text-align:right; padding-right:15px;"><b>' . number_format($total, 2) . '</td></tr>';
    echo '          </table>' . "\n" . '<br>';
    // print_r($totalDates);
    echo '<div class="container" style="border:1px solid;">
<canvas id="myChart" width="400" height="400"></canvas>
<script>
var ctx = document.getElementById("myChart").getContext("2d");
var myChart = new Chart(ctx, {
    type: "bar",
    data: {
        labels: [';
    $i = 0;
    foreach ($totalDates as $value) {
        if ($i) {
            echo ', ';
        }
        $value_txt = explode('_', $value);
        echo '"' . $value_txt[0] . '"';
        $i++;
    }
    echo '],
        datasets: [{
            label: "ยอดรับชำระ (บาท)",
            data: [';
    $i = 0;
    foreach ($totalDates as $value) {
        if ($i) {
            echo ', ';
        }
        $value_txt = explode('_', $value);
        echo '"' . $value_txt[1] . '"';
        $i++;
    }
    echo '],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    callback: function (value) {
                        return numeral(value).format(\' 0,0,.00\')
                    }
                }
            }]
        },
        tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                    let label = data.labels[tooltipItem.index];
                    let value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                    return "ยอดชำระ (บาท) : " + numeral(value).format(\' 0,0,.00\');
                }
            }
        }
    }
});
</script>
</div>
    ';
}

if ($type == 'warranty_monthly' && $date) {
    $month = substr($date, 2, 2);
    $year = substr($date, 4, 2);
    $total = 0;
    echo '<center>' . "\n";
    echo '<h3 style="padding-top:40px;">รายงานการเปลี่ยนสินค้าประจำเดือน</h3>';
    echo '<h4>เดือน ' . full_month($month) . ' พ.ศ. ' . ($year + (2000 + 543)) . "</h4><br>" . "\n";
    echo '<table width="900px" align="center"><tr style="border-bottom:1px solid;"><td align="right" colspan="10">';
    echo 'วันที่พิมพ์ ' . fulldate_thai(date("d-m-Y")) . '<br>' . "\n" . '</td></tr>';
    echo '</center>' . "\n";
    echo '
            <tr style="border-bottom:1px solid;">
              <th>วันที่เปลี่ยน</th>
              <th>พนักงาน</th>
              <th>ประเภทสินค้า</th>
              <th>S/N เคลมเข้า</th>
              <th>S/N เคลมออก</th>
              <th>หมายเหตุ</th>
            <tr/>
    ' . "\n";
    $sql = 'SELECT warranty.id AS warranty_id, warranty.date AS Date, employee.name AS Employee, category.name AS Category, orderlist.product AS Old, warranty.new AS New, warranty.ps AS PS, category.id AS CategoryID FROM warranty 
    INNER JOIN employee ON warranty.employee = employee.id 
    INNER JOIN orderlist ON warranty.orderlist = orderlist.id 
    INNER JOIN product ON orderlist.product = product.id
    INNER JOIN category ON product.category = category.id WHERE warranty.date >= \'20' . $year . '-' . $month . '-01 00:00:00\' AND warranty.date <= \'20' . $year . '-' . $month . '-31 23:59:59\' ORDER BY warranty.date';
    $result = $conn->query($sql);
    //  echo $sql;
    if ($result->num_rows == 0) {
        echo '<script>alert("ไม่พบข้อมูลที่ต้องการค้นหา"); window.close();</script>';
        exit();
    }
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . date("d/m/Y", strtotime($row['Date'] . "+543years")) . '</td>';
        echo '<td>' . $row['Employee'] . '</td>';
        echo '<td>' . $row['Category'] . '</td>';
        echo '<td>' . $row['Old'] . '</td>';
        echo '<td>' . $row['New'] . '</td>';
        echo '<td>' . $row['PS'] . '</td>';
        echo '</tr>';
        $i++;
        $category_count[$row['CategoryID']] += 1;
    }
    // print_r($category_count);
    echo '<tr style="border-top: 1px solid; border-bottom:1px solid; background:#DFE1DF;"><td colspan="4" >';
    echo '<td style="text-align:right;"><b>จำนวนรวม</td>';
    echo '<td style="padding-left:10px;"><b>' . $i . ' รายการ</td>';
    echo '</tr>';
    echo '</table><br>';
    echo '
<div class="container" style="border:1px solid;">
<canvas id="myChart" width="300" height="200"></canvas>
<script>
var ctx = document.getElementById("myChart").getContext("2d");
var myChart = new Chart(ctx, {
    type: "bar",
    data: {
        labels: [';
    $i = 0;
    foreach ($category_count as $key => $value) {
        if ($i) {
            echo ', ';
        }
        $sql = 'SELECT * FROM category WHERE id = ' . $key;
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        echo '\'' . $row['name'] . '\'';
        $i++;
    }
    echo '],
        datasets: [{
            label: "จำนวนเคลม",
            data: [';
    $i = 0;

    foreach ($category_count as $key => $value) {
        if ($i) {
            echo ', ';
        }
        echo '"' . $value . '"';
        $i++;
    }
    echo '],
            backgroundColor: "rgb(54,152,235,0.3)",
            borderWidth: 1,
       
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    autoSkip: true,
                    maxTicksLimit: 10,
                    stepSize: 1
                }
            }]
        }
    }
});
</script>
</div>
    ';
}

?>