<?php
require 'config.php';
require 'header.php';
?>
<div class="container">
    <div>
        <form method="get">
            <h1 class="text-center">สินค้า</h1>
            <p class="text-center">
                <input type="text" name="search" placeholder="ค้นหาสินค้า" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>"><button type="submit" class="btn btn-primary btn-sm">ค้นหา</button>
            </p>
        </form>
    </div>
    <div>
        <table class="table table-striped table-bordered text-center">
            <thead>
                <tr>
                    <th scope="col" width="15">รหัสประเภท</th>
                    <th scope="col" width="10%">รูป</th>
                    <th scope="col" width="33%">ชื่อประเภท</th>
                    <th scope="col" width="12%">ราคา [บาท]</th>
                    <th scope="col" width="12%">ค่าส่ง [บาท]</th>
                    <th scope="col" width="10%">คงเหลือ</th>
                    <th scope="col" width="10%">คำสั่ง</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_GET['search']) && $_GET['search'] != "") {
                    if (($_GET['search'][0] == 'T' || $_GET['search'][0] == 't') && is_numeric($_GET['search'][1])) {
                        $search_id = (int) substr($_GET['search'], 1);
                        $sql = 'SELECT * FROM category WHERE id LIKE ' . $search_id . ' AND issell = 1 ORDER BY id DESC';
                    } else {
                        $sql = 'SELECT * FROM category WHERE id LIKE "%' . $_GET['search'] . '%" OR name LIKE "%' . $_GET['search'] . '%" AND issell = 1 ORDER BY id DESC';
                    }
                } else {
                    $sql = 'SELECT * FROM category WHERE issell = 1 ORDER BY id DESC';
                }
                $result = $conn->query($sql);
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>' . "\n";
                        echo '<th scope="row" onclick="loadModal(\'addCart\', ' . $row['id'] . ')">T' . sprintf("%05d", $row['id']) . '</th>' . "\n";
                        echo '<td><img width="120px" height="100px" src="/images/category/' . $row['id'] . '.jpg" class="dimg-fluid"></td>' . "\n";
                        echo '<td class="text-left">' . $row['name'] . '</td>' . "\n";
                        echo '<td class="text-right">' . number_format($row['price'], 2, '.', ',') . '</td>' . "\n";
                        echo '<td class="text-right">' . number_format($row['ship'], 2, '.', ',') . '</td>' . "\n";
                        echo '<td class="text-right">' . $row['ready'] . ' ' . $row['unit'] . '</td>' . "\n";
                        echo '<td>' . "\n";
                        echo '<button type="button" class="btn btn-primary btn-sm" onclick="loadModal(\'addCart\', ' . $row['id'] . ')">ใส่ตะกร้า</button>' . "\n";
                        echo '</td>' . "\n";
                        echo '</tr>' . "\n";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<div id="loadModal"></div>
<?php
require 'footer.php';
?>