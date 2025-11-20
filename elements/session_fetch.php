<?php
session_start();

header("Content-Type: application/json");

$response = [
    "user_email" => $_SESSION["pwe_reg_entry"]["email"] ?? null,
    "user_phone" => $_SESSION["pwe_reg_entry"]["phone"] ?? null
];

echo json_encode($response);