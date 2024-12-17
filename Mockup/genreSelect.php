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
    $groupInfo = $groupDAO->getNewGroup();
    
    $groupDAO = new GroupDAO();

// ジャンルを取得
    $genreSelectDAO = new GenreSelectDAO();
    $gameGenre = $genreSelectDAO->get_Game_SubGenre();
    $musicGenre = $genreSelectDAO->get_Music_SubGenre();
    $sportsGenre = $genreSelectDAO->get_Sports_SubGenre();
    $studyGenre = $genreSelectDAO->get_Study_SubGenre();


    var_dump($gameGenre);
// グループ情報を取得
?>

<!DOCTYPE html>
<html>
    <body>
        <meta charset="utf-8">
        <header>
<!-- CSS適応 -->
        <link rel="stylesheet" href="CSSUser/Header.css">
        <link rel="stylesheet" href="CSSUser/GenreSelect.css">
 

  <div>
    <p id="title">最新のグループ</p>
  </div>
<!-- 所属グループ -->
<nav class="newGroup">
    <ul>
    <?php foreach ($groupInfo as $var): ?>
      <li>
        <a href="groupDetailBefor.html?GroupID=<?= urlencode($var->GroupID) ?>">
          <?= $var->GroupName?>（<?= $var->MemberInfo?>）<br>最終更新日：<?=$var->LastUpdated?><br>ジャンル：<?= $var->Genre ?>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
<!-- グループ作成ボタン -->
    <a href="groupCreate.php"><input type="submit" value="グループ作成" id="groupCreate"></a>  
<!-- ジャンル選択 -->
<form action="search.php" method="GET">
    <div class="genreSelect">
        <details class="accordion-004">
<!-- ゲームジャンル -->
            <summary>ゲーム</summary>
            <?php foreach ($gameGenre as $genre): ?>
                <label>
                    <input type="checkbox" name="genre[]" value="<?= $genre[0] ?>" >
                    <?= $genre[1]?>
                </label>
            <?php endforeach; ?>
        </details>
        <details class="accordion-004">
<!-- 音楽ジャンル -->
            <summary>音楽</summary>
            <?php foreach ($musicGenre as $genre): ?>
                <label>
                    <input type="checkbox" name="genre[]" value="<?= $genre[0] ?>" >
                    <?= $genre[1] ?>
                </label>
            <?php endforeach; ?>
        </details>
        <details class="accordion-004"> 
<!-- スポーツジャンル -->
            <summary>スポーツ</summary>
            <?php foreach ($sportsGenre as $genre): ?>
                <label>
                    <input type="checkbox" name="genre[]" value="<?= $genre[0] ?>" >
                    <?= $genre[1] ?>
                </label>
            <?php endforeach; ?>
            </details>
        <details class="accordion-004">
<!-- 勉強ジャンル -->
            <summary>勉強</summary>
            <?php foreach ($studyGenre as $genre): ?>
                <label>
                    <input type="checkbox" name="genre[]" value="<?= $genre[0] ?>" >
                    <?= $genre[1] ?>
                </label>
            <?php endforeach; ?>
        </details>
    </div>
    <button type="submit" id="Search">検索</button>
</form>

<!-- 検索ボタン 検索結果画面に遷移(search.html) -->
    </body>
</html>
