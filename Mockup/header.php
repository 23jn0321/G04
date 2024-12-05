<?php
require_once './helpers/studentDAO.php';
require_once './helpers/userDAO.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ログイン中のユーザー情報を取得
$loggedInUser = null;
if (isset($_SESSION['userInfo']) && $_SESSION['userInfo'] instanceof User) {
    $loggedInUser = $_SESSION['userInfo'];
}



?>

<header>
    <a href="home.php"><img src="jecMatching/Jec.jpg" width="450px" alt="JEC Logo"></a>
    <link rel="stylesheet" href="CSSUser/Header.css">

    <?php if ($loggedInUser): ?>
        <?php
        $studentDAO = new StudentDAO();
        $userName = $studentDAO->get_newUserInfo($loggedInUser->UserID ?? '');
        if (!$userName) {
            $userName = (object) ['UserName' => '不明なユーザー'];
        }
        ?>
        <input type="text" id="name" value="<?= htmlspecialchars($userName->UserName, ENT_QUOTES, 'UTF-8') ?>" readonly>
    <?php else: ?>
        <input type="text" id="name" value="ゲスト" readonly>
    <?php endif; ?>

    <a href="edit.php"><input type="button" value="編集" id="edit"></a>
    <a href="admin.html"><input type="button" value="管理者画面" id="admin"></a>
    <a href="login.php"><input type="button" value="ログイン画面遷移" id="admin"></a>
    <a href="logoutTEST.php">ログアウト</a>
    <hr>
</header>
