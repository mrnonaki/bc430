<?php
require '../config.php';
require 'header.php';

$type = $_POST['type'] ? $_POST['type'] : NULL;
//$new_sn = $_POST['new_sn'] ? $_POST['new_sn'] : NULL;
$old_sn = $_POST['old_sn'] ? $_POST['old_sn'] : NULL;
$ps = $_POST['ps'] ? $_POST['ps'] : NULL;

if ($type = "claim" && $old_sn != NULL) {

    $sql_chq_old = "SELECT category FROM product WHERE id = '$old_sn'";
    $q_chq_old = $conn->query($sql_chq_old);
    $row_old = $q_chq_old->fetch_assoc();

    $sql_chq_new = "SELECT category, status FROM product WHERE id = '$new_sn'";
    $q_chq_new = $conn->query($sql_chq_new);
    $row_new = $q_chq_new->fetch_assoc();


    $sql_order = "SELECT orderlist.id AS od_id, customer.id AS cus_id, product.category FROM product
                LEFT JOIN orderlist ON orderlist.product = product.id
                LEFT JOIN orders ON orders.id = orderlist.orders
                LEFT JOIN customer ON customer.id = orders.customer WHERE product.id = '$old_sn'";
    $q_order = $conn->query($sql_order);
    $row_order = $q_order->fetch_assoc();

    $sql_old = "UPDATE product SET status = '5' WHERE id = '$old_sn'";
    $q_old = $conn->query($sql_old);

    // ---------------------- New Product ----------------------------
    $sql_prod_new = 'SELECT * FROM product WHERE category = ' . $row_old['category'] . ' AND status = 1 ORDER BY datein';
    $result_prod_new = $conn->query($sql_prod_new) or die($conn->error);
    $row_prod_new = $result_prod_new->fetch_assoc();

    $new_sn = $row_prod_new['id'];

    $sql_new = "UPDATE product SET status = '6' WHERE id = '$new_sn'";
    $q_new = $conn->query($sql_new);

    $sql_warranty = "INSERT INTO warranty SET
                customer = '" . $row_order['cus_id'] . "',
                employee = '" . $_SESSION['emp_uid'] . "',
                new     = '$new_sn',
                ps      = '$ps',
                orderlist = '" . $row_order['od_id'] . "'";
    $q_warranty = $conn->query($sql_warranty);
    $new_warranty_id = $conn->insert_id;

    if ($q_warranty && $q_new && $q_old) {
        echo "<script>alert('W" . sprintf("%05d", $new_warranty_id) . ": บันทึกการเปลี่ยนเรียบร้อย');
                window.location.assigm('warranty.php');</script>";
    } else {
        echo $conn->error;
    }
}


?>
<div class="container">
    <form id="warranty" method="post">
        <div>
            <h1 class="text-center">เช็คประกัน</h1>
        </div>
        <div>
            <table class="table table-borderless">
                <tr>
                    <td class="text-right" width="50%">S/N:</td>
                    <td width="50%"><input name="sn" required type="text"></td>
                </tr>
                <tr>
                    <td class="text-center" colspan="2">
                        <button type="submit" class="btn btn-success btn-sm">ยืนยัน</button>
                    </td>
                </tr>
            </table>
        </div>
    </form>
</div>


<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['sn'])) {
        $sql = 'SELECT product.id AS id, orders.id AS orders, customer.name AS cus_name, product.status AS prod_status, orders.dateship AS ship FROM product 
                INNER JOIN orderlist ON product.id = orderlist.product 
                INNER JOIN orders ON orderlist.orders = orders.id
                LEFT JOIN customer ON customer.id = orders.customer WHERE product.id = \'' . $_POST['sn'] . '\'';
        $result = $conn->query($sql) or die($conn->error);
        $row = $result->fetch_assoc();

        // print_r($row);
        echo '<br>';
        $end = strtotime($row['ship'] . "+544 years -1 days");
        $end_claim = strtotime($row['ship'] . "+7 days");
        $warranty_period = date("d/m/Y", strtotime($row['ship'] . "+543 years")) . " - " . date("d/m/Y", $end);
        echo '<br>';
        if ($end_claim < time()) {
            $warranty = false;
            $reply = "<font color='red'>ไม่สามารถเปลี่ยนได้";
        } else {
            $warranty = true;
            $reply = "<font color='4CD267'>สามารถเปลี่ยนได้";
        }
        if ($result->num_rows > 0) { // เช็คว่ามีข้อมูบ
            if ($row['prod_status'] == 4) { // สถานะสินค้า เป็น ขายแล้ว
?>
                <div class="modal fade" id="addWarranty" tabindex="-1" role="dialog" aria-labelledby="addWarrantyLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addWarrantyLabel">S/N: <?= $row['id'] ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="">
                                <div class="modal-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="text-right" width="50%">การสั่งซื้อ:</td>
                                            <td width="50%">O<?= sprintf("%05d", $row['orders']) ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" width="50%">ชื่อ-นามสกุล:</td>
                                            <td width="50%"><?= $row['cus_name'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right">ระยะรับประกัน:</td>
                                            <td><?= $warranty_period ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right">สถานะ:</td>
                                            <td><?= $reply ?></td>
                                        </tr>
                                        <?php
                                        if ($warranty) {
                                        ?>
                                            <tr>
                                                <td class="text-right">หมายเหตุ :<font color="red">*</font>
                                                </td>
                                                <td>
                                                    <input type="text" hidden name="type" value="claim">
                                                    <input type="text" hidden name="old_sn" value="<?= $row['id'] ?>">
                                                    <textarea required class="form-control" name="ps"></textarea>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <?php if ($warranty) { ?>
                                        <button type="submit" onclick="if(confirm('ต้องการยืนยันการเปลี่ยนใช่หรือไม่?')) return true; else return false;" class="btn btn-success">ยืนยัน</button>
                                        <button type="reset" class="btn btn-warning">ล้างค่า</button>
                                    <?php
                                    }
                                    ?>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><?= $warranty ? "ยกเลิก" : "ปิด" ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <script>
                    $('#addWarranty').modal().show();
                </script>
<?php
            } elseif ($row['prod_status'] == 3) {
                echo "<script>alert('สินค้าอยู่ระหว่างการจัดส่ง');</script>";
            } elseif ($row['prod_status'] == 2) {
                echo "<script>alert('สินค้าอยู่อยู่ระหว่างรอชำระ');</script>";
            }
        } else {
            echo "<script>alert('ไม่พบข้อมูลสินค้า');</script>";
        }
    }
}
?>
<?php
require 'footer.php';
?>