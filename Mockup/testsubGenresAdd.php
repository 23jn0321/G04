<?php
require_once 'helpers/GenreDAO.php';

// データの取得
$genreDAO = new GenreDAO();
$genres = $genreDAO->get_Genre();

$subGenreDAO = new subGenreDAO();
$subGenres = $subGenreDAO->get_subGenre();

$genreWithSubGenres = [];

// メインジャンルとサブジャンルを関連付け
foreach ($genres as $genre) {
    $subGenreNames = [];
    foreach ($subGenres as $subGenre) {
        if ($subGenre->mainGenreID == $genre->mainGenreID && !$subGenre->deleteFlag) {
            $subGenreNames[] = $subGenre->subGenreName;
        }
    }
    $genreWithSubGenres[] = [
        'mainGenreName' => $genre->mainGenreName,
        'subGenreNames' => $subGenreNames
    ];
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>サブジャンル追加</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSSAdmin/subGenresAdd.css">
</head>

<body>
    <header>
        <a href="admin.html">
            <img src="jecMatching/JecMatchingAdmin.jpg" width="450px" alt="Jec Logo">
        </a>
        <hr>
    </header>

    <div class="dropdown-container">
        <label for="genreName">大ジャンル名</label>
        <form action="process.php" method="post">
            <!-- メインジャンルの選択 -->
            <select id="genreName" name="genreName" onchange="this.form.submit()">
                <?php foreach ($genreWithSubGenres as $item): ?>
                    <option value="<?php echo htmlspecialchars($item['mainGenreName'], ENT_QUOTES, 'UTF-8'); ?>"
                        <?php echo isset($_POST['genreName']) && $_POST['genreName'] === $item['mainGenreName'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($item['mainGenreName'], ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <div id="subGenreContainer">
        <form action="process.php" method="post">
            <!-- 中ジャンルの表示 -->
            <label for="subGenreName">中ジャンル名</label>

            <?php
            // 選択されたジャンルに応じたサブジャンルを表示
            $selectedGenre = $_POST['genreName'] ?? $genreWithSubGenres[0]['mainGenreName'];
            foreach ($genreWithSubGenres as $item) {
                if ($item['mainGenreName'] === $selectedGenre) {
                    $subGenres = $item['subGenreNames'];
                    break;
                }
            }

            // サブジャンルの入力フィールドを生成
            $inputCount = 0;
            foreach ($subGenres as $subGenre) {
                $inputCount++;
                if ($inputCount > 4) break; // 最初の4つだけ表示
                ?>
                <input type="text" id="subGenreName<?php echo $inputCount; ?>" name="subGenreNames[]" 
                    value="<?php echo htmlspecialchars($subGenre, ENT_QUOTES, 'UTF-8'); ?>" readonly>
                <?php
            }

            // 空白の入力フィールドを追加
            for ($i = $inputCount + 1; $i <= 4; $i++): ?>
                <input type="text" id="subGenreName<?php echo $i; ?>" name="subGenreNames[]" placeholder="サブジャンルを追加">
            <?php endfor; ?>

            <button type="submit">ジャンルを追加する</button>
        </form>
    </div>

</body>

</html>
