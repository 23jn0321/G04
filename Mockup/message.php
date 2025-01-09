<?php
    // 必要なDAOファイルをインクルード（データベース操作用クラス）
    require_once './helpers/messageDAO.php'; // メッセージ関連のDAO
    require_once 'helpers/userDAO.php';     // ユーザー情報関連のDAO
    require_once 'helpers/GroupDAO.php';    // グループ情報関連のDAO

    // 共通ヘッダーを読み込み（セッション管理と共通UI表示用）
    include "header.php";

    // セッションが開始されていない場合は開始する
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }


    $groupDAO = new GroupDAO();
    $messageDAO = new messageDAO();


    // GETパラメータからGroupIDを取得
    if (isset($_GET['GroupID'])) {
        $groupID = $_GET['GroupID'];
        $nowGroup = $messageDAO->NowGroup($groupID);
    }

    // ログイン中のユーザー情報を取得
    $loggedInUser = null;
    if (isset($_SESSION['userInfo'])) {
        $loggedInUser = $_SESSION['userInfo'];
    }

        // 所属グループ情報を取得

        $groupInfo = $groupDAO->getGroup($loggedInUser->UserID); // ユーザーが所属しているグループを取得

        // グループ管理者判定（追加部分）
        $groupAdminID = $groupInfo->GroupAdminID ?? null;

        $isGroupAdmin = ($loggedInUser->UserID === $groupAdminID); // 現在のログインユーザーが管理者かどうか判定


    // POSTリクエストが送信された場合（メッセージ送信処理）
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST['message'])) {
            $message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8'); // テキストボックスからメッセージを取得
            $userId = $_SESSION['userInfo']; // セッションからユーザーIDを取得

            // メッセージをデータベースに挿入
            $messageDAO = new messageDAO();
            $messageDAO->messageInsert($groupID, $userId->UserID, $message);
        }
    }

    // グループ内のすべてのメッセージを取得
    $messageDAO = new messageDAO();
    $messages = $messageDAO->getMessagesByGroup($groupID);

    // メッセージを「自分のもの」と「他人のもの」に分類
    $myMessages = []; // 自分のメッセージ用配列
    $otherMessages = []; // 他人のメッセージ用配列

    foreach ($messages as $msg) {
        if ($msg->SendUserID == $loggedInUser->UserID) {
            $myMessages[] = $msg; // 自分のメッセージ
        } else {
            $otherMessages[] = $msg; // 他人のメッセージ
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSSスタイルシートの読み込み -->
    <link rel="stylesheet" href="CSSUser/Message.css"> <!-- メッセージ画面のスタイル -->

    <!-- 外部ライブラリの読み込み -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2（モーダル表示用） -->
</head>

<body>
    <!-- 所属グループ一覧表示 -->
    <div class="JoinGroup">
        <p id="title">所属グループ一覧</p>
    </div>

    
    <nav class="group">
        <ul>
            <!-- ユーザーが所属するグループ一覧を動的に表示 -->
            <?php foreach ($groupInfo as $var): ?>
                <li>
                    <!-- グループ情報（名前、メンバー数、最終更新日、ジャンル） -->
                    <a href="message.php?GroupID=<?= urlencode($var->GroupID) ?>">
                        グループ名：<?= htmlspecialchars($var->GroupName) ?><br>
                        所属人数 ：<?= htmlspecialchars($var->MemberInfo) ?><br>
                        最終更新日：<?= htmlspecialchars($var->LastUpdated) ?><br>
                        ジャンル：<?= htmlspecialchars($var->Genre) ?>
                    </a>
                    <!-- グループ編集ボタン（管理者のみ表示） -->
                    <?php if ($loggedInUser->UserID == $var->GroupAdminID): ?>
                        <input type="button" onclick="location.href='groupEdit.php?GroupID=<?= urlencode($var->GroupID) ?>'" id="groupEditR" value="グループ編集">
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <div class="NowGroup">
        <h2>現在のグループ：<?= $nowGroup["GroupName"]; ?>　　<input type="button" id="Detail" value="詳細"></h2>
        <!-- 詳細ボタン -->
       
    </div>

    <!-- チャットルーム（メッセージ表示エリア） -->
    <div class="room">
        <ulH id="messageContainer">
            <!-- メッセージが動的に挿入されます -->
        </ulH>
    </div>

    <!-- メッセージ送信用フォーム -->
    <div class="send">
        <form id="chatMessage" method="POST">
            <input type="text" id="message" name="message" placeholder="メッセージを入力してください" maxlength="200" required>
            <input type="submit" value="Send" id="send">
        </form>
    </div>
    <input type="button" id="btn09" class="secret" value="">
    <!-- JavaScript（メッセージ取得・表示、送信処理） -->
    <script>
        $(document).ready(function () {
            const groupID = <?= json_encode($groupID) ?>; // PHPからGroupIDを取得
            const loggedInUserID = <?= json_encode($loggedInUser->UserID) ?>;
            const messageContainer = $("#messageContainer");

            // サーバーからメッセージを取得する関数
            function fetchMessages() {
                $.ajax({
                    url: "getMessage.php", // サーバー側でメッセージを取得
                    type: "GET",
                    data: { GroupID: groupID },
                    dataType: "json",
                    success: function (response) {
                        if (response.status === "success") {
                            renderMessages(response.messages); // メッセージを表示
                        } else {
                            console.error(response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching messages:", error);
                    }
                });
            }

            // メッセージを画面に表示する関数
            function renderMessages(messages) {
                let html = "";
                messages.forEach(msg => {
                    if (msg.SendUserID == loggedInUserID) {
                        // 自分のメッセージ表示
                        html += `
                            <liH class="chat me">
                                <label for="btn09" class="mes">${msg.MessageDetail}</label>
                                <div class="status">あなた<br>${msg.SendTime}</div>
                            </liH>`;
                    } else {
                        // 他人のメッセージ表示
                        html += `
                            <liH class="chat you" data-user-id="${msg.SendUserID}">
                                <label class="mes clickable">${msg.MessageDetail}</label>
                                <div class="status">${msg.SendUserName}<br>${msg.SendTime}</div>
                            </liH>`;
                    }
                });
                messageContainer.html(html);

                // メッセージクリック時のイベント（モーダル表示）
                $(".clickable").on("click", function () {
                    const userID = $(this).closest(".chat").data("user-id");
                    if (userID) {
                        // ユーザー情報取得と通報オプション表示
                        $.ajax({
                            url: "getUserInfo.php",
                            type: "GET",
                            data: { UserID: userID },
                            dataType: "json",
                            success: function (response) {
                                if (response.status === "success") {
                                    Swal.fire({
                                        title: response.userName,
                                        html: `<p>${response.profileComment}</p><br><br><br><br>
                                              <button id="reportButton">通報する</button>`,
                                        showConfirmButton: false,
                                        didRender: () => {
                                            $("#reportButton").on("click", function () {
                                                window.location.href = `report.php?UserID=${userID}&GroupID=${groupID}`;
                                            });
                                        }
                                    });
                                }
                            }
                        });
                    }
                });
            }

            // 初期表示および3秒ごとのメッセージ更新
            fetchMessages();
            setInterval(fetchMessages, 3000);

            // メッセージ送信処理
            $("#chatMessage").on("submit", function (e) {
                e.preventDefault();
                const message = $("#message").val();
                $.ajax({
                    url: "", // 同じPHPファイルにPOST送信
                    type: "POST",
                    data: { message: message },
                    success: function () {
                        $("#message").val(""); // 入力欄をクリア
                        fetchMessages(); // 新しいメッセージを再取得
                    }
                });
            });
        });
    </script>

<script>
  $("#btn09").click(function () {
    Swal.fire({
      title: '<?= $user[0]['UserName'] ?>',
      html: '<br><?= $user[0]['ProfileComment'] ?><br><br>',
      showCloseButton : true
    })
  });
</script>


<script>
    $(document).ready(function() {
        const isGroupAdmin = <?= json_encode($isGroupAdmin) ?>; // PHPから管理者判定を受け取る
        const groupID = <?= json_encode($groupID) ?>; // 現在のGroupIDを取得

        // 詳細ボタンのクリックイベント
        $("#Detail").on("click", function() {
            if (isGroupAdmin) {
                // 管理者の場合はグループ編集ページにリダイレクト
                window.location.href = `groupEdit.php?GroupID=${encodeURIComponent(groupID)}`;
            } else {
                // 一般メンバーの場合はグループ詳細ページにリダイレクト
                window.location.href = `groupDetail.php?GroupID=${encodeURIComponent(groupID)}`;
            }
        });
    });
</script>



</body>
</html>
