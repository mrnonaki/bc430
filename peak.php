<?php
session_start();
require 'config.php';
include './peak/baht_text.php';
if (isset($_POST['id'])) {
    $id = $_POST['id'];
} else if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = NULL;
}
if (isset($_POST['type'])) {
    $type = $_POST['type'];
} else if (isset($_GET['type'])) {
    $type = $_GET['type'];
} else {
    $type = NULL;
}
if ($id && $type) {
    if ($type == 'orders') {
        $pageTH = 'ใบสั่งซื้อ';
        $pageEN = 'Purchase Order';
        $ordersID = $id;
        $pageID = 'O' . sprintf("%05d", $id);
        $sql = 'SELECT * FROM orders WHERE id = ' . $ordersID;
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $customerID = $row['customer'];
        $employeeID = $row['employee'];
        $ordersAddress = $row['ship'];
        $ordersDiscount = $row['discount'];
        $date = date("d/m/Y", strtotime($row['date'] . "+543years"));
        $duedate = date("d/m/Y", strtotime($row['date'] . "+543years+7days"));
    }
    if ($type == 'invoice') {
        $pageTH = 'ใบแจ้งหนี้';
        $pageEN = 'Invoice';
        $invoiceID = $id;
        $pageID = 'I' . sprintf("%05d", $id);
        $sql = 'SELECT invoice.date AS date, invoice.employee AS employee, invoice.duedate AS duedate, invoice.ps AS ps, orders.customer AS customer FROM invoice INNER JOIN orders ON invoice.id = orders.invoice WHERE invoice.id = ' . $invoiceID;
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $customerID = $row['customer'];
        $employeeID = $row['employee'];
        $ps = $row['ps'];
        $date = date("d/m/Y", strtotime($row['date'] . "+543years"));
        $duedate = date("d/m/Y", strtotime($row['duedate'] . "+543years"));
    }
    if ($type == 'receipt') {
        $pageTH = 'ใบเสร็จรับเงิน';
        $pageEN = 'Receipt';
        $receiptID = $id;
        $pageID = 'R' . sprintf("%05d", $id);
        $sql = 'SELECT receipt.date AS date, receipt.employee AS employee, invoice.payment AS payment, invoice.paydate AS paydate, orders.customer AS customer FROM receipt INNER JOIN invoice ON receipt.id = invoice.receipt INNER JOIN orders ON invoice.id = orders.invoice WHERE receipt.id = ' . $receiptID;
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $customerID = $row['customer'];
        $employeeID = $row['employee'];
        $ps = 'วันที่ชำระ : '.$row['payment'].'<br>วันเวลาแจ้งชำระ : '.date("d/m/Y", strtotime($row['paydate'] . "+543years"));
        $date = date("d/m/Y", strtotime($row['date'] . "+543years"));
    }

    $sql = 'SELECT * FROM customer WHERE id = ' . $customerID;
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $customerEmail = $row['email'];
    $customerName = $row['name'];
    $customerTel = $row['tel'];
    $customerAddress = $row['address_no'] . ', ' . $row['address_district'] . ', ' . $row['address_amphoe'] . ', ' . $row['address_province'] . ', ' . $row['address_zipcode'];

    if ($employeeID) {
        $sql = 'SELECT * FROM employee WHERE id = ' . $employeeID;
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $employeeName = $row['name'];
    } else {
        $employeeName = 'Online';
    }
}
?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script src="./peak/nr-1167.min.js"></script>
    <script src="./peak/jquery.min.js"></script>
    <script src="./peak/semantic.min.js"></script>
    <script src="./peak/PeakUserInterface.js"></script>
    <link rel="stylesheet" href="./peak/font-awesome.css" type="text/css" media="all">
    <link rel="stylesheet" href="./peak/font-awesome.min.css" type="text/css" media="all">
    <link href="./peak/semantic.min.css" rel="stylesheet" type="text/css" media="all">
    <link href="./peak/onlineview.css" rel="stylesheet">
    <link href="./peak/icomoon-style.css" rel="stylesheet">
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style data-styled="" data-styled-version="4.2.0"></style>
</head>

