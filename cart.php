<?php
require 'config.php';
require 'header.php';

$post = isset($_POST['type']) ? $_POST['type'] : NULL;
$id = isset($_POST['id']) ? $_POST['id'] : NULL;
$name = isset($_POST['name']) ? $_POST['name'] : NULL;
$price = isset($_POST['price']) ? $_POST['price'] : NULL;
$ship = isset($_POST['ship']) ? $_POST['ship'] : NULL;
$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : NULL;

if ($post == 'addCart' || isset($_POST['del_cart'])) {
    if ($quantity >= 0) {
        $sql = 'SELECT * FROM category WHERE id = ' . $id;
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        if ($row['ready'] >= $quantity) {
            $i = 0;
            foreach ($_SESSION['cart'] as $cart) {
                if ($cart['id'] != $id) {
                    $i++;
                } else {
                    break;
                }
            }
            if ($i > count($_SESSION['cart'])) {
                $i = count($_SESSION['cart']);
            }
            $_SESSION['cart'][$i]['id'] = $id;
            $_SESSION['cart'][$i]['name'] = $name;
            $_SESSION['cart'][$i]['price'] = $price;
            $_SESSION['cart'][$i]['ship'] = $ship;
            $_SESSION['cart'][$i]['quantity'] = $quantity;
        } else {
            echo '<script>alert("สินค้ามีไม่เพียงพอ")</script>';
        }
    } else {
        echo '<script>alert("กรอกจำนวนสินค้ามีไม่ถูกต้อง")</script>';
    }
}

if (isset($_POST['address'])) {
    if ($_POST['address']) {
        if (isset($_POST['updateaddress'])) {
            $_SESSION['address'] = $_POST['updateaddress'];
            $addressRadio0 = NULL;
            $addressRadio1 = 'checked';
            $addressTxt = NULL;
        }
    } else {
        unset($_SESSION['address']);
        $addressRadio0 = 'checked';
        $addressRadio1 = NULL;
        $addressTxt = 'disabled';
    }
}
if (isset($_SESSION['address'])) {
    $addressRadio0 = NULL;
    $addressRadio1 = 'checked';
    $addressTxt = NULL;
}

if (isset($_GET['clear'])) {
    unset($_SESSION['cart']);
    header("Location: /cart.php", TRUE, 301);
}

