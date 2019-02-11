<?php
require '../license/license.php';
$licence = new License();
if (!$licence->isValid()) {
    echo 'false';
} else {
    echo 'true';
}
?>