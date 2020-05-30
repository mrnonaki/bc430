<?php
session_start();
$page_url = basename($_SERVER['PHP_SELF']);
switch ($page_url) {
   case 'index.php':
      $page_name = 'หน้าแรก';
      break;
   case 'employee.php':
      $page_name = 'จัดการพนักงาน';
      break;
   case 'customer.php':
      $page_name = 'จัดการลูกค้า';
      break;
   case 'cart_staff.php':
      $page_name = 'ตะกร้าสั่งซื้อ';
      break;
   case 'multiple_orders.php':
      $page_name = 'ออกใบแจ้งหนี้';
      break;
   case 'category.php':
      $page_name = 'จัดการประเภท';
      break;
   case 'product.php':
      $page_name = 'จัดการสินค้า';
      break;
   case 'show_product.php':
      $page_name = 'ค้นหา/แสดงสินค้า';
      break;
   case 'select_customer.php':
      $page_name = 'ค้นหา/เลือกลูกค้า';
      break;
   case 'orderlist.php':
      $page_name = 'รายการสั่งซื้อ';
      break;
   case 'invoice.php':
      $page_name = 'ใบแจ้งหนี้';
      break;
   case 'receipt.php':
      $page_name = 'ใบเสร็จรับเงิน';
      break;
   case 'payment.php':
      $page_name = 'รับชำระ';
      break;
   case 'warranty.php':
      $page_name = 'การเปลี่ยน';
      break;
   case 'profile.php':
      $page_name = 'แก้ไขข้อมูลผู้ใช้';
      break;
   default:
      $page_name = 'Error';
}

if (isset($_COOKIE['emp_uid']) && !isset($_SESSION['emp_uid'])) {
   $uid = isset($_COOKIE['emp_uid']) ? $_COOKIE['emp_uid'] : NULL;
   $posid = strpos($uid, '-');
   $id = substr($uid, 0, $posid);
   $sql = 'SELECT * FROM employee WHERE id = \'' . $id . '\'';
   $result = $conn->query($sql);
   if ($result->num_rows != 0) {
      $row = $result->fetch_assoc();
      if ($uid == $row['id'] . '-' . md5($row['id'] . $row['username'] . $row['password'])) {
         $_SESSION['emp_uid'] = $row['id'];
         $_SESSION['emp_user'] = $row['username'];
         $_SESSION['emp_role'] = $row['role'];
         $_SESSION['ban'] = $row['ban'];
      }
   }
}

if (!isset($_SESSION['emp_uid'])) {
   echo "<script>window.location.assign('login.php');</script>";
   exit();
}

