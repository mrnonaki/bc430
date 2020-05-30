<?php
require '../config.php';
require 'header.php';
$sql = 'SELECT * FROM category WHERE issell = 1 ORDER BY id DESC';
$result = $conn->query($sql);
$i = 1;
if ($result) {
    while ($row = $result->fetch_assoc()) {
        if ($i <= 6) {
            if ($i == 1) {
                echo '
<hr><h2 class="text-center">สินค้าใหม่</h2><hr>
<div class="container">
    <div class="row text-center">
            ';
            }
            if ($i == 4) {
                echo '
    <div class="row text-center mt-4">
                ';
            }
            echo '
<div class="col-md-4 pb-1 pb-md-0">
    <div class="card">
        <img class="card-img-top" style="height:300px;" src="../images/category/' . $row['id'] . '.jpg">
        <div class="card-body">
            <h5 class="card-title">' . $row['name'] . '</h5>
        </div>
    </div>
</div>
            ';
            if ($i == 3 || $i == 6) {
                echo '
    </div>
                ';
            }
            $i += 1;
        }
    }
    if ($i > 1) {
        echo '
    </div>
            ';
    }
}

require 'footer.php';