?>
<div class="container">
    <div>
        <h1 class="text-center">ตะกร้าสั่งซื้อ</h1>
    </div>
    <div>
        <table class="table table-striped table-bordered text-center">
            <thead>
                <tr>
                    <th scope="col" width="10%">รหัสประเภท</th>
                    <th scope="col" width="10%">รูป</th>
                    <th scope="col" width="20%">ชื่อ</th>
                    <th scope="col" width="12%">ราคา [บาท]</th>
                    <th scope="col" width="12%">ค่าส่ง [บาท]</th>
                    <th scope="col" width="6%">จำนวน</th>
                    <th scope="col" width="12%">ราคารวม [บาท]</th>
                    <th scope="col" width="10%">ลบ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sum_price = 0;
                $sum_ship = 0;
                $chq_cart = 0; // จำนวนสินค้าในตะกร้า
                foreach ($_SESSION['cart'] as $cart) {
                    $sql = 'SELECT * FROM category WHERE id = ' . $cart['id'];
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    if ($cart['quantity']) {
                        $chq_cart++; // ตรวจสอบจำนวนสินค้าในตะกร้า
                        $sum_price += $row['price'] * $cart['quantity'];
                        $sum_ship += $row['ship'] * $cart['quantity'];
                        echo '<tr>' . "\n";
                        echo '<th scope="row" onclick="loadModal(\'addCart\', ' . $row['id'] . ')">T' . sprintf("%05d", $row['id']) . '</th>' . "\n";
                        echo '<td><img src="/images/category/' . $row['id'] . '.jpg" class="img-fluid"></td>' . "\n";
                        echo '<td class="text-left">' . $row['name'] . '</td>' . "\n";
                        echo '<td class="text-right">' . number_format($row['price'], 2, '.', ',') . '</td>' . "\n";
                        echo '<td class="text-right">' . number_format($row['ship'], 2, '.', ',') . '</td>' . "\n";
                        echo '<td class="text-right" onclick="loadModal(\'addCart\', ' . $row['id'] . ')">' . $cart['quantity'] . ' ' . $row['unit'] . '</td>' . "\n";
                        echo '<td class="text-right">' . number_format(($row['price'] + $row['ship']) * $cart['quantity'], 2, '.', ',') . '</td>' . "\n";
                ?>
                        <form method="post">
                        <?php
                        echo '<input type="text" hidden name="id" value=' . $row['id'] . '>';
                        echo '<td class="text-center"><button class="btn btn-danger" name="del_cart">ลบ</button></td>';
                        echo '</tr>' . "\n";
                    }
                        ?>
                        </form>
                    <?php
                }
                echo '<tr>' . "\n";
                echo '<td colspan="8"><button onClick="window.location.href=\'product.php\';">เพิ่มสินค้า</button> <button onClick="if(confirm(\'ต้องการล้างตะกร้าใช่หรือไม่?\')) window.location.href=\'cart.php?clear\'; else return false;">ล้างตะกร้า</button></td>' . "\n";
                echo '</tr>' . "\n";
                echo '<tr>' . "\n";
                echo '<td class="text-right" colspan="7">ค่าสินค้ารวม</td>' . "\n";
                echo '<td class="text-right">' . number_format($sum_price, 2, '.', ',') . '</td>' . "\n";
                echo '</tr>' . "\n";
                echo '<tr>' . "\n";
                echo '<td class="text-right" colspan="7">ค่าส่งรวม</td>' . "\n";
                echo '<td class="text-right">' . number_format($sum_ship, 2, '.', ',') . '</td>' . "\n";
                echo '</tr>' . "\n";
                echo '<tr>' . "\n";
                echo '<td class="text-right" colspan="7">ยอดรวม</td>' . "\n";
                echo '<td class="text-right">' . number_format($sum_price + $sum_ship, 2, '.', ',') . '</td>' . "\n";
                echo '</tr>' . "\n";
                    ?>
            </tbody>
        </table>
    </div>
    <div class="text-center">
        <p class="text-danger">สินค้าจัดส่งภายใน 7วัน หลังจากรับชำระ</p>
    </div>
    <div>
        <form method="post">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <th scope="row" class="text-right" width="20%">สถานที่จัดส่ง *</th>
                        <td class="form-check-inline" width="60%"><input type="radio" class="form-check-input" name="address" value=0 onchange="this.form.submit();" <?php echo $addressRadio0; ?>>สถานที่ ตามข้อมูลสมาชิก&nbsp;&nbsp;&nbsp;<input type="radio" class="form-check-input" name="address" value=1 onchange="this.form.submit();" <?php echo $addressRadio1; ?>>สถานที่อื่น โปรดระบุ</td>
                        <td class="text-right" width="20%"><input type="submit" value="บันทึกสถานที่"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2"><textarea class="form-control" rows="5" name="updateaddress" <?php echo $addressTxt ?>><?php echo $_SESSION['address']; ?></textarea></td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
    <div class="text-center">
        <?php
        if ($_SESSION['ban']) {
            echo '<button class="btn btn-danger">ผู้ใช้ถูกระงับ</button>';
        } else {
            if ($chq_cart == 0) {
                echo '<button class="btn btn-success" onclick="alert(\'กรุณาเลือกสินค้าที่ต้องการสั่งซื้อ\'); window.location.assign(\'product.php\');">สั่งซื้อสินค้า</button>';

            } else {
                echo '<button class="btn btn-success" onclick="loadModal(\'addOrder\')">สั่งซื้อสินค้า</button>';
            }
        }
        ?>
    </div>
</div>
<div id="loadModal"></div>
<?php
require 'footer.php';
?>