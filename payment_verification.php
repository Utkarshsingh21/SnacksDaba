<?php
session_start();
require('vendor/autoload.php'); // Razorpay PHP SDK
include_once("admin/conn.php");

use Razorpay\Api\Api;

$input = json_decode(file_get_contents('php://input'), true);

$api = new Api('rzp_test_RGJ1hfRVhqu7MS', 'ztU5ZYHSDJJYjQL0xMOKUAGN'); // Replace with your keys

$payment_id = $input['razorpay_payment_id'];
$_SESSION['razorpay_payment_id'] = $input['razorpay_payment_id'];
$_SESSION['razorpay_payment_dtime'] = date('Y-m-d H:i:s');

?>
