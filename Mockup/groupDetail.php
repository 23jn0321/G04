<?php
require_once 'helpers/GroupDetailDAO.php';
require_once 'helpers/userDAO.php';
require_once 'helpers/GroupDAO.php';

include "header.php";

$loggedInUser = null;

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (isset($_SESSION['userInfo'])) {
  $userInfo = $_SESSION['userInfo'];

  $loggedInUser = $_SESSION['userInfo'];
} else {
  header("Location: login.php");
  exit;
}
$groupDAO = new GroupDAO();

$groupInfo = $groupDAO->getGroup($loggedInUser->UserID);

if (isset($_GET['GroupID'])) {
  //リクエストパラメータのgroupIDを取得する
  $groupID = $_GET['GroupID'];
} 
$groupdetail = new GroupDetailDAO();
$group = $groupdetail->get_GroupDetail1($groupID);
$groupdetail2 = new GroupDetailDAO();
$group_list = $groupdetail2->get_groupDetail2($groupID);

$groupdetail3 = new GroupDetailDAO();
$isJoined = $groupdetail3->get_join($loggedInUser->UserID, $groupID);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
 
  $action = isset($_POST['action']) ? $_POST['action'] : null;
  if ($groupID === null) {
    die('GroupIDが指定されていません。');
  }

  $groupdetail4 = new GroupDetailDAO();

  if ($action === 'leave') {  
    
    $groupdetail4->delete($loggedInUser->UserID, $groupID);
    var_dump($userInfo); 
    header('Location:home.php?GroupID=' . urlencode($groupID));
    exit;}

   elseif ($action === 'join') { 
    $groupdetail4->insert($loggedInUser->UserID, $groupID);
    var_dump($userInfo); 
    header('Location: message.php?GroupID=' . urlencode($groupID));// actionパラメータを追加

    exit;}
   }
?>
<!DOCTYPE html>
<html>
<meta charset="utf-8">
<!--ヘッダー-->
<header>
  <!-- CSS適応 -->
  <link rel="stylesheet" href="CSSUser/GroupDetailBefor.css">
</header>
  <div>
    <body>
      <p id="group">所属グループ一覧</p>
    </body>
  </div>

  <button id="back" onclick="location.href='genreSelect.php'">ジャンル選択に戻る</button>

<script src="./jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
  $(document).ready(function() {
    // ボタンのクリックイベントを設定
    $('#actionButton').on('click', function(e) {
      e.preventDefault(); // デフォルトの送信処理を防ぐ

      const isJoined = <?= json_encode($isJoined) ?>; // PHPから参加状況を渡す
      const groupName = <?= json_encode($group[0]['GroupName']) ?>; // グループ名を取得
      const form = $('#actionForm'); // フォーム要素を取得

      if (isJoined) {
        // 退会処理
        Swal.fire({
          title: `${groupName}から退会しますか？`, // ダイアログのタイトル
          icon: 'warning', // アイコン（警告マーク）
          showCancelButton: true, // キャンセルボタンを表示
          confirmButtonText: '退会', // 確定ボタンのテキスト
          cancelButtonText: 'キャンセル', // キャンセルボタンのテキスト
          reverseButtons: true
        }).then((result) => {
          if (result.isConfirmed) {
                    form.find('input[name="action"]').val('leave');
                    // actionパラメータを追加
                    form.submit(); // フォームを送信
          }
        });
      } else {
        // 参加処理
        Swal.fire({
          title: `${groupName}に参加しますか？`, // ダイアログのタイトル
          icon: 'question', // アイコン（質問マーク）
          showCancelButton: true, // キャンセルボタンを表示
          confirmButtonText: '確定', // 確定ボタンのテキスト
          cancelButtonText: 'キャンセル', // キャンセルボタンのテキスト
          reverseButtons: true
        }).then((result) => {
          if (result.isConfirmed) {
            // 確定ボタンが押された場合、参加用のアクションを送信
                   form.find('input[name="action"]').val('join'); 
                    form.submit(); // フォームを送信
          }
        });
      }
    });
  });
</script>

<form id="actionForm" method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?GroupID=' . urlencode($groupID) ?>">
<input type="hidden" name="action" value="">
  <button id="actionButton" type="button" class="btn <?= $isJoined ? 'btn-danger' : 'btn-primary' ?>">
    <?= $isJoined ? '退会する' : '参加する' ?>
  </button>
</form>

<div>
<nav class="group">
  <ul>
  <?php if (empty($groupInfo)): ?>
         <li>
            所属グループがありません。<br>
            グループに参加しましょう！
          </li>
          <?php else: ?>
    <?php foreach ($groupInfo as $var): ?>
      <li>
        <a href="message.php?GroupID=<?= urlencode($var->GroupID) ?>">
          <?= $var->GroupName ?>（<?= $var->MemberInfo ?>）<br>最終更新日：<?= $var->LastUpdated ?><br>ジャンル：<?= $var->Genre ?>
        </a>
        <?php if ($loggedInUser->UserID == $var->GroupAdminID) : ?>
          <input type="button" onclick="location.href='groupEdit.php?GroupID=<?= urlencode($var->GroupID) ?>'" id="groupEditR" value="グループ編集">
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
    <?php endif; ?>
  </ul>
</nav>
</div>
<div>
  <p id="groupName">グループ名：<?= $group[0]['GroupName'] ?>(<?= $group[0]['MemberInfo'] ?>)</p>
  <p id="groupGenre">グループのジャンル：<?= $group[0]['Genre'] ?> </p>
  <p id="mem">参加者一覧</p>
  <div class="Sanka">
  <ul1>
    <?php foreach ($group_list as $var) : ?>
      <li1><?= $var->UserName ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $var->GakkaName ?> <br></li1>
    <?php endforeach; ?>
  </ul1>
  </div>
  <p id="groupEdit">  グループ詳細</p>
  <input type="text" id="txtGE" value=<?= $group[0]['GroupDetail'] ?> readonly>
  </div>