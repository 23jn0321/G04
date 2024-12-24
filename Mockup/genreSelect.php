<?php 
require_once 'helpers/userDAO.php';
require_once 'helpers/GroupDAO.php';
require_once 'helpers/GenreSelectDAO.php';
   
    include "header.php"; 
  
    $loggedInUser = null;

if (isset($_SESSION['userInfo']) ) {
    //$userInfo = $_SESSION['userInfo'];

    $loggedInUser = $_SESSION['userInfo'];
}
    $groupDAO = new NewGroupDAO();
 
    $groupInfo = $groupDAO->getNewGroup($loggedInUser->UserID);
    
    $groupDAO = new GroupDAO();

// ジャンルを取得
    $genreSelectDAO = new GenreSelectDAO();
    $gameGenre = $genreSelectDAO->get_Game_SubGenre();
    $musicGenre = $genreSelectDAO->get_Music_SubGenre();
    $sportsGenre = $genreSelectDAO->get_Sports_SubGenre();
    $studyGenre = $genreSelectDAO->get_Study_SubGenre();


// グループ情報を取得
?>

<!DOCTYPE html>
<html>
    <body>
        <meta charset="utf-8">
        <header>
<!-- CSS適応 -->
        <link rel="stylesheet" href="CSSUser/GenreSelect.css">
 

  <div>
    <p id="title">最新のグループ　</p>
  </div>
<!-- 所属グループ -->
<nav class="newGroup">
    <ul>
        <?php if(empty($groupInfo)) : ?>
        <li>
            最新グループがありません
        </li>
        <?php else: ?>
    <?php foreach ($groupInfo as $var): ?>
      <li>
        <a href="groupDetail.php?GroupID=<?= urlencode($var->GroupID) ?>">
          <?= $var->GroupName?>（<?= $var->MemberInfo?>）<br>最終更新日：<?=$var->LastUpdated?><br>ジャンル：<?= $var->Genre ?>
        </a>
      </li>
      <?php endforeach; ?>
      <?php endif; ?>
    </ul>
</nav>
<!-- グループ作成ボタン -->
    <button id="GroupSakusei" onclick="location.href='groupCreate.php'">グループ作成</button>
<!-- ジャンル選択 -->
<form action="search.php" method="GET">
    <div class="genreSelect">
        <details class="accordion-004">
<!-- ゲームジャンル -->
            <summary>ゲーム</summary>
            <label>
                <input type="checkbox" class="select-all" data-target="game-checkboxes">
                すべて選択
            </label>
            <?php foreach ($gameGenre as $genre): ?>
                <label>
                    <input type="checkbox" name="genre[]" value="<?= $genre[0] ?>" class="game-checkboxes">
                    <?= $genre[1]?>
                </label>
            <?php endforeach; ?>
        </details>
        <details class="accordion-004">
<!-- 音楽ジャンル -->
            <summary>音楽</summary>
            <label>
                <input type="checkbox" class="select-all" data-target="music-checkboxes">
                すべて選択
            </label>
            <?php foreach ($musicGenre as $genre): ?>
                <label>
                    <input type="checkbox" name="genre[]" value="<?= $genre[0] ?>" class="music-checkboxes">
                    <?= $genre[1] ?>
                </label>
            <?php endforeach; ?>
        </details>
        <details class="accordion-004"> 
<!-- スポーツジャンル -->
            <summary>スポーツ</summary>
            <label>
                <input type="checkbox" class="select-all" data-target="sports-checkboxes">
                すべて選択
            </label>
            <?php foreach ($sportsGenre as $genre): ?>
                <label>
                    <input type="checkbox" name="genre[]" value="<?= $genre[0] ?>" class="sports-checkboxes">
                    <?= $genre[1] ?>
                </label>
            <?php endforeach; ?>
            </details>
        <details class="accordion-004">
<!-- 勉強ジャンル -->
            <summary>勉強</summary>
            <label>
                <input type="checkbox" class="select-all" data-target="study-checkboxes">
                すべて選択
            </label>
            <?php foreach ($studyGenre as $genre): ?>
                <label>
                    <input type="checkbox" name="genre[]" value="<?= $genre[0] ?>" class="study-checkboxes">
                    <?= $genre[1] ?>
                </label>
            <?php endforeach; ?>
        </details>
    </div>
    <button type="submit" id="Search">検索</button>
</form>



<!-- JavaScript -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // すべて選択チェックボックスにイベントリスナーを追加
        const selectAllCheckboxes = document.querySelectorAll(".select-all");

        selectAllCheckboxes.forEach(selectAll => {
            selectAll.addEventListener("change", function () {
                const targetClass = this.getAttribute("data-target");
                const targetCheckboxes = document.querySelectorAll(`.${targetClass}`);

                targetCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked; // すべて選択 or 解除
                });
            });
        });
    });
</script>
    </body>
</html>
