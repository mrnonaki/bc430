<?php
require '../config.php';
require 'header.php';
if (!isset($_SESSION)) {
    session_start();
}

$post = isset($_POST['type']) ? $_POST['type'] : NULL;
$get = isset($_GET['type']) ? $_GET['type'] : NULL;

?>

<div class="container">
    <div>
        <form method="get">
            <h1 class="text-center">รายการใบเสร็จรับเงิน</h1>
            <p class="text-center">
                <input type="text" name="search" placeholder="ค้นหาใบเสร็จรับเงิน" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>"><button type="submit" class="btn btn-primary btn-sm">ค้นหา</button>
            </p>
        </form>
    </div>
    <div>
        <table class="table table-striped table-bordered text-center">
            <thead>
                <tr>
                    <th scope="col" width="15%">เลขที่ใบเสร็จ</th>
                    <th scope="col" width="15%">วันที่ออก</th>
                    <th scope="col" width="20%">ลูกค้า</th>
                    <th scope="col" width="20%">พนักงาน</th>
                    <th scope="col" width="15%">ยอดชำระ (บาท)</th>
                    <th scope="col" width="20%">คำสั่ง</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_GET['search']) && $_GET['search'] != "") {
                    if (($_GET['search'][0] == 'R' || $_GET['search'][0] == 'r') && is_numeric($_GET['search'][1])) {
                        $search_id = (int) substr($_GET['search'], 1);

                        $sql = "SELECT distinct receipt.id, customer, receipt.employee employee, receipt.date, receipt.amount, cus.name AS cus_name, cus.id customer, emp.name AS emp_name FROM receipt
                            LEFT JOIN invoice AS invc ON invc.receipt = receipt.id
                            LEFT JOIN orders AS od ON od.invoice = invc.id
                            LEFT JOIN customer AS cus ON cus.id = od.customer
                            LEFT JOIN employee AS emp ON emp.id = receipt.employee
                        WHERE receipt.id = $search_id ORDER BY id DESC";
                    } else {
                        $sql = 'SELECT distinct receipt.id, receipt.employee employee, receipt.date, receipt.amount, cus.id customer, cus.name AS cus_name, emp.name AS emp_name FROM receipt
                            LEFT JOIN invoice AS invc ON invc.receipt = receipt.id
                            LEFT JOIN orders AS od ON od.invoice = invc.id
                           LEFT JOIN customer AS cus ON cus.id = od.customer
                           LEFT JOIN employee AS emp ON emp.id = receipt.employee
                        WHERE receipt.id LIKE "%' . $_GET['search'] . '%" OR cus.name LIKE "%' . $_GET['search'] . '%" OR emp.name LIKE "%' . $_GET['search'] . '%" ORDER BY receipt.id DESC';
                    }
                } else {
                    $sql = "SELECT distinct receipt.id, receipt.employee AS employee, receipt.date, receipt.amount, cus.id customer, cus.name AS cus_name, emp.name AS emp_name FROM receipt
                            INNER JOIN invoice AS invc ON invc.receipt = receipt.id
                            INNER JOIN orders AS od ON od.invoice = invc.id
                            INNER JOIN customer AS cus ON cus.id = od.customer
                            INNER JOIN employee AS emp ON emp.id = receipt.employee
                            ORDER BY receipt.id DESC";
                }

                $result = $conn->query($sql) or die($conn->error);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>R" . sprintf("%05d", $row['id']) . "</td>";
                        echo "<td>" . date("d/m/Y", strtotime($row['date'] . "+543 year")) . "</td>";
                        echo "<td class='text-left'>C" . sprintf("%05d", $row['customer']) . " - " . $row['cus_name'] . "</td>";
                        echo "<td class='text-left'>E" . sprintf("%05d", $row['employee']) . " - " . $row['emp_name'] . "</td>";
                        echo "<td class='text-right'>" . number_format($row['amount'], 2) . "</td><td>";
                        echo '<button class="btn btn-primary btn-sm" name="receipt" onclick="loadModal(\'getReceipt\', ' . $row['id'] . ')">แสดงใบเสร็จ</button>';

                        echo '</td></tr>';
                    }
                } else {
                    echo '<td colspan="7">ไม่พบข้อมูล</td>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<div id="loadModal"></div>
<?php
require("footer.php");
?>