<?php
require 'config.php';
require 'header.php';

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
      $sql = 'SELECT product.id AS id, orders.id AS orders, product.status AS prod_status, orders.dateship AS ship FROM product INNER JOIN orderlist ON product.id = orderlist.product INNER JOIN orders ON orderlist.orders = orders.id WHERE product.id = \'' . $_POST['sn'] . '\'';
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();

      // print_r($row);
      echo '<br>';
      $end = strtotime($row['ship'] . "+544 years -1 days");
      $warranty_period = date("d/m/Y", strtotime($row['ship'] . "+543 years")) . " - " . date("d/m/Y", $end);
      echo '<br>';
      if ($end < time()) {
         $reply = "<font color='red'>ไม่สามารถเปลี่ยนได้";
      } else {
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
                     <div class="modal-body">
                        <table class="table table-borderless">
                           <tr>
                              <td class="text-right" width="50%">การสั่งซื้อ:</td>
                              <td width="50%">O<?= sprintf("%05d", $row['orders']) ?></td>
                           </tr>
                           <tr>
                              <td class="text-right">ระยะรับประกัน:</td>
                              <td><?= $warranty_period ?></td>
                           </tr>
                           <tr>
                              <td class="text-right">สถานะ:</td>
                              <td><?= $reply ?></td>
                           </tr>
                           <tr>
                              <td colspan="2" style="text-align:center;"><font color="red"><b>** ต้องการเปลี่ยนสินค้า โปรด <a href="contact.php">ติดต่อพนักงาน</a> **</b></td>
                           </tr>
                        </table>
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                     </div>
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