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


<div class="JoinGroup">
  <p id="title">所属グループ一覧</p>
</div>

    <!-- グループ表示 -->
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
      グループ名：<?= htmlspecialchars($var->GroupName) ?><br>
      所属人数 ：<?= htmlspecialchars($var->MemberInfo) ?><br>
      最終更新日：<?= htmlspecialchars($var->LastUpdated) ?><br>
      ジャンル：<?= htmlspecialchars($var->Genre) ?>
  </a>
    <?php if ($loggedInUser->UserID == $var->GroupAdminID) : ?>
      <input type="button" onclick="location.href='groupEdit.php?GroupID=<?= urlencode($var->GroupID) ?>'" id="groupEditR" value="グループ編集">
    <?php endif; ?>
  </li>
<?php endforeach; ?>
<?php endif; ?>
</ul>
</nav>

<!-- お知らせボックス -->

<!-- マッチングボタン　ジャンル選択に遷移(genreSelect.html) -->






<div class="btn">
  <button class="MachingButton" onclick="location.href='genreSelect.php'">マッチング</button>
</div>



</html>