<?php
    require_once './helpers/messageDAO.php';
    require_once 'helpers/userDAO.php';
    require_once 'helpers/GroupDAO.php';
    
    include "header.php";
     
    //セッションの開始
  if(session_status() === PHP_SESSION_NONE){
      session_start();

  }

  if(isset($_GET['GroupID'])){
    //リクエストパラメータのgroupIDを取得する
    $groupID = $_GET['GroupID'];
}
$loggedInUser = null;

if (isset($_SESSION['userInfo']) ) {
    $userInfo = $_SESSION['userInfo'];

    $loggedInUser = $_SESSION['userInfo'];
}
    $groupDAO = new GroupDAO();
    $groupInfo = $groupDAO->getGroup($loggedInUser->UserID);

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['message'])) {
        $message = $_POST['message']; // テキストボックスのメッセージを受け取る
        $userId = $_SESSION['userInfo']; // ユーザーIDなどをセッションから取得
        $messageDAO=new messageDAO();
        $messageDAO->messageInsert($groupID,$userId->UserID,$message);
    }
}

$messageDAO = new messageDAO();
$messages = $messageDAO->getMessagesByGroup($groupID); // GroupIDでメッセージを取得

$myMessages = []; // 自分のメッセージ
$otherMessages = []; // 他人のメッセージ

foreach ($messages as $msg) {
    if ($msg->SendUserID == $loggedInUser->UserID) {
        $myMessages[] = $msg; // 自分のメッセージに分類
    } else {
        $otherMessages[] = $msg; // 他人のメッセージに分類
    }
}

?>

<!DOCTYPE html>
<html>
  <meta charset="utf-8">
  <header>
<!-- CSS適応 -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    
    <link rel="stylesheet" href="CSSUser/Home.css">
    <link rel="stylesheet" href="CSSUser/Message.css">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>


<input type="button" id="btn08" class="secret" value="">
<input type="button" id="btn09" class="secret" value="">
    
  </header>
  <div>
  <p id="title">所属グループ一覧</p>
</div>


    <!-- グループ表示 -->
    <nav class="group">
    <ul>
    <?php foreach ($groupInfo as $var): ?>
      <li>
        <a href="message.php?GroupID=<?= urlencode($var->GroupID) ?>">
          <?= $var->GroupName?>（<?= $var->MemberInfo?>）<br>最終更新日：<?=$var->LastUpdated?><br>ジャンル：<?= $var->Genre ?>
        </a>
        <?php if($loggedInUser->UserID == $var->GroupAdminID) : ?>
         <input type="button" onclick="location.href='groupEdit.php?GroupID=<?= urlencode($var->GroupID)?>'" id="groupEditR" value="グループ編集" >
          <?php endif; ?>
      </li>
      <?php endforeach; ?>
    </ul>
</a>
</p>


<header class="header"></header>
  <div class="header__inner">
 
    <buttom class="drawer__button">
      <span></span>
      <span></span>
      <span></span>
    </buttom>
    <nav class="drawer__nav">
      <div class="drawer__nav__inner">
        <th class="drawer__nav__menu">
          <tr class="drawer__nav__item">
            <a class="drawer__nav__link" href="#"><nobr><p>所属グループ一覧　　<button onclick="location.href='groupEdit.html'">グループ編集</button></p></nobr></a>
          </tr>
          <tr class="drawer__nav__item">
            <a class="drawer__nav__link" href="#"><ul><li><p>テスト期間がち勉強(4/5)<br>最終更新日：10/8<br>ジャンル：勉強 / テスト勉強</p></li></ul></a>
          </tr>
          <tr class="drawer__nav__item">
            <a class="drawer__nav__link" href="#"><ul><li><p>プログラミング愛好家(3/4)<br>最終更新日：10/3<br>ジャンル：勉強 / プログラミング</p></li></ul></a>
          </tr>
          <tr class="drawer__nav__item">
            <a class="drawer__nav__link" href="#"><ul><li><p>テスト勉強(4/4)<br>最終更新日：9/30<br>ジャンル：勉強 / テスト勉強</p></li></ul></a>
          </tr>
          <tr class="drawer__nav__item">
            <a class="drawer__nav__link" href="#"><ul><li><p>資格勉強の集い(3/5)<br>最終更新日：10/13<br>ジャンル：勉強 / 資格勉強</p></li></ul></a>
          </tr>
      </th>
      </div>
    </nav>
  </div>
