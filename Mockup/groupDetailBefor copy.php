<?php

      require_once './helpers/GruopDAO.php';

      if($_SERVER["REQUEST_METHOD"] === "POST"){
         

        $GroupDAO = new GruopDetailDAO();
        $sss = 101;
        $GroupDAO->getGroup($GroupID,$GroupName,$MemberInfo,$LastUpdated,$Genre,$gakusekiNo);

        header('Location: message.php');
        exit;
        
        
}
?>


<!DOCTYPE html>
<html>
<meta charset="utf-8">
<header>
  <!-- CSS適応 -->
  <link rel="stylesheet" href="CSSUser/Header.css">
  <link rel="stylesheet" href="CSSUser/GroupDetailBefor.css">

  <!-- ロゴ周り表示 ロゴマークを押すとホーム画面に遷移(Home.html) -->
  <a href="home.html"><img src="jecMatching/Jec.jpg" width="450px"></a>
  <input type="text" id="name" value="電子太郎 さん" placeholder="ニックネームを入力してください" readonly>
  <a href="edit.html"><input type="button" value="編集" id="edit"></a>
  <hr>
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
<p id="groupName">グループ名：資格勉強の集い(3/5)</p>
<p id=groupGenre>グループのジャンル</p><input type="text" id="txtGG" value="勉強、資格勉強" 　readonly>
<p id=groupMem>参加者</p><textarea id="txtGM" rows="5" cols="33" value="" 　readonly>
電子花子               情報処理科      １年
ベンキョ・ジンセイ     情報処理科      ２年
大橋雪乃成             情報処理科      ２年
</textarea></p>
<p id=groupEdit>グループ詳細</p><input type="text" id="txtGE" value="誰でも歓迎です！！資格取得に向けてみんなで勉強しましょう！！！" 　readonly>


