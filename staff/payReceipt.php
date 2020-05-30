<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['emp_uid'])) {
    exit();
}
require("../config.php");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = ($_POST['id']) ? $_POST['id'] : NULL; // Invoice id
    $sql_invoice = "SELECT * FROM invoice WHERE id = '$id'";
    $result = $conn->query($sql_invoice);
    $row = $result->fetch_assoc();

    $orderid = ($_POST['orderid']) ? $_POST['orderid'] : NULL; // Orderid
    if ($orderid == NULL) { // กรณีไม่มี orderid ส่งมา
        $sql_o = "SELECT * FROM orders WHERE invoice = '$id'";
        $q_order = $conn->query($sql_o);
        $row_o = $q_order->fetch_assoc();
        $orderid = $row_o['id'];
    }

    $emp = $_SESSION['emp_uid'];
    $cus = $row['customer'];
    $amount = $row['amount'];
    //$date = $_POST['date_pay'];
    $payment = ($_POST['payment']) ? $_POST['payment'] : $row['payment'];
    $ps = ($_POST['ps']) ? $_POST['ps'] : $row['ps'];

    $sql_order = "UPDATE orders SET
        status = '3' WHERE orders.invoice = '$id'";

    $q_order = $conn->query($sql_order);

    $sql_product = "UPDATE product 
    INNER JOIN orderlist ON orderlist.product = product.id
    SET product.status = '3' WHERE orderlist.orders = '$orderid'";

    $q_product = $conn->query($sql_product);

    $sql_receipt = "INSERT INTO receipt SET
            employee = '$emp',
            amount   = '$amount',
            ps       = '$ps'";

    $q_receipt = $conn->query($sql_receipt) or die($conn->error);
    $receipt_id = $conn->insert_id;

    $sql_invoice2 = "UPDATE invoice SET
            payment = '" . $payment . "',
            paydate = CURRENT_TIMESTAMP,
            receipt = '$receipt_id',
            status = '3' WHERE id = '$id'";

    $q_invoice = $conn->query($sql_invoice2);

    if (isset($_FILES["detail"]["name"]) && $_FILES["detail"]["name"] != "") {
        // if (strtolower(pathinfo($_FILES["detail"]["name"], PATHINFO_EXTENSION)) == "jpg") {
        //     $checkUpload = 'jpg';
        //     if (getimagesize($_FILES["detail"]["tmp_name"])) {
        //         $checkUpload = 'img';
        //         if ($_FILES["detail"]["size"] <= 500000) {
        //             $checkUpload = 'ok';
        //         }
        //     }
        // }
        $checkUpload = 'ok';

        if ($q_invoice && $q_receipt && $q_order && $checkUpload == 'ok') {

            if (move_uploaded_file($_FILES["detail"]["tmp_name"], "../images/payment/" . $id . ".jpg")) {
                echo "<script>alert('O" . sprintf("%05d", $orderid) . ": บันทึกรับชำระเรียบร้อย');
                window.location.assign('receipt.php');</script>";
            } else {
                echo '<script> alert("อัพโหลดไฟล์ไม่สำเร็จ"); </script>';
            }
        } else {
            echo '<script> alert("อัพโหลดไฟล์ไม่สำเร็จ"); </script>';
        }
    } else {
        echo "<script>alert('O" . sprintf("%05d", $orderid) . ": บันทึกรับชำระเรียบร้อย');
        window.location.assign('receipt.php');</script>";
    }
}
