<?php 
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
    
    
?>



<!DOCTYPE html>
<html>
<meta charset="utf-8">
<header>
  <!-- CSS適応 -->
  <link rel="stylesheet" href="CSSUser/Home.css">
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
         <input type="button" onclick="location.href='groupEdit.php?GroupID=<?= urlencode($var->GroupID)?>'" id="groupEditR" value="グループ編集">
          <?php endif; ?>
      </li>
      <?php endforeach; ?>
    </ul>
</a>

<!-- お知らせボックス -->

<!-- マッチングボタン　ジャンル選択に遷移(genreSelect.html) -->
<button id="MatchingButton" onclick="location.href='genreSelect.php'">マッチング</button>

</html>