<body class="onlineview">
    <div class="pusher">
        <header id="header" class="onlineview-header">
            <div class="onlineview-header-top">
                <div class="onlineview-headertop-content">
                    <div class="ui teal buttons">
                        <div class="ui button button-red" onclick="printDiv(&#39;printableArea&#39;)">พิมพ์เอกสาร</div>
                    </div>
                </div>
            </div>
        </header>
        <div class="a4-onlineview">
            <div class="invoice-page iv-olview a4-page-print">
                <div id="emptydiv" style="display:none;width:697.69px;height:90px;"></div>
                <header>
                    <h1><?php echo $pageTH . '<br>' . $pageEN; ?></h1>
                    <section class="invoice-page-detail">
                        <p id="fpheadertype" class="pheadersetdoc">&nbsp;</p>
                        <p id="fpheadersetdoc" class="pheadertype">( ต้นฉบับ / original )</p>
                    </section>
                    <span class="onlineview-logo-qrcode-header">
                        <img id="logo" src="./peak/logo_NjQ4MzU=.png">
                    </span>
                </header>
                <div class="clear"></div>
                <article name="contactDiv">
                    <section class="customer-left">
                        <address class="edit-contact-heading">
                            <section class="customer-heading">
                                <strong>
                                    <p>ลูกค้า</p> /
                                </strong>
                                <p>Customer</p>
                            </section>
                            <section class="customer-detail-iv">
                                <p><?php echo $customerName; ?></p>
                            </section>
                        </address>
                        <address>
                            <section class="customer-heading"><strong>
                                    <p>ที่อยู่</p> /
                                </strong>
                                <p>Address</p>
                            </section>
                            <section class="customer-detail-iv">
                                <p> <?php echo $customerAddress; ?>
                                </p>
                            </section>
                        </address>
                        <address class="customer-tax-main">
                            <section class="email-heading">
                                <strong>
                                    <p>E:</p>
                                </strong>
                            </section>
                            <section class="email-detail">
                                <p><?php echo $customerEmail; ?></p>
                            </section>
                            <section class="tel-heading"><strong>
                                    <p>T:</p>
                                </strong></section>
                            <section class="header-tel-detail">
                                <p><?php echo $customerTel; ?></p>
                            </section>
                        </address>
                    </section>
                    <aside>
                        <section class="number-section">
                            <section class="number-heading">
                                <strong>
                                    <p>เลขที่ /</p>
                                </strong>
                                <p>No.</p>
                            </section>
                            <section class="number-detail">
                                <p class="lbTransactionNumber" style="position:absolute;"><?php echo $pageID; ?></p>
                            </section>
                        </section>
                        <section class="number-section">
                            <section class="number-heading">
                                <strong>
                                    <p>วันที่ /</p>
                                </strong>
                                <p>Issue</p>
                            </section>
                            <section class="number-detail">
                                <p class="makedateandduedate">
                                    <?php echo $date; ?>
                                </p>
                            </section>
                        </section>
                        <?php
                        if ($type == 'orders' || $type == 'invoice') {
                            echo '
                        <section class="number-section">
                            <section class="number-heading">
                                <strong>
                                    <p>ครบกำหนด/</p>
                                </strong>
                                <p>Valid</p>
                            </section>
                            <section class="number-detail">
                                <p class="makedateandduedate">
                                ' . $duedate . '
                                </p>
                            </section>
                        </section>
        ';
                        }
        //                 if ($type == 'receipt') {
        //                     echo '
        //                 <section class="number-section">
        //                     <section class="number-heading">
        //                         <strong>
        //                             <p>อ้างอิง/</p>
        //                         </strong>
        //                         <p>Ref.</p>
        //                     </section>
        //                     <section class="number-detail">
        //                         <p class="makedateandduedate">
        //                         I' . sprintf("%05d", $invoiceID) . '
        //                         </p>
        //                     </section>
        //                 </section>
        // ';
        //                 }
                        ?>
                    </aside>
                </article>
                <article name="issuerDiv" class="article-second">
                    <section class="issuer-section">
                        <section class="issuer-heading">
                            <strong>
                                <p class="is-nowarp">ผู้ออก</p>
                            </strong>
                            <br>
                            <p class="is-nowarp">issuer</p>
                        </section>
                        <section class="issuer-detail">
                            <p>บริษัท เน็กซ์ฮอป จำกัด (สำนักงานใหญ่)<br>เลขที่ 320 ถนนทุ่งรี ตำบลคอหงส์ อำเภอหาดใหญ่ จังหวัดสงขลา 90110</p>
                        </section>
                    </section>
                    <aside class="tax-section">
                        <section class="customer-tax-name">
                            <strong>
                                <p>จัดเตรียมโดย / </p>
                            </strong>
                            <p>Prepared by</p>
                        </section>
                        <section class="customer-tax-detailname">
                            <p>
                                <?php echo $employeeName ?>
                            </p>
                        </section>
                        <section class="tel-heading">
                            <strong>
                                <p>T:</p>
                            </strong>
                        </section>
                        <section class="tel-detail">
                            <p>02-1073435</p>
                        </section>
                        <section class="email-heading">
                            <strong>
                                <p>E:</p>
                            </strong>
                        </section>
                        <section class="email-detail style-resize-text auto-resize-text">
                            <span style="font-size: 20px;">hello@nexthop.co.th</span>
                        </section>
                        <div class="clear"></div>
                        <section class="web-heading">
                            <strong>
                                <p>W:</p>
                            </strong>
                        </section>
                        <section class="web-detail">
                            <p>https://nexthop.co.th</p>
                        </section>
                    </aside>
                </article>
                <table class="table-detail">
                    <thead>
                        <tr>
                            <th class="table-detail-number">
                                <strong>รหัส</strong>
                                <br><span>ID no.</span>
                            </th>
                            <th class="table-detail-description border-tbl-left">
                                <strong>คำอธิบาย</strong>
                                <br><span>Description</span>
                            </th>
                            <th class="table-detail-quantity">
                                <strong>จำนวน</strong>
                                <br><span>Quantity</span>
                            </th>
                            <th class="table-detail-unit" name="unitColumn">
                                <strong>ค่าจัดส่ง</strong>
                                <br><span>Shipping</span>
                            </th>
                            <th class="table-detail-unitprice align-left">
                                <strong>ราคาต่อหน่วย</strong>
                                <br><span>Unit Price</span>
                            </th>
                            <th class="table-detail-taxamount border-tbl-left">
                                <strong>มูลค่า</strong>
                                <br>
                                <span>Amount</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($type == 'invoice') {
                            $i = 0;
                            $sumRate = 0;
                            $sumShip = 0;
                            $sumDiscount = 0;
                            $sql = 'SELECT * FROM orders WHERE invoice =' . $invoiceID;
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()) {
                                $rateship = explode(',', orderlist($type, $row['id']));
                                $sumRate += $rateship[0];
                                $sumShip += $rateship[1];
                                $i += $rateship[2];
                            }
                            $sql = 'SELECT SUM(discount) AS sumDiscount FROM orders WHERE invoice =' . $invoiceID;
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();
                            $sumDiscount += $row['sumDiscount'];
                        }
                        if ($type == 'receipt') {
                            $i = 0;
                            $sumRate = 0;
                            $sumShip = 0;
                            $sumDiscount = 0;
                            $sql = 'SELECT * FROM invoice WHERE receipt =' . $receiptID;
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()) {
                                $sqll = 'SELECT * FROM orders WHERE invoice = ' . $row['id'];
                                $resultt = $conn->query($sqll);
                                while ($roww = $resultt->fetch_assoc()) {
                                    $rateship = explode(',', orderlist($type, $roww['id']));
                                    $sumRate += $rateship[0];
                                    $sumShip += $rateship[1];
                                    $i += $rateship[2];
                                }
                                $sqll = 'SELECT SUM(discount) AS sumDiscount FROM orders WHERE invoice =' . $row['id'];
                                $resultt = $conn->query($sqll);
                                $roww = $resultt->fetch_assoc();
                                $sumDiscount += $roww['sumDiscount'];
                            }
                        }
                        if ($type == 'orders') {
                        $rateship = explode(',', orderlist($type, $ordersID));
                            $sumRate = $rateship[0];
                            $sumShip = $rateship[1];
                            $i = $rateship[2] + 2;
                            $sumDiscount = $ordersDiscount;
                            echo '
            <tr>
            <td class="align-left" style="white-space:nowrap;font-size:16px;">&nbsp;</td>
            <td class="align-center" table-detail-description border-tbl-left is-acceptSlashN">จัดส่งที่</td>
            <td class="align-right" style="font-size:16px;">&nbsp;</td>
            <td class="align-center" name="unitColumn">&nbsp;</td>
            <td class="align-right">
                <p name="valueColumn">&nbsp;</p>
            </td>
            <td class="align-right border-tbl-left">
                <p name="valueColumn">&nbsp;</p>
            </td>
            </tr>
        ';
                            echo '
            <tr>
            <td class="align-left" style="white-space:nowrap;font-size:16px;">&nbsp;</td>
            <td class="align-center" table-detail-description border-tbl-left is-acceptSlashN">' . $ordersAddress . '</td>
            <td class="align-right" style="font-size:16px;">&nbsp;</td>
            <td class="align-center" name="unitColumn">&nbsp;</td>
            <td class="align-right">
                <p name="valueColumn">&nbsp;</p>
            </td>
            <td class="align-right border-tbl-left">
                <p name="valueColumn">&nbsp;</p>
            </td>
            </tr>
        ';
                        }
                        while ($i < 18) {
                            echo '
        <tr>
        <td class="align-left" style="white-space:nowrap;font-size:16px;">&nbsp;</td>
        <td class="align-left" table-detail-description border-tbl-left is-acceptSlashN">&nbsp;</td>
        <td class="align-right" style="font-size:16px;">&nbsp;</td>
        <td class="align-center" name="unitColumn">&nbsp;</td>
        <td class="align-right">
            <p name="valueColumn">&nbsp;</p>
        </td>
        <td class="align-right border-tbl-left">
            <p name="valueColumn">&nbsp;</p>
        </td>
        </tr>
        ';
                            $i++;
                        }
                        $total = $sumRate + $sumShip - $sumDiscount
                        ?>
                    </tbody>
                </table>
                <section class="total-footer">
                    <?php
                    if ($type == 'invoice') {
                        echo '
                    <ul>
                        <li><strong>หมายเหตุ /</strong> Remarks</li>
                        <li>
                            ' . $ps . '
                        </li>
                    </ul>
    ';
                    }
                    if ($type == 'receipt') {
                        echo '
                    <ul>
                        <li><strong>รายละเอียดการชำระ /</strong> Payment info</li>
                        <li>
                            ' . $ps . '
                        </li>
                    </ul>
    ';
                    }
                    ?>
                    <table class="grand-total">
                        <tbody>
                            <tr>
                                <td class="grand-total-desc"><strong>ราคาสุทธิ (บาท) /</strong> Total
                                    Amount</td>
                                <td class="align-right grand-total-number border-tbl-left">
                                    <p name="valueColumn"><?php echo (number_format($sumRate, 2, '.', ',')); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td class="grand-total-desc"><strong>ค่าจัดส่ง (บาท) /</strong> Shipping Cost</td>
                                <td class="align-right grand-total-number border-tbl-left">
                                    <p name="valueColumn"><?php echo (number_format($sumShip, 2, '.', ',')); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td class="grand-total-desc"><strong>ส่วนลด (บาท) /</strong> Discount</td>
                                <td class="align-right grand-total-number border-tbl-left">
                                    <p name="valueColumn"><?php echo (number_format($sumDiscount, 2, '.', ',')); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td class="grand-total-desc"><strong>จำนวนเงินรวมทั้งสิ้น (บาท) /</strong> Grand Total
                                </td>
                                <td class="align-right grand-total-number grand-total-txt border-tbl-left">
                                    <p name="valueColumn"><?php echo (number_format($total, 2, '.', ',')); ?>
                                    </p>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                    <div class="grand-total-txtbtm" style="border-bottom:1px solid #929292">
                        <div>จำนวนเงินรวมทั้งสิ้น</div>
                        <span style="font-size:16px;" name="valueColumn"><?php echo baht_text($total); ?></span>
                    </div>
                </section>
                <div class="btm-line"></div>
                </article>
                <?php
                if ($type == 'orders' | $type == 'invoice') {
                    echo '
                <article class="payment-footer clear-btm-line">
                    <section class="payment-hdd float-left"><strong>การชำระเงิน /</strong> Payment</section>
                    <div class="clear"></div>
                    <table class="tb-payment float-left">
                        <tbody>
                            <tr>
                                <th class="tb-payment-bank-name align-left">ธนาคาร</th>
                                <th class="tb-payment-account-name align-left">ชื่อบัญชี</th>
                                <th class="tb-payment-bank-number align-left">เลขที่บัญชี</th>
                            </tr>
                            <tr>
                                <td>• ไทยพาณิชย์</td>
                                <td>บริษัท เน็กซ์ฮอป จำกัด</td>
                                <td>4330223673</td>
                            </tr>
                        </tbody>
                    </table>
                </article>
                ';
                }
                if ($type == 'invoice' || $type == 'receipt') {
                    echo '
                <aside class="sign-section">
                    <div>
                        <section class="approved-by"><strong>อนุมัติโดย /</strong> Approved by</section>
                        ......................................................
                        <section class="d-date">
                            <p>('.$employeeName.')</p>
                        </section>
                    </div>
                    <div>
                        <section class="accepted-by"><strong>ผู้รับ /</strong> Accepted by</section>
                        ......................................................
                        <section class="d-date">
                            <p>('.$customerName.')</p>
                        </section>
                    </div>
                </aside>
        ';
                }
                ?>
                <div class="clear"></div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function printDiv(divName) {
            if ($('#aLinkCopy').hasClass('visible')) {
                $('#aLinkCopy').popup('hide');
                setTimeout(
                    function() {
                        window.print();
                    }, 1000);
            } else {
                window.print();
            }
        }
    </script>
    <link rel="stylesheet" href="./peak/invoice-style.css" type="text/css" media="all">

