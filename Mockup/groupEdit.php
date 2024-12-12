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
    
    <link rel="stylesheet" href="CSSUser/home.css">
  </header>
  <div>
  <p id="group">所属グループ一覧</p>
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
         <input type="button" onclick="location.href='groupEdit.php'" id="groupEditR" value="グループ編集">
          <?php endif; ?>
      </li>
      <?php endforeach; ?>
    </ul>
</a>
<!-- グループ編集 -->
<a href="message.html"></a>
<p id=gn>グループ名 ：</p><input type="text" id="groupName">
<p id=sanka>参加人数　：</p>

<label class="selectbox-6">
  <select>
      <option>3</option>
      <option>4</option>
      <option>5</option>
  </select>
</label>

<p id=j1></pid>大ジャンル：</p>
<label class="selectbox-3">
  <select>

      <option>ゲーム</option>
      <option>音楽</option>
      <option>スポーツ</option>
      <option>勉強</option>
  </select>
</label>

<p id=j2></pid>中ジャンル：</p>
  <label class="selectbox-3">
      <select>

      <option>サッカー</option>
      <option>テニス</option>
      <option>バスケットボール</option>
      <option>野球</option>
      <option>バレーボール</option>
      <option>ラグビー</option>
      <option>卓球</option>
      <option>バトミントン</option>
  </select>
</label>
<label>
    <span class="textbox-1-label">グループの説明：</span>
    <input type="text" class="textbox-1" id="textbox-2" />
</label>



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script src="./jquery-3.6.0.min.js"></script>


<!-- グループ詳細更新ボタン ??に遷移(??.html) -->
<input type="submit" id="editDetail" value="編集内容を確定する">

<script>
  $("#editDetail").click(function(){
    Swal.fire({
      text: '編集内容を確定しますか？',
      showCancelButton: true,
      confirmButtonText: 'OK'
    }).then((result) => {
      if (result.value) {
          window.location.href='.html'
      }
    });
  });
  </script>

<!-- 退出ボタン ??に遷移(??.html) -->
<input type="submit" id="Exit" value="グループを削除する">

<script>
  $("#Exit").click(function(){
    Swal.fire({
      text: '"資格勉強の集い"を削除しますか？',
      showCancelButton: true,
      confirmButtonText: 'OK'
    }).then((result) => {
      if (result.value) {
          window.location.href='home.html'
      }
    });
  });
  </script>


</html>