<?php
require '../license/license.php';
require 'callAPI.php';
require 'admin_token.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try
    {
        $licence = new License();
        $licence->deactivate();
        exit();
    } catch (Exception $e) {
        error_log("unable to deactivate this subcriptions", "error:" . $e->getMessage());
        // TODO
        // Email or notify the developer
    }
}
?>