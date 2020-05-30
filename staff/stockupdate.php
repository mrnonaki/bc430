<?php
    if (isset($category)) {
        $sql = 'SELECT COUNT(id) AS count FROM product WHERE category = '.$category;
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $quantity = $row['count'];
        $sql = 'SELECT COUNT(id) AS count FROM product WHERE category = '.$category.' AND status = 1';
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $ready = $row['count'];
        $sql = 'UPDATE category SET quantity = '.$quantity.', ready = '.$ready.' WHERE id = '.$category;
        $result = $conn->query($sql);
        echo '<script>alert("T'.sprintf("%05d", $category).': ระบบอัพเดทข้อมูลเรียบร้อยแล้ว")</script>';
    }
?>