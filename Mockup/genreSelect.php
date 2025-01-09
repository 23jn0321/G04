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
$allSubGenres = $genreSelectDAO->getAllSubGenres();

// ジャンルを大ジャンルごとにまとめる
$groupedGenres = [];
foreach ($allSubGenres as $genre) {
    $mainGenreID = $genre['MainGenreID'];
    $mainGenreName = $genre['MainGenreName'];
    
    if (!isset($groupedGenres[$mainGenreID])) {
        $groupedGenres[$mainGenreID] = [
            'MainGenreName' => $mainGenreName,
            'SubGenres' => []
        ];
    }
    $groupedGenres[$mainGenreID]['SubGenres'][] = [
        'SubGenreID' => $genre['SubGenreID'],
        'SubGenreName' => $genre['SubGenreName']
    ];
}


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
        グループ名：<?= htmlspecialchars($var->GroupName) ?><br>
        所属人数：<?= htmlspecialchars($var->MemberInfo) ?><br>
        最終更新日：<?= htmlspecialchars($var->LastUpdated) ?><br>
        ジャンル：<?= htmlspecialchars($var->Genre) ?>
        </a>
      </li>
      <?php endforeach; ?>
      <?php endif; ?>
    </ul>
</nav>
<!-- グループ作成ボタン -->
    <button id="GroupSakusei" onclick="location.href='groupCreate.php'">グループ作成</button>



<form action="search.php" method="GET">
    <div class="genreSelect">
        <?php foreach ($groupedGenres as $mainGenre): ?>
            <details class="accordion-004">
                <summary><?= htmlspecialchars($mainGenre['MainGenreName']) ?></summary>
                <label>
                    <input type="checkbox" class="select-all" data-target="genre-<?= htmlspecialchars($mainGenre['MainGenreName']) ?>-checkboxes">
                    すべて選択
                </label>
                <?php foreach ($mainGenre['SubGenres'] as $subGenre): ?>
                    <label>
                        <input type="checkbox" name="genre[]" value="<?= htmlspecialchars($subGenre['SubGenreID']) ?>" class="genre-<?= htmlspecialchars($mainGenre['MainGenreName']) ?>-checkboxes">
                        <?= htmlspecialchars($subGenre['SubGenreName']) ?>
                    </label>
                <?php endforeach; ?>
            </details>
        <?php endforeach; ?>
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
