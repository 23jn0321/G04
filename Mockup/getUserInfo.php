<?php
require_once './helpers/StudentDAO.php';

if (isset($_GET['UserID'])) {
    $userID = $_GET['UserID'];
    $studentDAO = new StudentDAO();
    $userInfo = $studentDAO->get_newUserInfo($userID);

    if ($userInfo) {
        echo json_encode([
            'status' => 'success',
            'userName' => $userInfo[0]['UserName'],
            'profileComment' => $userInfo[0]['ProfileComment']
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
    }
}
?>
