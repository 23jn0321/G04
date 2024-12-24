<?php 
require_once 'helpers/userDAO.php';
require_once 'helpers/GroupDAO.php';

include "header.php"; 

$loggedInUser = null;

// URLからGroupID取得
if (isset($_GET['GroupID'])) {
    $groupID = $_GET['GroupID'];
}

// セッションからユーザー情報を取得
if (isset($_SESSION['userInfo'])) {
    $loggedInUser = $_SESSION['userInfo'];
}

$groupDAO = new GroupDAO();
$groupInfo = $groupDAO->getGroup($loggedInUser->UserID);
$my_group = $groupDAO->get_My_Group($groupID);

// 編集内容の保存処理
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["groupName"])) {
    $GroupName = $_POST['groupName'];
    $GroupDetial = $_POST['groupDetail'];

    $GroupCreateDAO = new GroupDAO();
    $GroupCreateDAO->groupInfoUpdate($groupID, $GroupName, $GroupDetial);
    header('Location: message.php?GroupID='. urlencode($groupID));
    exit;
}

// 削除処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteGroup'])) {
    $groupDAO = new GroupDAO();
    $groupDAO->deleteGroup($groupID);
    header('Location: home.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
  <meta charset="utf-8">
  <header>
<!-- CSS適応 -->
    
    <link rel="stylesheet" href="CSSUser/GroupEdit.css">

  </header>
  <div class="JoinGroup">
  <p id="title">所属グループ一覧</p>
</div>
<a href="message.html"></a>
<p>
    <!-- グループ表示 -->
    <nav class="group">
    <ul>
    <?php foreach ($groupInfo as $var): ?>
      <li>
        <a href="message.php?GroupID=<?= urlencode($var->GroupID) ?>">
          <?= $var->GroupName?>（<?= $var->MemberInfo?>）<br>最終更新日：<?=$var->LastUpdated?><br>ジャンル：<?= $var->Genre ?>
        </a>
       
        <?php if($loggedInUser->UserID == $var->GroupAdminID) : ?>
         <input type="button" onclick="location.href='groupEdit.php?GroupID=<?= urlencode($var->GroupID) ?>'" id="groupEditR" value="グループ編集">
          <?php endif; ?>
      </li>
      <?php endforeach; ?>
    </ul>
</a>
</nav>  
<!-- グループ編集 -->
 <form id="myForm" action="" method="POST">
 <div class="groupEdit">
    <p>グループ名：<input type="text" name="groupName" id="groupName" value="<?= $my_group['GroupName']; ?>"></p>
    <p>最大人数　：<input type="text" name="sanka" id="sanka" value="<?= $my_group['MaxMember']; ?>" readonly></p>
    <p>大ジャンル：<input type="text" name="mainGenre" id="mainGenre" value="<?= $my_group['MainGenreName']; ?>"readonly></p>
    <p>中ジャンル：<input type="text" name="subGenre" id="subGenre" value="<?= $my_group['SubGenreName']; ?>"readonly></p>  

    <p id="SETUMEI">グループの説明：</p><br>
    <textarea id="textbox-2" name="groupDetail" rows="5" cols="30"><?= $my_group['GroupDetail']; ?></textarea>
</div>
<br><br><br><br><br>
<input type="submit" id="editDetail" value="編集内容を確定する">
</form>

<form id="deleteForm" action="" method="POST" >
  <input type="hidden" name="deleteGroup" value="1">
  <input type="submit" id="Exit" value="グループを削除する">
</form>







<script src="./jquery-3.6.0.min.js"></script> <!-- jQueryライブラリを読み込み -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2ライブラリを読み込み -->


<script>
$(document).ready(function() {
    // フォームの送信イベントをカスタマイズ
    $('#myForm').on('submit', function(e) {
        e.preventDefault(); // デフォルトの送信処理を防ぐ

        // SweetAlert2を使って確認ダイアログを表示
        Swal.fire({
            title: '編集確認', // ダイアログのタイトル
            text: '編集を確定しますか？', // ダイアログの内容
            icon: 'question', // アイコン（質問マーク）
            showCancelButton: true, // キャンセルボタンを表示
            confirmButtonText: '確定', // 確定ボタンのテキスト
            cancelButtonText: 'キャンセル', // キャンセルボタンのテキスト
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // 確定ボタンが押された場合、フォームを送信
                e.target.submit();
            }
        });
    });
});
</script>




<script>
$(document).ready(function () {
    $('#deleteForm').on('submit', function (e) {
        e.preventDefault(); // デフォルト送信をストップ

        Swal.fire({
            title: '⚠️ 削除確認 ⚠️',
            text: '削除すると二度と戻せなくなります',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '本当に削除する',
            cancelButtonText: 'キャンセル',
        
            customClass: {
              title: 'custom-title',
              content: 'custom-content',
              cancelButton: 'cancel-btn',
              confirmButton: 'confirm-btn'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.submit(); // サーバーサイドに送信
            }
        });
    });
});
</script>

<style>
  .custom-title{
    color:#FF0000;
    font-weight: bold;
    text-shadow: 2px 2px 5px rgba(0,0,0,0.8);
  }
  .custom-content{
    color: #FFFFFF;
    font-size: 18px;
    font-family: 'Arial', sans-serif;
  }
  .confirm-btn{
    background-color: #FF6347;
    color: white;
    font-weight: bold ;
  }

  .cancel-btn{
    background-color: #2F4F4F;
    color: white;
  }
</style>

</html>