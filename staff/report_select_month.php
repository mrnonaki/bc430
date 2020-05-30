<head>
    <?php

    if (!isset($_GET['report_name'])) {
        header("location: index.php");
        exit();
    }
    switch ($_GET['report_name']) {
        case 'order_month':
            $report_name = "รายงานการสั่งซื้อประจำเดือน";
            $report_file = "report.php?type=orders_monthly";
            $date_attr = "date"; // ฟิลด์ข้อมูล
            $date_table = "orders";
            break;
        case 'debt_month':
            $report_name = "รายงานหนี้ค้างชำระประจำเดือน";
            $report_file = "report.php?type=invoice_monthly";
            $date_attr = "date"; // ฟิลด์ข้อมูล
            $date_table = "invoice";
            break;
        case 'deliver_month':
            $report_name = "รายงานการจัดส่งสินค้าประจำวัน";
            $report_file = "report_deliver_month.php";
            $date_attr = "dateship"; // ฟิลด์ข้อมูล
            $date_table = "payment";
            break;
        case 'payment_month':
            $report_name = "รายงานการรับชำระประจำเดือน";
            $report_file = "report.php?type=pay_monthly";
            $date_attr = "date"; // ฟิลด์ข้อมูล
            $date_table = "receipt";
            break;
        case 'change_month':
            $report_name = "รายงานการเปลี่ยนสินค้าประจำเดือน";
            $report_file = "report.php?type=warranty_monthly";
            $date_attr = "date"; // ฟิลด์ข้อมูล
            $date_table = "orders";
            break;
        default:
            echo "<script>window.location.assign('index.php');</script>";
            exit();
    }

    ?>
    <title> <?= $report_name ?> | Food Order System</title>
    <link rel="shortcut icon" href="favicon.ico" />
</head>

<body>
    <?php
    require("header.php");
    require("../config.php");
    ?>
    <div class="container" style="padding-top: 55px; width:60%">
        <h1 class="page-header text-left"><?= $report_name ?></h1>
        <hr>

        <div class="row" style="padding-top:15px;">
            <form name="report" action="<?= $report_file ?>" method="POST" target="_blank">
                <label class=" col-md-5 text-left" style="padding-top:5px;">เดือน :<font color="red">*</font> </label>
                <div class="col-md-13">
                    <select name="month" id="month" class="form-control" required>
                        <option value="" selected disabled>-- กรุณาเลือกเดือนที่ต้องการ --</option>
                        <option value="01">มกราคม</option>
                        <option value="02">กุมภาพันธ์</option>
                        <option value="03">มีนาคม</option>
                        <option value="04">เมษายน</option>
                        <option value="05">พฤษภาคม</option>
                        <option value="06">มิถุนายน</option>
                        <option value="07">กรกฎาคม</option>
                        <option value="08">สิงหาคม</option>
                        <option value="09">กันยายน</option>
                        <option value="10">ตุลาคม</option>
                        <option value="11">พฤศจิกายน</option>
                        <option value="12">ธันวาคม</option>
                    </select>
                </div>
                <label class="control-label col-md-5 text-left" style="padding-top:5px;">ปี พ.ศ. :<font color="red">*</font> </label>
                <div class="col-md-13">
                    <select name="year" id="year" class="form-control" required>
                        <option value="" selected disabled>-- กรุณาเลือกปี พ.ศ. ที่ต้องการ --</option>
                        <?php

                        $sql_year = "SELECT DISTINCT year($date_attr) FROM $date_table WHERE $date_attr != '0000-00-00'";
                        $result_year = $conn->query($sql_year) or die($conn->error);

                        while ($row_year = $result_year->fetch_array()) {
                            echo "<option value='" . substr($row_year[0], 2, 2) . "' >" . ($row_year[0] + 543) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="row" style="padding-top:15px;">
                    <div class="col-md-2">
                        <input type="text" name="report_name" id="report_name" value="<?= $report_name ?>" hidden>
                        <button class="btn btn-primary" name="search" type="submit">ค้นหา</button>
                    </div>
                </div>
            </form>
        </div>
        <hr>
    </div>
    <?php
    require("footer.php");
    ?>
</body>