<?php
date_default_timezone_set("Asia/Bangkok");
require 'dbconfig.php';
require 'header.php';
?>
<div class="container">
   <div>
      <h1 class="text-center">แจ้งชำระเงิน</h1>
   </div>
   <div><form action="invoice.php" method="post">
      <table class="table table-borderless">
         <tr>
            <td class="text-right" width="50%">ใบแจ้งหนี้:</td>
            <td width="50%">
               <select id="invoiceid" name="invoiceid">
                   <?php
                   $datas = $database->select("orders",[
                       "invoice_id",
                       "total"
                   ],[
                       "customer[=]" => $_SESSION['uid']
                   ]);
                   foreach($datas as $data){
                       echo("<option value=\"".$data['invoice_id']."\">".$data['invoice_id']." - ".$data['total']."</option>");
                   }
                   ?>
               </select>
               <tr>
               <td class="text-right" width="50%">รายละเอียดการชำระ:</td>
               <td><textarea id="detail" name="detail" rows="3"></textarea></td>
               </tr>
               <tr>
               <td class="text-right" width="50%">หมายเหตุ:</td>
               <td><textarea id="note" name="note" rows="3"></textarea></td>
            </tr>
            </td>
         </tr>
         <tr>
            <td class="text-center" colspan="2"><button type="submit" class="btn btn-success btn-sm">ยืนยัน</button>
         </tr>
      </table>
      </form>
   </div>
</div>
<?
require 'footer.php';
?>