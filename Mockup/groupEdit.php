<?php 
require_once 'helpers/userDAO.php';
require_once 'helpers/GroupDAO.php';
   
    include "header.php"; 
  
    $loggedInUser = null;

    if(isset($_GET['GroupID'])){
        //リクエストパラメータのgroupIDを取得する
        $groupID = $_GET['GroupID'];
    }

if (isset($_SESSION['userInfo']) ) {
    //$userInfo = $_SESSION['userInfo'];

    $loggedInUser = $_SESSION['userInfo'];
}
    $groupDAO = new GroupDAO();
   
    $groupInfo = $groupDAO->getGroup($loggedInUser->UserID);

    $my_group = $groupDAO->get_My_Group($groupID);

    if($_SERVER["REQUEST_METHOD"] === "POST"){
      //作成ボタンが押されたとき
        //グループの内容が空ではなければ
       
  
          //入力されたグループの内容を受け取る
          $GroupName = $_POST['groupName'];
          $GroupDetial = $_POST['groupDetail'];

          $GroupCreateDAO = new GroupDAO();
          $GroupCreateDAO->groupInfoUpdate($groupID,$GroupName,$GroupDetial);
  }
    
    
?>
<!DOCTYPE html>
<html>
  <meta charset="utf-8">
  <header>
<!-- CSS適応 -->
    
    <link rel="stylesheet" href="CSSUser/home.css">
    <link rel="stylesheet" href="CSSUser/groupEdit.css">

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
 <form id="myForm" action="" method="POST">
 <div class="groupEdit">
    <p>グループ名：<input type="text" name="groupName" id="groupName"value="<?= $my_group['GroupName']; ?>"></p><br>
    <p>参加人数：<input type="text" name="sanka" id="sanka" value="<?= $my_group['MaxMember']; ?>" readonly></p><br>
    <p>大ジャンル<input type="text" name="mainGenre" id="mainGenre" value="<?= $my_group['MainGenreName']; ?>"readonly></p><br>
    <p>中ジャンル：<input type="text" name="subGenre" id="subGenre" value="<?= $my_group['SubGenreName']; ?>"readonly></p><br>



    <p id="SETUMEI">グループの説明：</p><br>
    <input type="text" name="groupDetail" id="textbox-2" value="<?= $my_group['GroupDetail']; ?>"/>
</div>
<input type="submit" id="editDetail" value="編集内容を確定する">
</form>



<script src="./jquery-3.6.0.min.js"></script> <!-- jQueryライブラリを読み込み -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2ライブラリを読み込み -->


<!-- グループ詳細更新ボタン ??に遷移(??.html) -->


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
        }).then((result) => {
            if (result.isConfirmed) {
                // 確定ボタンが押された場合、フォームを送信
                this.submit();
            }
        });
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