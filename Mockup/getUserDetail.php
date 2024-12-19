<?php
require_once 'helpers/ReportDAO.php';

if (!isset($_GET['userID'])) {
    echo json_encode(['error' => 'ユーザーIDが指定されていません']);
    exit;
}

$userID = $_GET['userID'];
$reportDAO = new ReportDAO();

// 所属グループとチャット履歴を取得
$userGroups = $reportDAO->getUserGroups($userID);
$userChats = $reportDAO->getUserChatHistory($userID);

// JSON形式で返す
echo json_encode([
    'groups' => $userGroups,
    'chats' => $userChats,
]);
