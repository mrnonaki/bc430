<?php
require 'config.php';
require 'header.php';

$post = isset($_POST['type']) ? $_POST['type'] : NULL;

if ($post == 'payInvoice') {
    $id = $_POST['id'];
    $detail = $_POST['detail'];
    $payment = $_POST['payment'];
    $ps = $_POST['ps'];
    $sql = 'UPDATE invoice SET paydate = CURRENT_TIMESTAMP , payment = "' . $payment . '" , ps = "'. $ps .'",  status = 2 WHERE id = ' . $id;
    // echo($sql);
    $result = $conn->query($sql);
    $sql = 'UPDATE orders SET status = 2 WHERE invoice = ' . $id;
    // echo($sql);
    $result = $conn->query($sql);


    if (move_uploaded_file($_FILES["detail"]["tmp_name"], "images/payment/" . $id . ".jpg")) {
        echo '<script>
                alert("I' . sprintf("%05d", $id) . ': ระบบบันทึกข้อมูลเรียบร้อยแล้ว");
                window.location.assign("orderlist.php");
            </script>';
    } else {
        echo '<script> alert("อัพโหลดไฟล์ไม่สำเร็จ"); </script>';
    }
}
// header("location: orderlist.php");
?>
<?php
require 'footer.php';
?>