</body>

</html>
<?php
function orderlist($type, $ordersID)
{
    require 'config.php';
    $sumRate = 0;
    $sumShip = 0;
    $i = 0;
    $sql = 'SELECT category.id, category.name, category.unit FROM orderlist INNER JOIN product ON orderlist.product = product.id INNER JOIN category ON product.category = category.id WHERE orderlist.orders = ' . $ordersID . ' GROUP BY category';
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo '
            <tr>
            <td class="align-left" style="white-space:nowrap;font-size:16px;">T' . sprintf("%05d", $row['id']) . '</td>
            <td class="align-left" table-detail-description border-tbl-left is-acceptSlashN">';
        if ($type != 'orders') {
            echo ' [O' . sprintf("%05d", $ordersID) . '] ';
        }
        echo $row['name'] . ' ( ';
        $sqll = 'SELECT product.id FROM orderlist INNER JOIN product ON orderlist.product = product.id WHERE orderlist.orders = ' . $ordersID . ' AND product.category = ' . $row['id'];
        $resultt = $conn->query($sqll);
        while ($roww = $resultt->fetch_assoc()) {
            echo $roww['id'] . ' ';
        }
        echo ')';
        $sqll = 'SELECT orderlist.rate, orderlist.ship, COUNT(*) AS count FROM orderlist INNER JOIN product ON orderlist.product = product.id WHERE orderlist.orders = ' . $ordersID . ' AND product.category = ' . $row['id'];
        $resultt = $conn->query($sqll);
        $roww = $resultt->fetch_assoc();
        $sum = $roww['count'] * ($roww['rate'] + $roww['ship']);
        $sumRate += $roww['count'] * $roww['rate'];
        $sumShip += $roww['count'] * $roww['ship'];
        echo '
            </td>
            <td class="align-left" style="font-size:16px;">' . $roww['count'] . ' ' . $row['unit'] . '</td>
            <td class="align-right" name="unitColumn">' . number_format($roww['ship'],2) . '</td>
            <td class="align-right">
                <p name="valueColumn">' . number_format($roww['rate'], 2) . '</p>
            </td>
            <td class="align-right border-tbl-left">
                <p name="valueColumn">' . number_format($sum, 2, '.', ',') . '</p>
            </td>
            </tr>
            ';
        $i++;
    }
    return $sumRate . ',' . $sumShip . ',' . $i;
}
?>