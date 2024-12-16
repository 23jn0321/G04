<?php
require_once './helpers/messageDAO.php';

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$groupID = isset($_GET['GroupID']) ? intval($_GET['GroupID']) : null;

if (!$groupID) {
    echo json_encode(["status" => "error", "message" => "GroupID is missing"]);
    exit;
}

$messageDAO = new messageDAO();
$messages = $messageDAO->getMessagesByGroup($groupID);

if ($messages) {
    echo json_encode(["status" => "success", "messages" => $messages]);
} else {
    echo json_encode(["status" => "error", "message" => "No messages found"]);
}
