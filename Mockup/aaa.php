<?php
require_once 'helpers/DAO.php';
require_once 'helpers/reportDAO.php';

// データベースからユーザーごとのチャットデータを取得する

$a=new ReportDAO();
$userChats = $a->getUserChats();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>ユーザーごとのチャット表示</title>
    <link rel="stylesheet" href="CSSUser/Header.css">
    <link rel="stylesheet" href="CSSAdmin/reportView.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<header>
    <a href="admin.html"><img src="jecMatching/JecMatchingAdmin.jpg" width="450px" alt="Jec Matching"></a>
    <hr>
</header>

<div class="content">
    <ul id="userList">
        <?php foreach ($userChats as $userID => $userData): ?>
            <li onclick="showUserChats(<?php echo $userID; ?>)">
                <div class="user-info">
                    <p><?php echo $userData['userName'] . ' ' . $userData['department']; ?></p>
                    <button class="freeze-btn" data-user-name="<?php echo $userData['userName']; ?>" onclick="freezeUser(this)">凍結</button>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="box">
        <div id="userChats"></div>
    </div>
</div>

<script>
    // PHPから取得したデータをJavaScriptに渡す
    const userChats = <?php echo json_encode($userChats, JSON_UNESCAPED_UNICODE); ?>;

    // ユーザーごとのチャットを表示する
    function showUserChats(userID) {
        const userChatContainer = document.getElementById('userChats');
        userChatContainer.innerHTML = ''; // 既存の内容をクリア

        const selectedUserChats = userChats[userID];
        if (!selectedUserChats) return;

        Object.entries(selectedUserChats.groups).forEach(([groupName, chats]) => {
            const groupDiv = document.createElement('div');
            groupDiv.className = 'group-chat';

            const groupTitle = document.createElement('p');
            groupTitle.className = 'group-name';
            groupTitle.textContent = `${groupName} グループ`;
            groupDiv.appendChild(groupTitle);

            chats.forEach(chat => {
                const chatDiv = document.createElement('p');
                chatDiv.textContent = chat;
                groupDiv.appendChild(chatDiv);
            });

            userChatContainer.appendChild(groupDiv);
        });
    }

    // 初期状態で最初のユーザーのチャットを表示
    window.onload = function () {
        const firstUserID = Object.keys(userChats)[0];
        if (firstUserID) showUserChats(firstUserID);
    };

    function freezeUser(button) {
        const userName = button.getAttribute("data-user-name");

        Swal.fire({
            html: `<h1>ユーザー「${userName}」を凍結しますか？</h1>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'OK',
            cancelButtonText: 'キャンセル',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire("ユーザーを凍結しました", {
                    icon: "success",
                });
                // APIリクエストなどの処理をここに追加
            } else {
                Swal.fire("凍結がキャンセルされました。", {
                    icon: "info",
                });
            }
        });
    }
</script>

</body>
</html>
