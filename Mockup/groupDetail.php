<?php
    require_once 'helpers/GroupDetailDAO.php';
    require_once 'helpers/userDAO.php';
    require_once 'helpers/GroupDAO.php';
   
    include "header.php"; 
  
    $loggedInUser = null;

if (isset($_SESSION['userInfo']) ) {
    $userInfo = $_SESSION['userInfo'];

    $loggedInUser = $_SESSION['userInfo'];
}else{
  header("Location: login.php");
  exit;
}
    $groupDAO = new GroupDAO();
   
    $groupInfo = $groupDAO->getGroup($loggedInUser->UserID);

    //セッションの開始
    if(session_status() === PHP_SESSION_NONE){
      session_start();
}
  if(isset($_GET['GroupID'])){
    //リクエストパラメータのgroupIDを取得する
    $groupID = $_GET['GroupID'];
}
  
  $groupdetail = new GroupDetailDAO();
  $group = $groupdetail->get_GroupDetail1($groupID);

  $groupdetail2 = new GroupDetailDAO();
  $group_list = $groupdetail2->get_groupDetail2($groupID);


?>


<!DOCTYPE html>
<html>
<meta charset="utf-8">
<!--ヘッダー-->
<header>
  <!-- CSS適応 -->

  <link rel="stylesheet" href="CSSUser/GroupDetailBefor.css">
  <link rel="stylesheet" href="CSSUser/Home.css">

  <!-- ロゴ周り表示 ロゴマークを押すとホーム画面に遷移(Home.html) -->

</header>

<div>
  <p id="group">所属グループ一覧</p>
  <input type="button" value="参加" id="groupJoin">
  <a href="genreSelect.html"><input type="button" value="ジャンル選択に戻る" id="back"></a>
</div>


<script src="./jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>

<script>
        // 作成ボタンがクリックされたとき
        $(document).ready(function() {
            // フォームの送信処理をカスタマイズ
            $('#sendbutton').on('submit', function(e) {
                e.preventDefault(); // 通常の送信を防ぐ

                // SweetAlert2を使って確認ダイアログを表示
                Swal.fire({
                    title: '編集確認',
                    text: '編集を確定しますか？',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '確定',
                    cancelButtonText: 'キャンセル',
                    
                }).then((result) => {
                    if (result.isConfirmed) {
                        // 「送信」ボタンが押された場合、フォームを送信
                        this.submit();
                    }
                });
            });
        });
    </script>




<nav class="group">
    <ul>
    <?php foreach ($groupInfo as $var): ?>
      <li>
        <a href="message.php?GroupID=<?= urlencode($var->GroupID) ?>">
          <?= $var->GroupName?>（<?= $var->MemberInfo?>）<br>最終更新日：<?=$var->LastUpdated?><br>ジャンル：<?= $var->Genre ?>
        </a>
       
        <?php if($loggedInUser->UserID == $var->GroupAdminID) : ?>
         <input type="button" onclick="location.href='groupEdit.php?GroupID=<?= urlencode($var->GroupID)?>'" id="groupEditR" value="グループ編集">
          <?php endif; ?>
      </li>
      <?php endforeach; ?>
    </ul>
</a>

  

<p id="groupName">グループ名：<?= $group[0]['GroupName'] ?> (<?= $group[0]['MemberInfo'] ?>)</p>
<p id="groupGenre">グループのジャンル：<?= $group[0]['Genre'] ?> </p>
<div class="aaa">
<ul1>
<?php foreach($group_list as $var) : ?>
  <li1><?= $var->UserName ?><br><?= $var->GakkaName ?></li1>
<?php endforeach; ?>
</ul1>
</div>


<p id=groupEdit>グループ詳細</p><input type="text" id="txtGE" value=<?= $group[0]['GroupDetail'] ?> readonly>