<?php
require 'config.php';
if ($_GET['type'] == 'addCart') {
  session_start();
  $sql = 'SELECT * FROM category WHERE id = ' . $_GET['id'];
  $result = $conn->query($sql);
  $row = $result ? $result->fetch_assoc() : NULL;
  foreach ($_SESSION['cart'] as $cart) {
    if ($cart['id'] == $_GET['id']) {
      $quantity = $cart['quantity'];
    }
  }
  echo '
<div class="modal fade" id="addCart" tabindex="-1" role="dialog" aria-labelledby="addCartLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="addCart" name="addCart" action="cart.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="type" value="addCart">
      <input type="hidden" name="id" value="' . $row['id'] . '">
      <input type="hidden" name="name" value="' . $row['name'] . '">
      <input type="hidden" name="price" value="' . $row['price'] . '">
      <input type="hidden" name="ship" value="' . $row['ship'] . '">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addCartLabel">รายละเอียดสินค้า</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table align="center" class="table table-borderless" style="width:55%">
            <tr>
              <td class="text-center" colspan=2><img src="/images/category/' . $row['id'] . '.jpg" class="img-fluid"></td>
            </tr>
            <tr>
              <td class="text-right" style="width:30%">ชื่อ:</td>
              <td>' . $row['name'] . '</td>
            </tr>
            <tr>
              <td class="text-right">ราคา:</td>
              <td>' . number_format($row['price'], 2) . ' </td>
              <td>บาท</td>
            </tr>
            <tr>
              <td class="text-right">ค่าส่ง:</td>
              <td>' . number_format($row['ship'], 2) . '</td>
              <td>บาท</td>
            </tr>
            <tr>
              <td class="text-right">พร้อมขาย:</td>
              <td>' . $row['ready'] . ' ' . $row['unit'] . '</td>
            </tr>
            <tr>
              <td class="text-center" colspan=2>สั่งซื้อ</td>
            </tr>
            <tr>
              <td class="text-center" colspan=2><input type="number" name="quantity" id="quantity" min="1" value="' . $quantity . '" required> * ' . $row['unit'] . '</td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success">ยืนยัน</button>
            <button type="button" class="btn btn-warning" onclick="loadModal(\'addCart\', ' . $row['id'] . ')">ล้างค่า</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
        </div>
      </div>
    </form>
  </div>
</div>
  ';
}

