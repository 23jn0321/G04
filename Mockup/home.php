<?php 
 require_once 'helpers/userDAO.php';
    require_once 'helpers/GroupDAO.php';
   
    include "header.php"; 
  
    $loggedInUser = null;

if (isset($_SESSION['userInfo']) ) {
    //$userInfo = $_SESSION['userInfo'];

    $loggedInUser = $_SESSION['userInfo'];
}
    $groupDAO = new GroupDAO();
    $groupInfo = $groupDAO->getGroup($loggedInUser->UserID);
    
?>



<!DOCTYPE html>
<html>
<meta charset="utf-8">
<header>
  <!-- CSS適応 -->
  <link rel="stylesheet" href="CSSUser/Header.css">
  <link rel="stylesheet" href="CSSUser/Home.css">
</header>


<div>
  <p id="title">所属グループ一覧</p>
</div>

  <p>
    <!-- グループ表示 -->
    <nav class="group">
    <ul>
    <?php foreach ($groupInfo as $var): ?>
      <li>
        <a href="message.php?GroupID=<?= urlencode($var->GroupID) ?>"><p><?= $var->GroupName?>
        （<?= $var->MemberInfo?>）<br>最終更新日：<?=$var->LastUpdated?><br>ジャンル：<?= $var->Genre ?>
        </p></a>
      </li>
      <?php endforeach; ?>
    </ul>
</a>
</p>

<!-- お知らせボックス -->
<div class="box">
  <p>お知らせ表示</p>
</div>

<!-- マッチングボタン　ジャンル選択に遷移(genreSelect.html) -->
<a href="groupCreate.php"><input type="submit" id="MatchingButton" value="マッチング"></a>

</html>