if (isset($_SESSION['cart'])) {
   $cart_count = 0;
   $cart_item = '';
   foreach ($_SESSION['cart'] as $cart) {
      $cart_count += $cart['quantity'];
      $cart_item = $cart_item . $cart['name'] . ' (' . $cart['quantity'] . ')<br>';
   }
} else {
   $cart_count = 0;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title><?php echo $page_name; ?> - ระบบจำหน่ายอุปกรณ์คอมพิวเตอร์</title>
   <link href="../css/bootstrap-4.3.1.css" rel="stylesheet">
   <script src="../js/jquery-3.3.1.min.js"></script>
   <script src="../js/popper.min.js"></script>
   <script src="../js/bootstrap-4.3.1.js"></script>
   <link href="../css/font-awesome.min.css" rel="stylesheet" />

   <script type="text/javascript" src="../js/date/js/bootstrap-datepicker.js"></script>
   <!-- thai extension -->
   <script type="text/javascript" src="../js/date/js/bootstrap-datepicker-thai.js"></script>
   <script type="text/javascript" src="../js/date/js/locales/bootstrap-datepicker.th.js"></script>
   <link href="../js/date/css/datepicker.css" rel="stylesheet" />

</head>

<body>
   <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <a class="navbar-brand" href="index.php"><img src="../images/logo.png"></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
         <ul class="navbar-nav mr-auto">
            <li class="nav-item<?php echo ($page_url == 'index.php') ? ' active' : '' ?>">
               <a class="nav-link" href="index.php">หน้าแรก</a>
            </li>
            <?php
            if ($_SESSION['emp_role'] == 2) {
            ?>
               <li class="nav-item<?php echo ($page_url == 'employee.php') ? ' active' : '' ?>">
                  <a class="nav-link" href="employee.php">จัดการพนักงาน</a>
               </li>
            <?php } ?>
            <li class="nav-item<?php echo ($page_url == 'customer.php') ? ' active' : '' ?>">
               <a class="nav-link" href="customer.php">จัดการลูกค้า</a>
            </li>
            <li class="nav-item dropdown<?php echo ($page_url == 'category.php' || $page_url == 'product.php') ? ' active' : '' ?>">
               <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  สินค้า
               </a>
               <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="category.php">จัดการประเภท</a>
                  <a class="dropdown-item" href="product.php">จัดการสินค้า</a>
                  <a class="dropdown-item" href="show_product.php">ค้นหา/แสดงสินค้า</a>
               </div>
            </li>
            <li class="nav-item<?php echo ($page_url == 'orderlist.php') ? ' active' : '' ?>">
               <a class="nav-link" href="orderlist.php">รายการสั่งซื้อ</a>
            </li>
            <li class="nav-item dropdown<?php echo ($page_url == 'invoice.php' || $page_url == 'receipt.php' || $page_url == 'payment.php') ? ' active' : '' ?>">
               <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  จัดการบัญชี
               </a>
               <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="invoice.php">รายการหนี้</a>
                  <a class="dropdown-item" href="receipt.php">รายการใบเสร็จรับเงิน</a>
               </div>
            </li>
            <li class="nav-item<?php echo ($page_url == 'warranty.php') ? ' active' : '' ?>">
               <a class="nav-link" href="warranty.php">การเปลี่ยน/ประกัน</a>
            </li>
            <!-- รายงาน -->
            <?php
            if ($_SESSION['emp_role'] == 2) {
            ?>
               <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     รายงาน
                  </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                     <a class="dropdown-item" href="report_select_date.php?report_name=orders_daily">รายงานการสั่งซื้อประจำวัน</a>
                     <a class="dropdown-item" href="report_select_month.php?report_name=order_month">รายงานการสั่งซื้อประจำเดือน</a>
                     <a class="dropdown-item" href="report_select_month.php?report_name=debt_month">รายงานหนี้ค้างชำระประจำเดือน</a>
                     <a class="dropdown-item" href="report_select_date.php?report_name=deliver_date">รายงานการจัดส่งสินค้าประจำวัน</a>
                     <a class="dropdown-item" href="report_select_date.php?report_name=payment_date">รายงานการรับชำระประจำวัน</a>
                     <a class="dropdown-item" href="report_select_month.php?report_name=payment_month">รายงานการรับชำระประจำเดือน</a>
                     <a class="dropdown-item" href="report_select_month.php?report_name=change_month">รายงานการเปลี่ยนสินค้าประจำเดือน</a>
                     <a class="dropdown-item" target="_blank" href="report_product.php">รายงานสินค้าแยกประเภท</a>
                  </div>
               </li>
            <?php } ?>
            <!-- ----- -->
         </ul>
         <ul class="nav navbar-nav navbar-right ml-auto " style="padding-right:20px; ">
            <li class="nav-item dropdown<?php echo ($page_url == 'profile.php') ? ' active' : '' ?>">
               <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  บัญชีของ, <?= (isset($_SESSION['emp_uid']) ? $_SESSION['emp_user'] : "") ?>
               </a>
               <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="profile.php">แก้ไขข้อมูลผู้ใช้</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="cart_staff.php">ตะกร้าสั่งซื้อ <?php echo ($cart_count) ? '(' . $cart_count . ')' : ''; ?></a>
                  <?php
                  if ($_SESSION['emp_role'] == 2) { // ตรวจลูกค้าหรือ พนง
                     echo '<a class="dropdown-item" target="_blank" href="usermanual-adm.pdf">คู่มือการใช้งาน</a>';
                  } elseif ($_SESSION['emp_role'] == 1) {
                     echo '<a class="dropdown-item" target="_blank" href="usermanual-emp.pdf">คู่มือการใช้งาน</a>';
                  }
                  ?>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="login.php?logout">ออกจากระบบ</a>
               </div>
            </li>
         </ul>
      </div>
   </nav>