if ($_GET['type'] == 'addOrder') {
  echo '
<div class="modal fade" id="addOrder" tabindex="-1" role="dialog" aria-labelledby="addOrderLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="addOrder" name="addOrder" action="orderlist.php" method="post">
      <input type="hidden" name="type" value="addOrder">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addOrderLabel">ยืนยันการสั่งซื้อสินค้า</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="col-md-7">
            ต้องการสั่งซื้อสินค้าใช่หรือไม่?
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">ยืนยัน</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
        </div>
      </div>
    </form>
  </div>
</div>
  ';
}
if ($_GET['type'] == 'showOrders') {
  echo '
<div class="modal fade" id="showOrders" tabindex="-1" role="dialog" aria-labelledby="showOrdersLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <form id="showOrders" name="showOrders" action="orderlist.php" method="post">
      <input type="hidden" name="type" value="showOrders">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="showOrdersLabel">รายละเอียดการสั่งซื้อสินค้า</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="embed-responsive embed-responsive-4by3">
            <iframe class="embed-responsive-item" src="peak.php?type=orders&id=' . $_GET['id'] . '"></iframe>
          </div>
        </div>
        <div class="modal-footer">
        </div>
      </div>
    </form>
  </div>
</div>
  ';
}
if ($_GET['type'] == 'payInvoice') {
  session_start();
  $id = $_GET['id'];

  $sql_invoice = "SELECT amount, date FROM invoice WHERE id = '$id'";
  $result_invoice = $conn->query($sql_invoice);
  $row_inv = $result_invoice->fetch_assoc();

  $date_inv = date("Y-m-d", strtotime($row_inv['date']));

  // $sum_price = 0;
  // $sum_ship = 0;
  // foreach ($_SESSION['cart'] as $cart) {
  //   $sql = 'SELECT * FROM category WHERE id = '.$cart['id'];
  //   $result = $conn->query($sql);
  //   $row = $result->fetch_assoc();
  //   if ($cart['quantity']) {
  //       $sum_price += $row['price'] * $cart['quantity'];
  //       $sum_ship += $row['ship'] * $cart['quantity'];
  //   }
  // }
  // $sum = $sum_price+$sum_ship;
  echo '
<div class="modal fade" id="payInvoice" tabindex="-1" role="dialog" aria-labelledby="payInvoiceLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="payInvoice" name="payInvoice" action="payInvoice.php" enctype="multipart/form-data" method="post" >
      <input type="hidden" name="type" value="payInvoice">
      <input type="hidden" name="id" value="' . $id . '">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="payInvoiceLabel">แจ้งชำระเงิน</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <div class="form-group">
        <div class="row">
        <label class="offset-md-1 col-md-5" style="margin-top:11px;">ยอดที่ต้องชำระ (บาท) :</font> </label>
        <div class="col-md-4 text-right" style="margin-top:9px;">
          <input type="text" hidden name="start_date" id="start_date" value="' . date("Y-m-d", strtotime($row_inv['date'])) . '">
          <input type="text" class="form-control" name="amount" value="' . number_format($row_inv['amount'], 2) . '" disabled>
        </div>
        <label class="offset-md-1 col-md-5" style="margin-top:12px;">แนบหลักฐาน :<font color="red">*</font> </label>
        <div class="col-md-4 text-right" style="margin-top:12px;">
        <input type="file" accept="image/x-png,image/gif,image/jpeg" required class="" name="detail" id="detail" >
        </div>
        <label class="offset-md-1 col-md-5" style="margin-top:11px;">วันที่โอน :<font color="red">*</font> </label>
        <div class="col-md-6 text-right" style="margin-top:9px;">
          <input type="text" class="form-control datepicker-payment" required onfocus="$(this).blur();" name="payment" id="payment" >
        </div>
        <label class="offset-md-1 col-md-5" style="margin-top:12px;">รายละเอียด : </label>
        <div class="col-md-6 text-right" style="margin-top:12px;">
        <textarea class="form-control" rows="3" name="ps"></textarea>
       <!-- <input type="text" class="form-control" name="payment"> -->
        </div>
        </div>
        </div>
        <div class="modal-footer">
          <button type="submit" onclick="if(confirm(\'ต้องการยืนยันการรับชำระใช่หรือไม่?\')) return true; else return false;" class="btn btn-success">ยืนยัน</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
        </div>
    </form>
  </div>
</div>
<script>
    $(function() {
            var start_report = new Date($("#start_date").val());
            var end_report = new Date();
            // Report Selector
            $(\'.datepicker-payment\').datepicker({
                language: \'th-th\', //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                format: \'dd/mm/yyyy\',
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
  ';
}
if ($_GET['type'] == 'downloadInvoice') {
  session_start();
  $id = $_GET['id'];
  // $sum_price = 0;
  // $sum_ship = 0;
  // foreach ($_SESSION['cart'] as $cart) {
  //   $sql = 'SELECT * FROM category WHERE id = '.$cart['id'];
  //   $result = $conn->query($sql);
  //   $row = $result->fetch_assoc();
  //   if ($cart['quantity']) {
  //       $sum_price += $row['price'] * $cart['quantity'];
  //       $sum_ship += $row['ship'] * $cart['quantity'];
  //   }
  // }
  // $sum = $sum_price+$sum_ship;
  echo '
<div class="modal fade" id="downloadInvoice" tabindex="-1" role="dialog" aria-labelledby="downloadInvoiceLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="downloadInvoice" name="downloadInvoice" target="_blank" action="peak.php" method="post">
      <input type="hidden" name="type" value="invoice">
      <input type="hidden" name="id" value="' . $id . '">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="downloadInvoiceLabel">ออกใบแจ้งหนี้</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="col-md-7">
            ต้องการออกใบแจ้งหนี้ใช่หรือไม่?
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">ยืนยัน</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
        </div>
      </div>
    </form>
  </div>
</div>
  ';
}
if ($_GET['type'] == 'getReceipt') {
  echo '
  <div class="modal fade" id="getReceipt" tabindex="-1" role="dialog" aria-labelledby="getReceiptLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <form id="getReceipt" name="getReceipt" action="orderlist.php" method="post">
        <input type="hidden" name="type" value="getReceipt">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="showOrdersLabel">ใบเสร็จรับเงิน - R' . sprintf("%05d", $_GET['id']) . '</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="embed-responsive embed-responsive-4by3">
              <iframe class="embed-responsive-item" src="../peak.php?type=receipt&id=' . $_GET['id'] . '"></iframe>
            </div>
          </div>
          <div class="modal-footer">
          </div>
        </div>
      </form>
    </div>
  </div>
    ';
}
if ($_GET['type'] == 'cancelInvoice') {
  session_start();
  $id = $_GET['id'];
  // $sum_price = 0;
  // $sum_ship = 0;
  // foreach ($_SESSION['cart'] as $cart) {
  //   $sql = 'SELECT * FROM category WHERE id = '.$cart['id'];
  //   $result = $conn->query($sql);
  //   $row = $result->fetch_assoc();
  //   if ($cart['quantity']) {
  //       $sum_price += $row['price'] * $cart['quantity'];
  //       $sum_ship += $row['ship'] * $cart['quantity'];
  //   }
  // }
  // $sum = $sum_price+$sum_ship;
  echo '
<div class="modal fade" id="cancelInvoice" tabindex="-1" role="dialog" aria-labelledby="cancelInvoiceLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="getReceipt" name="cancelInvoice" action="orderlist.php" method="post">
      <input type="hidden" name="type" value="cancelInvoice">
      <input type="hidden" name="id" value="' . $id . '">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cancelInvoiceLabel">ยกเลิกการสั่งซื้อ</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="col-md-7">
            ต้องการยกเลิกการสั่งซื้อใช่หรือไม่?
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">ยืนยัน</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
        </div>
      </div>
    </form>
  </div>
</div>
  ';
}
