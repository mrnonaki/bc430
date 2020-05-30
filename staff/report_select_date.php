<head>
    <?php

    if (!isset($_GET['report_name'])) {
        header("location: index.php");
        exit();
    }

    switch ($_GET['report_name']) {
        case 'orders_daily':
            $report_name = "รายงานการสั่งซื้อประจำวัน";
            $report_file = "report.php?type=orders_daily";
          //  $type = "orders_daily";
            break;
        case 'deliver_date':
            $report_name = "รายงานการจัดส่งสินค้าประจำวัน";
            $report_file = "report.php?type=ship_daily";
            break;
        case 'payment_date':
            $report_name = "รายงานการรับชำระประจำวัน";
            $report_file = "report.php?type=pay_daily";
            break;
        default:
            echo "<script>window.location.assign('index.php');</script>";
            exit();
    }

    ?>
    <title> <?= $report_name ?> | Food Order System</title>
    <link rel="shortcut icon" href="favicon.ico" />

    <?php
    require("header.php");
    require("../config.php");
    ?>
    <script>
        $(function() {


            var start_report = new Date(2019, 4, 1);
            var end_report = new Date(start_report.getFullYear() + 10, 11, 32);
            // Report Selector
            $('.datepicker-report').datepicker({
                language: 'th-th', //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                format: 'dd/mm/yyyy',
                disableTouchKeyboard: true,
                todayBtn: false,
                clearBtn: false,
                closeBtn: false,
                //daysOfWeekDisabled: [0],
                endDate: end_report,
                startDate: start_report,
                autoclose: true, //Set เป็นปี พ.ศ.
                inline: true
            }) //กำหนดเป็นวันปัจุบัน       
        });
    </script>
</head>

<body>
    <?php

    ?>
    <div class="container" style="padding-top: 55px; width:60%">
        <h1 class="page-header text-left"><?= $report_name ?></h1>
        <hr>
        <div class="row" style="padding-top:15px;">
            <form name="report" action="<?= $report_file ?>" method="POST" target="_blank">
                <label class=" col-md-8 text-left" style="padding-top:5px;">ตั้งแต่วันที่ :<font color="red">*</font> </label>
                <div class="col-md-13">
                    <input class="form-control datepicker-report" autocomplete="off" required name="date" type="text">
                </div>
                <label class="control-label col-md-8 text-left" style="padding-top:5px;">ถึงวันที่ :<font color="red">*</font> </label>
                <div class="col-md-13">
                    <input class="form-control datepicker-report" autocomplete="off" required name="todate" type="text">
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