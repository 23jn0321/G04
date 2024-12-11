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
    //$userInfo = $_SESSION['userInfo'];

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
        <ul class="drawer__nav__menu">
          <li class="drawer__nav__item">
            <a class="drawer__nav__link" href="#"><nobr><p>所属グループ一覧　　<button onclick="location.href='groupEdit.html'">グループ編集</button></p></nobr></a>
          </li>
          <li class="drawer__nav__item">
            <a class="drawer__nav__link" href="#"><ul><li><p>テスト期間がち勉強(4/5)<br>最終更新日：10/8<br>ジャンル：勉強 / テスト勉強</p></li></ul></a>
          </li>
          <li class="drawer__nav__item">
            <a class="drawer__nav__link" href="#"><ul><li><p>プログラミング愛好家(3/4)<br>最終更新日：10/3<br>ジャンル：勉強 / プログラミング</p></li></ul></a>
          </li>
          <li class="drawer__nav__item">
            <a class="drawer__nav__link" href="#"><ul><li><p>テスト勉強(4/4)<br>最終更新日：9/30<br>ジャンル：勉強 / テスト勉強</p></li></ul></a>
          </li>
          <li class="drawer__nav__item">
            <a class="drawer__nav__link" href="#"><ul><li><p>資格勉強の集い(3/5)<br>最終更新日：10/13<br>ジャンル：勉強 / 資格勉強</p></li></ul></a>
          </li>
        </ul>
      </div>
    </nav>
  </div>
</header>

<script src="./jquery-3.6.0.min.js"></script>




<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>


<!-- メッセージ機能 (https://naruweb.com/coding/linechat/)から引用 -->
<form action="" method="POST" id="chatMessage">
<div class="room">
    <ul>
      <li class="chat you" id="btn08">   <!-- 相手のメッセージにはclass「you」をつける。 -->
        <label for="btn08"class="mes" id="btn08">相手のメッセージ相手のメッセージ相手のメッセージ相手のメッセージ相手のメッセージ相手のメッセージ相手のメッセージ相手のメッセージ相手のメッセージ相手のメッセージ相手のメッセージ</label>
        <div class="status">電子花子<br>17:00</div>
      </li>
      <li class="chat me" id="btn09">    <!-- 相手のメッセージにはclass「me」をつける。 -->
        <label for="btn09" class="mes" >自分のメッセージ自分のメッセージ自分のメッセージ自分のメッセージ自分のメッセージ自分のメッセージ自分のメッセージ自分のメッセージ自分のメッセージ自分のメッセージ自分のメッセージ自分のメッセージ自分のメッセージ</label>
        <div class="status">電子太郎<br>17:05</div>
      </li>
    </ul>
  </div>

  <div class="send">
    <input type="text" id="message" name="message" placeholder="メッセージを入力してください" required>
    <input type="submit" value="Send" id="send">
    <a href="groupDetailAfter.html"><input type="button" value="グループ詳細" id="groupDetail"></a>
  </div>
  </form>
  <script>

    $("#btn08").click(function () {
      Swal.fire({
        
        html: '<br>電子花子<br>高度情報処理科<br><br>勉強が好きです。よろしくお願いします<br><br>',
     
        confirmButtonText: '通報',
        showCloseButton : true,
        confirmButtonColor : '#990606'
      }).then((result) => {
        if (result.value) {
          window.location.href = 'report.html'
        }
      });
    });
</script>

<script>
  $("#btn09").click(function () {
    Swal.fire({
      html: '<br>電子太郎<br>情報処理科<br><br>よろしくお願いします<br><br>',
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
