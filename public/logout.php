<?php
// public/logout.php
require_once '../src/controllers/AuthController.php';

$authController = new AuthController();
$authController->logout();
