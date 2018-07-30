<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once '../Classes/DebugHelper.php';
require_once '../Classes/User.php';
require_once '../Classes/Database.php';

$database = new Database();
$conn = $database->getConnection();
$attributes = $database->getAttributes();

$myUser = new User($conn, $attributes);
if (isset($_SESSION['Email'])) {
    $myUser->get($_SESSION['Email'], false);
}
$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
$debugH = new DebugHelper();
$debugH->addObject($method);
$debugH->addObject($request);
$debugH->addObject($myUser);

function update_user($conn, $attributes, $request) {
    print_r($request);
}

function add_user($conn, $attributes, $request) {
    print_r($request);
}

function get_user($conn, $attributes, $request) {
    $user = new User($conn, $attributes);
    switch (strtolower($request[0])) {
        case "email":
            $rEmail = strip_tags($request[1]);
            break;
        case "getall":
            print_r($user->getAllJson());
            return;
        default:
            $rID = strip_tags($request[0]);
            break;
    }
    if (isset($rID)) {
        $user->getByID($rID);
    } else if (isset($rEmail)) {
        $user->get($rEmail);
    }
}


switch ($method) {
    case 'PUT':
        update_user($conn, $attributes, $request);
        break;
    case 'POST':
        add_user($conn, $attributes, $request);
        break;
    case 'GET':
        get_user($conn, $attributes, $request);
        break;
    default:
        print_r(json_encode(array("message" => "Invalid method received")));
        $debugH->errormail($myUser->email, "API Call to user", "Invalid API call");



}

 ?>
