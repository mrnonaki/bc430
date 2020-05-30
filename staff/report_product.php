<?php
require '../config.php';
if (!isset($_SESSION)) {
    session_start();
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

?>
<html>

<head>
    <link href="../css/bootstrap-4.3.1.css" rel="stylesheet">
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap-4.3.1.js"></script>
    <style type="text/css">
        @page {
            size: auto;
        }

        .head {
            margin-top: 40px;
            padding-bottom: 30px;
        }

        td {
            height: 30px !important;
        }

        th {
            height: 35px;
            text-align: center;
        }

        .container {
            padding-bottom: 20px;
        }
    </style>
    <title>รายงานสินค้าแยกตามประเภท</title>
</head>

<body>
    <div class="container">
        <h2 class="head text-center">รายงานสินค้าแยกประเภท</h2>
        <table border="0" align="center" width="95%">
            <tr style="border-bottom:1px solid;">
                <td colspan="7" align="right"><b><?= fulldate_thai(date('d-m-Y')) ?></b></td>
            </tr>
            <tr style="border-bottom:1px solid;">
                <th style="width:15%;">รหัสประเภท</th>
                <th style="text-align:left; width:20%">ชื่อประเภทรุ่น</th>
                <th style="width:25%;">ราคาต่อหน่วย [บาท]</th>
                <th style="width:10%"> หน่วยนับ</th>
                <th style="text-align:center; width:20%;">S/N</th>
                <th style="text-align:center; width:50%;">วันที่เข้าระบบ</th>
                <th>สถานะ</th>
            </tr>
            <?php
            $sql_category = "SELECT * FROM category";
            $result_category = $conn->query($sql_category);

            $sum_total = $sum_1 = $sum_2 = $sum_3 = $sum_4 = $sum_5 = $sum_6 = 0;

            while ($row_cat = $result_category->fetch_array()) {
                $count_row = 1;
            ?>
                <tr>
                    <td align="center">T<?= sprintf("%05d", $row_cat['id']) ?></td>
                    <td><?= $row_cat['name'] ?></td>
                    <?php
                    echo "<td style='text-align:right; padding-right:20px;'>" . number_format($row_cat['price'], 2) . "</td>";
                    echo "<td style='text-align:center;'>" . $row_cat['unit'] . "</td>";
                    $sql_product = "SELECT * FROM product 
                            WHERE category = '" . $row_cat['id'] . "' ORDER BY datein DESC;
                            ";
                    $result_product = $conn->query($sql_product);
                    while ($row_product = $result_product->fetch_array()) {
                        if ($count_row > 1) {
                            echo "</tr><tr><td colspan='4'>";
                        }

                        switch ($row_product['status']) {
                            case 1:
                                $status = "<b><font color='#4CD267'>พร้อมขาย</font></b>";
                                $sum_1++;
                                break;
                            case 2:
                                $status = "<b><font color='#FBB70F'>รอชำระ</font></b>";
                                $sum_2++;
                                break;
                            case 3:
                                $status = "<b><font color='#C058FE'>รอจัดส่ง</font></b>";
                                $sum_3++;
                                break;
                            case 4:
                                $status = "<b><font color='#00953A'>ขายแล้ว</font></b>";
                                $sum_4++;
                                break;
                            case 5:
                                $status = "<b><font color='#00B2D3'>เคลมเข้า</font></b>";
                                $sum_5++;
                                break;
                            case 6:
                                $status = "<b><font color='#F26905'>เคลมออก</font></b>";
                                $sum_6++;
                                break;
                            default:
                                $status = NULL;
                        }
                        echo "<td style='text-align:center;'>" . $row_product['id'] . "</td>";
                        echo "<td style='text-align:center;'>" . date("d/m/Y", strtotime($row_product['datein']."+ 543 Years")) . "</td>";
                        echo "<td align='center'>" . $status . "</td>";
                        $count_row++;
                        $sum_total++;
                    }
                    echo "</tr>";
                    ?>
                <tr style="background: #DFE1DF; border-bottom:1px solid;">
                    <td colspan="5"></td>
                    <td align="right"><b>รวม</b></td>
                    <td style="text-align:right; padding-right:25px;"><b><?= $count_row - 1. ?>&nbsp;&nbsp;&nbsp;รายการ</b></td>
                </tr>
            <?php
            }
            ?>
            <tr>
                <td colspan="4"></td>
                <td colspan="2" align="right"><b>รวมทั้งหมด</b></td>
                <td style="text-align:right; padding-right:25px;"><b><?= $sum_total ?>&nbsp;&nbsp;&nbsp;รายการ</b></td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td colspan="2" align="right"><b>
                        <font color='#4CD267'>รวมพร้อมขาย</font>
                    </b></td>
                <td style="text-align:right; padding-right:25px;"><b>
                        <font color='#4CD267'><?= $sum_1 ?>&nbsp;&nbsp;&nbsp;รายการ</font>
                    </b></td>
            </tr>
            <tr style="">
                <td colspan="4"></td>
                <td colspan="2" align="right"><b>
                        <font color='FBB70F'>รวมรอชำระ</font>
                    </b></td>
                <td style="text-align:right; padding-right:25px;"><b>
                        <font color='FBB70F'><?= $sum_2 ?>&nbsp;&nbsp;&nbsp;รายการ</font>
                    </b></td>
            </tr>
            <tr style="">
                <td colspan="4"></td>
                <td colspan="2" align="right"><b>
                        <font color='C058FE'>รวมรอส่ง</font>
                    </b></td>
                <td style="text-align:right; padding-right:25px;"><b>
                        <font color='C058FE'><?= $sum_3 ?>&nbsp;&nbsp;&nbsp;รายการ</font>
                    </b></td>
            </tr>
            <tr style="">
                <td colspan="4"></td>
                <td colspan="2" align="right"><b>
                        <font color='00953A'>รวมขายแล้ว</font>
                    </b></td>
                <td style="text-align:right; padding-right:25px;"><b>
                        <font color='00953A'><?= $sum_4 ?>&nbsp;&nbsp;&nbsp;รายการ</font>
                    </b></td>
            </tr>
            <tr style="">
                <td colspan="4"></td>
                <td colspan="2" align="right"><b>
                        <font color='00B2D3'>รวมเคลมเข้า</font>
                    </b></td>
                <td style="text-align:right; padding-right:25px;"><b>
                        <font color='00B2D3'><?= $sum_5 ?>&nbsp;&nbsp;&nbsp;รายการ</font>
                    </b></td>
            </tr>
            <tr style="border-bottom:1px solid">
                <td colspan="4"></td>
                <td colspan="2" align="right"><b>
                        <font color='F26905'>รวมเคลมออก</font>
                    </b></td>
                <td style="text-align:right; padding-right:25px;"><b>
                        <font color='F26905'><?= $sum_6 ?>&nbsp;&nbsp;&nbsp;รายการ</font>
                    </b></td>
            </tr>
        </table>
    </div>


</body>

</html>