</header>

<script src="./jquery-3.6.0.min.js"></script>




<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<!-- メッセージ機能 (https://naruweb.com/coding/linechat/)から引用 -->
<form action="" method="POST" id="chatMessage">
<div class="room">
        <ulH id="messageContainer">
            <!-- メッセージが表示されます -->
        </ulH>
    </div>

    <!-- Message Input -->
    <div class="send">
        <form id="chatMessage" method="POST">
            <input type="text" id="message" name="message" placeholder="メッセージを入力してください" required>
            <input type="submit" value="Send" id="send">
        </form>
    </div>

    <script>
        $(document).ready(function () {
            const groupID = <?= json_encode($groupID) ?>; // PHPからGroupIDを取得
            const loggedInUserID = <?= json_encode($loggedInUser->UserID) ?>;
            const messageContainer = $("#messageContainer");

            // メッセージを取得
            function fetchMessages() {
                $.ajax({
                    url: "getMessage.php",
                    type: "GET",
                    data: { GroupID: groupID },
                    dataType: "json",
                    success: function (response) {
                        if (response.status === "success") {
                            renderMessages(response.messages);
                        } else {
                            console.error(response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching messages:", error);
                    }
                });
            }

            // メッセージを表示
            function renderMessages(messages) {
                let html = "";
                messages.forEach(msg => {
                    if (msg.SendUserID == loggedInUserID) {
                        // 自分のメッセージ
                        html += `
                        <liH class="chat me">
                            <label for="btn09" class="mes">${msg.MessageDetail}</label>
                            <div class="status">あなた<br>${msg.SendTime}</div>
                        </liH>`;
                    } else {
                        // 相手のメッセージ
                        html += `
                        <liH class="chat you" data-user-id="${msg.SendUserID}">
                            <label for="btn08" class="mes clickable">${msg.MessageDetail}</label>
                            <div class="status">${msg.SendUserName}<br>${msg.SendTime}</div>
                        </liH>`;
                    }
                });
                messageContainer.html(html);

// 動的に追加される要素に対してイベントを設定
$(document).off("click", ".clickable").on("click", ".clickable", function () {
    const userID = $(this).closest(".chat").data("user-id");
    if (userID) {
        $.ajax({
            url: "getUserInfo.php",
            type: "GET",
            data: { UserID: userID },
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    Swal.fire({
                        title: response.userName, // 大文字小文字を合わせる
                        html: `<p>${response.profileComment}<br><br><br><br><br></p> 
                              <button id="reportButton">
                                通報する
                            </button>`,
                            showConfirmButton: false,
              
                        didRender: () => {
                            // 通報ボタンのイベント処理
                            $("#reportButton").on("click", function () {
                              console.log("通報画面に遷移中...");
                                // report.php に遷移し、UserIDを渡す
                                window.location.href = `report.php?UserID=${encodeURIComponent(userID)}`;
                            });
                          }
                    });
                } else {
                    console.error(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching user info:", error);
            }
        });
    }
});

}

            

            // 初期化
            fetchMessages();

            // 3秒ごとに更新
            setInterval(fetchMessages, 3000);

            // メッセージ送信
            $("#chatMessage").on("submit", function (e) {
                e.preventDefault(); // フォーム送信を阻止
                const message = $("#message").val();

                $.ajax({
                    url: "", // 現在のページで処理
                    type: "POST",
                    data: { message: message },
                    success: function () {
                        $("#message").val(""); // 入力をクリア
                        fetchMessages(); // メッセージを再取得
                    },
                    error: function (xhr, status, error) {
                        console.error("Error sending message:", error);
                    }
                });
            });
        });
    </script> 
  </form>

  <?php
          $studentDAO = new StudentDAO();
          $user = $studentDAO->get_newUserInfo($loggedInUser->UserID ?? '');
  ?>


<script>
  $("#btn09").click(function () {
    Swal.fire({
      title: '<?= $user[0]['UserName'] ?>',
      html: '<br><?= $user[0]['ProfileComment'] ?><br><br>',
      showCloseButton : true
    })
  });
</script>




</html> 

<script>$(function () {
  // ハンバーガーボタンクリックで実行
  $(".drawer__button").click(function () {
    $(this).toggleClass("active");
    $(".drawer__nav").toggleClass("active");
  });
  // function
});</script>
