<?php
require_once './helpers/studentDAO.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ログイン中のユーザー情報を取得
$loggedInUser = null;

if (isset($_SESSION['userInfo']) ) {
    $loggedInUser = $_SESSION['userInfo'];
}else{
  header("Location: login.php");
  exit;
}

?>

<header>
    <a href="home.php"><img src="jecMatching/Jec.jpg" width="450px" alt="JEC Logo" id="JecIMG"></a>
    <link rel="stylesheet" href="CSSUser/Header.css">
        <!-- CSS適応 -->
    <link rel="stylesheet" href="CSSUser/Header.css">
    <link rel="stylesheet" href="CSSUser/GroupCreate.css">  


        <?php
        $studentDAO = new StudentDAO();

        $user = $studentDAO->get_newUserInfo($loggedInUser->UserID ?? '');
        ?>
        <input type="text" id="name" value="<?= htmlspecialchars($user[0]['UserName'], ENT_QUOTES, 'UTF-8') ?>" placeholder="ニックネームを決めよう！" readonly>


    <a href="edit.php"><input type="button" value="プロフィール編集" id="edit"></a>
    <a href="logoutTEST.php"><input type="button" value="ログアウト" id="logout"></a>
</header>
<hr>
