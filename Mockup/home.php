<!DOCTYPE html>
<html>
<meta charset="utf-8">
<header>
  <!-- CSS適応 -->
  <link rel="stylesheet" href="CSSUser/Header.css">
  <link rel="stylesheet" href="CSSUser/Home.css">
</header>
<?php include "header.php"; ?>

<div>
  <p id="group">所属グループ一覧</p>
</div>

  <p>
    <!-- グループ表示 -->
    <ul>
      <li>
        <p>資格勉強の集い(3/5)<br>最終更新日：10/13<br>ジャンル：勉強 / 資格勉強</p><a href="groupEdit.html"><input type="button" value="グループ編集">
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

<!-- お知らせボックス -->
<div class="box">
  <p>お知らせ表示</p>
</div>

<!-- マッチングボタン　ジャンル選択に遷移(genreSelect.html) -->
<a href="genreSelect.html"><input type="submit" id="MatchingButton" value="マッチング"></a>

</html>