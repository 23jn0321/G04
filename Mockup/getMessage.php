<?php
    require_once './helpers/messageDAO.php';

    $groupID = 1;  // 仮にGroupIDを1に設定。動的に設定することも可能。
    $userID = $_SESSION['userInfo']; // セッションからユーザーIDを取得
    $messageDAO = new messageDAO();

    // メッセージを取得
    $messages = $messageDAO->getMessages($groupID);

    // メッセージリストをHTML形式で出力
    foreach ($messages as $message) {
        echo '<li class="chat ' . ($message['SendUserID'] == $userID ? 'me' : 'you') . '">';
        echo '<label class="mes">' . htmlspecialchars($message['MessageDetail']) . '</label>';
        echo '<div class="status">' . htmlspecialchars($message['UserName']) . '<br>' . $message['SendTime'] . '</div>';
        echo '</li>';
    }
?>
