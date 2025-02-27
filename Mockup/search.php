<?php
require_once 'helpers/GroupDAO.php';
require_once 'helpers/GenreSelectDAO.php';

include "header.php";

$genreSelectDAO = new GenreSelectDAO();

$loggedInUser = $_SESSION['userInfo'] ?? null;


// 選択されたジャンルIDの配列を取得
$selectedGenres = $_GET['genre'] ?? [];
if (!is_array($selectedGenres)) {
    $selectedGenres = [$selectedGenres];
}
$selectedGenres = array_map('intval', $selectedGenres);
// Debugging: Log the received data
error_log("Received genres: " . print_r($selectedGenres, true));

if (!empty($selectedGenres)) {  
    $groups = $genreSelectDAO->getGroupsByGenres($selectedGenres, $loggedInUser->UserID);
    $genres = $genreSelectDAO->get_Select_Genres($selectedGenres);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="CSSUser/Search.css">
    <title>検索結果</title>
</head>
<body>

<?php if(!empty($genres)) : ?>
    <h2>選択した中ジャンル</h2>
    <?php foreach ($genres as $genre): ?>
        <a id="Genres"><?= htmlspecialchars($genre['SubGenreName']) ?>　</a>
    <?php endforeach; ?>
<?php else: ?>
    <p>ジャンルが選ばれていません。</p>
<?php endif; ?>
    <h1>検索結果</h1>
    <nav class="group">
    <?php if (!empty($groups)): ?>
    <ul>
        <?php foreach ($groups as $group): ?>
            <li>
                <div>
                    <a href="groupDetail.php?GroupID=<?= htmlspecialchars($group['GroupID']) ?>">
                    グループ名：<?= htmlspecialchars($group['GroupName']) ?><br>
                    所属人数 ：<?= htmlspecialchars($group['MemberInfo']) ?><br>
                    最終更新日：<?= htmlspecialchars($group['LastUpdatedTime']) ?><br>
                    ジャンル：<?= htmlspecialchars($group['Genre']) ?>
                    </div>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <ul>
        <liH>
            グループがありません<br>
            ジャンルを変更しましょう。
        </liH>
    </ul>
<?php endif; ?>
    </nav>
    <button onclick="location.href='GenreSelect.php'" id="returnGenreSelect">ジャンルを変更</button>
</body>
</html>
