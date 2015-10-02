<?php
require_once('libs/common/global_inc.php');
session_destroy();
header('Location: ' . WEB_BASE_COMMON . 'index.php');
die();
?>