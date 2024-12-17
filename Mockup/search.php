<?php
require_once 'helpers/groupDAO.php';
require_once 'helpers/GenreSelectDAO.php';
   
include "header.php"; 

$genreSelectDAO = new GenreSelectDAO();

$loggedInUser = null;

if (isset($_SESSION['userInfo']) ) {
    //$userInfo = $_SESSION['userInfo'];

    $loggedInUser = $_SESSION['userInfo'];
}

// 選択されたジャンルIDの配列を取得
$selectedGenres = isset($_GET['genre']) ? $_GET['genre'] : [];
$groups = [];

if (!empty($selectedGenres)) {
    $groups = $genreSelectDAO->getGroupsByGenres($selectedGenres);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>検索結果</title>
</head>
<body>
    <h1>検索結果</h1>
    <?php if (!empty($groups)): ?>
        <ul>
            <?php foreach ($groups as $group): ?>
                <li class="group-item">
                    <div class="group-title">
                        <a href="groupDetail.php?GroupID=<?= htmlspecialchars($group->GroupID) ?>">
                            <?= htmlspecialchars($group->GroupName) ?>
                        </a>
                        (<?= htmlspecialchars($group->MemberCount) ?>/<?= htmlspecialchars($group->MaxMember) ?>)
                    </div>
                    <div class="group-details">
                        最終更新日: <?= htmlspecialchars($group->LastChatTime ?: '更新履歴なし') ?><br>
                        ジャンル: <?= htmlspecialchars($group->MainGenre) ?> / <?= htmlspecialchars($group->SubGenre) ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>該当するグループはありません。</p>
    <?php endif; ?>
</body>
</html>
