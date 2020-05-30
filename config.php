<?php
date_default_timezone_set("Asia/Bangkok");
mb_internal_encoding("UTF-8");
$weburl = 'WEBURL';
$mailgunapi = 'APIKEY';
$conn = new mysqli("localhost", "USER", "PASSWORD", "DB_NAME");
$conn->set_charset("utf8");
?>