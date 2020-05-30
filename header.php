<?php
session_start();

if (isset($_COOKIE['uid']) && !isset($_SESSION['uid'])) {
   $uid = isset($_COOKIE['uid']) ? $_COOKIE['uid'] : NULL;
   $posid = strpos($uid, '-');
   $id = substr($uid, 0, $posid);
   $sql = 'SELECT * FROM customer WHERE id = \'' . $id . '\'';
   $result = $conn->query($sql);
   if ($result->num_rows != 0) {
      $row = $result->fetch_assoc();
      if ($uid == $row['id'] . '-' . md5($row['id'] . $row['username'] . $row['password'])) {
         $_SESSION['uid'] = $row['id'];
         $_SESSION['user'] = $row['username'];
         $_SESSION['ban'] = $row['ban'];
      }
   }
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

$page_url = basename($_SERVER['PHP_SELF']);
switch ($page_url) {
   case 'index.php':
      $page_name = 'หน้าแรก';
      break;
   case 'product.php':
      $page_name = 'สินค้า';
      break;
   case 'warranty.php':
      $page_name = 'เช็คประกัน';
      break;
   case 'contact.php':
      $page_name = 'ติดต่อเรา';
      break;
   case 'cart.php':
      $page_name = 'ตะกร้า';
      break;
   case 'profile.php':
      $page_name = 'แก้ไขสมาชิก';
      break;
   case 'login.php':
      $page_name = 'เข้าสู่ระบบ';
      break;
   case 'register.php':
      $page_name = 'สมัครสมาชิก';
      break;
   case 'forgotpassword.php':
      $page_name = 'ลืมรหัสผ่าน';
      break;
   case 'resetpassword.php':
      $page_name = 'ตั้งรหัสผ่านใหม่';
      break;
   case 'orderlist.php':
      $page_name = 'รายการสั่งซื้อ';
      break;
   default:
      $page_name = 'Error';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title><?php echo $page_name; ?> - ระบบจำหน่ายอุปกรณ์คอมพิวเตอร์</title>
   <link href="css/bootstrap-4.3.1.css" rel="stylesheet">
   <script src="js/jquery-3.3.1.min.js"></script>
   <script src="js/popper.min.js"></script>
   <script src="js/bootstrap-4.3.1.js"></script>
   <link href="css/font-awesome.min.css" rel="stylesheet" />
   
   <script type="text/javascript" src="js/date/js/bootstrap-datepicker.js"></script>
   <!-- thai extension -->
   <script type="text/javascript" src="js/date/js/bootstrap-datepicker-thai.js"></script>
   <script type="text/javascript" src="js/date/js/locales/bootstrap-datepicker.th.js"></script>
   <link href="js/date/css/datepicker.css" rel="stylesheet" />
</head>

<body>
   <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <a class="navbar-brand" href="/index.php"><img src="images/logo.png"></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
         <ul class="navbar-nav mr-auto">
            <li class="nav-item<?php echo ($page_url == 'index.php') ? ' active' : '' ?>">
               <a class="nav-link" href="index.php">หน้าแรก</a>
            </li>
            <li class="nav-item<?php echo ($page_url == 'product.php') ? ' active' : '' ?>">
               <a class="nav-link" href="product.php">สินค้า</a>
            </li>
            <li class="nav-item<?php echo ($page_url == 'warranty.php') ? ' active' : '' ?>">
               <a class="nav-link" href="warranty.php">เช็คประกัน</a>
            </li>
            <li class="nav-item dropdown<?php echo ($page_url == 'cart.php') ? ' active' : '' ?>">
               <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  ตะกร้า<?php echo ($cart_count) ? ', (' . $cart_count . ')' : ''; ?>
               </a>
               <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <div class="dropdown-item"><?php echo ($cart_count) ? $cart_item : 'ตะกร้าว่าง'; ?></div>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" onclick="if(confirm('ต้องการล้างตะกร้าใช่หรือไม่?')) window.location.assign('cart.php?clear'); else return false;">ล้างตะกร้า</a>
                  <a class="dropdown-item" href="cart.php">จัดการตะกร้า</a>
               </div>
            </li>
            <li class="nav-item<?php echo ($page_url == 'contact.php') ? ' active' : '' ?>">
               <a class="nav-link" href="contact.php">ติดต่อเรา</a>
            </li>
         </ul>
         <ul class="nav navbar-nav navbar-right">
            <li class="nav-item dropdown<?php echo ($page_url == 'profile.php') ? ' active' : '' ?>">
               <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <?php echo (isset($_SESSION['uid'])) ? 'บัญชีของ, ' . $_SESSION['user'] : 'เข้าสู่ระบบ / สมัครสมาชิก' ?>
               </a>
               <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="<?php echo (isset($_SESSION['uid'])) ? 'profile.php' : 'register.php' ?>"><?php echo (isset($_SESSION['uid'])) ? 'แก้ไขสมาชิก' : 'สมัครสมาชิก' ?></a>
                  <?php
                  if (isset($_SESSION['uid'])) {
                     echo '<div class="dropdown-divider"></div>';
                     echo '<a class="dropdown-item" href="orderlist.php">รายการสั่งซื้อ</a>' . "\n";
                     echo '<a class="dropdown-item" target="_blank" href="usermanual-cus.pdf">คู่มือการใช้งาน</a>';
                     echo '<div class="dropdown-divider"></div>';
                     echo '<a class="dropdown-item" href="login.php?logout">ออกจากระบบ</a>';
                  } else {
                     echo '<a class="dropdown-item" href="login.php">เข้าสู่ระบบ</a>';
                  }
                  ?>
               </div>
            </li>
         </ul>
      </div>
   </nav>