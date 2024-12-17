<?php
    require_once 'helpers/GroupDetailDAO.php';
    include "header.php";

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
  //var_dump($group);

  
?>


<!DOCTYPE html>
<html>
<meta charset="utf-8">
<!--ヘッダー-->
<header>
  <!-- CSS適応 -->
  <link rel="stylesheet" href="CSSUser/Header.css">
  <link rel="stylesheet" href="CSSUser/GroupDetailBefor.css">

  <!-- ロゴ周り表示 ロゴマークを押すとホーム画面に遷移(Home.html) -->

</header>

<div>
  <p id="group">所属グループ一覧</p>
  <a href="groupEdit.html"><input type="button" value="グループ編集" id="groupEdit"></a>
  <input type="button" value="参加" id="groupJoin">
  <a href="genreSelect.html"><input type="button" value="ジャンル選択に戻る" id="back"></a>
</div>


<script src="./jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>

<script>
  $("#groupJoin").click(function () {
    Swal.fire({
      html: '"資格勉強の集い"に参加しますか？',
      showCancelButton: true,
      confirmButtonText: 'OK',
      type: 'info'
    }).then((result) => {
      if (result.value) {
        window.location.href = 'message.html'
      }
    });
  });
</script>





<a href="message.html">
  <p>
    <!-- グループ表示 -->
    <ul>
      <li>
        <p>資格勉強の集い(3/5)<br>最終更新日：10/13<br>ジャンル：勉強 / 資格勉強</p>
      </li>
      <li>
        <p>テスト期間がち勉強(4/5)<br>最終更新日：10/8<br>ジャンル：勉強 / テスト勉強</p>
      </li>
      <li>
        <p>プログラミング愛好家(3/4)<br>最終更新日：10/3<br>ジャンル：勉強 / プログラミング</p>
      </li>
      <li>
        <p>テスト勉強(4/4)<br>最終更新日：9/30<br>ジャンル：勉強 / テスト勉強</p>
      </li>
      <li>
        <p>K-POP愛(2/3)<br>最終更新日：9/22<br>ジャンル：音楽 / K-POP</p>
      </li>
    </ul>
</a>
</p>


<p id="groupName">グループ名：<?= $group[0]['GroupName'] ?> (<?= $group[0]['MemberInfo'] ?>)</p>
<p id="groupGenre">グループのジャンル：<?= $group[0]['Genre'] ?> </p>

<p id=groupMem>参加者</p><textarea id="txtGM" rows="5" cols="33" value=<?= $group->Genre ?> readonly>
電子花子               情報処理科      １年
ベンキョ・ジンセイ      情報処理科      ２年
大橋雪乃成             情報処理科      ２年
</textarea></p>

<p id=groupEdit>グループ詳細</p><input type="text" id="txtGE" value=<?= $group[0]['GroupDetail'] ?> readonly>


