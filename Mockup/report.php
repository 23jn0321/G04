<?php
    // 必要なファイルの読み込み
    require_once 'helpers/studentDAO.php';  // 学生データアクセスオブジェクト
    require_once 'helpers/ReportDAO.php';  // 通報データアクセスオブジェクト
    include "header.php";  // ヘッダーファイルのインクルード

    // セッションの開始（まだセッションが開始されていない場合のみ）
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    // ログイン中のユーザー情報を初期化
    $loggedInUser = null;

    // ログイン中のユーザー情報がセッションにある場合、それを取得
    if (isset($_SESSION['userInfo']) ) {
        $loggedInUser = $_SESSION['userInfo'];
    }

    // 通報対象のユーザーIDとグループIDを取得
    if (isset($_GET['UserID'])) {
        $userID = $_GET['UserID'];
    }
    if (isset($_GET['GroupID'])) {
        $groupID = $_GET['GroupID'];
    }

    // 学生情報を取得
    $studentDAO = new StudentDAO();
    $user = $studentDAO->get_newUserInfo($userID);

    // 通報処理が完了したかどうかを判定するフラグ
    $isReported = false;

    // 通報フォームが送信された場合の処理
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $reportCategory = $_POST['reason']; // POSTデータから通報理由を取得
        $reportDAO = new ReportDAO();
        // 通報をデータベースに保存
        $reportDAO->reportUser($loggedInUser->UserID, $userID, $groupID, $reportCategory);
        $isReported = true; // 通報完了フラグを更新
    }
?>

<!DOCTYPE html>
<html>
<meta charset="utf-8">
<header>
    <!-- CSSファイルの適用 -->
    <link rel="stylesheet" href="CSSUser/Report.css">

    <!-- 通報対象者情報の表示 -->
    <p>通報対象のニックネーム：<input type="text" value="<?= $user[0]['UserName'] ?>" readonly><br></p>
    <p>該当する項目を選択してください</p>

    <!-- 通報フォーム -->
    <div style=" display: inline-block;padding: 10px; margin-bottom: 10px; border: 1px solid #333333;">
        <form action="" method="POST" id="myForm">
            <!-- ラジオボタンで通報理由を選択 -->
            <label for="radio1"><input type="radio" id="radio1" name="reason" value="スパムや迷惑行為">スパムや迷惑行為</label><br>
            <label for="radio2"><input type="radio" id="radio2" name="reason" value="ハラスメントや暴言">ハラスメントや暴言</label><br>
            <label for="radio3"><input type="radio" id="radio3" name="reason" value="不適切なコンテンツの共有">不適切なコンテンツの共有</label><br>
            <label for="radio4"><input type="radio" id="radio4" name="reason" value="なりすましやプライバシー侵害">なりすましやプライバシー侵害</label><br>
            <label for="radio5"><input type="radio" id="radio5" name="reason" value="不正行為や詐欺">不正行為や詐欺</label><br>
            <label for="radio6"><input type="radio" id="radio6" name="reason" value="不適切な勧誘や宣伝">不適切な勧誘や宣伝</label><br>
            <label for="radio7"><input type="radio" id="radio7" name="reason" value="その他 / 違反行為">その他 / 違反行為</label>
        </div><br>
        <!-- 通報ボタン -->
        <button type="submit" id="reportButton">通報</button>
    </form>

    <!-- 必要なJavaScriptファイルの読み込み -->
    <script src="./jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // 通報完了ポップアップを表示（PHPのフラグを利用）
            <?php if ($isReported): ?>
                Swal.fire({
                    title: '通報完了！', // ダイアログのタイトル
                    text: '通報が正常に送信されました。ご協力ありがとうございます！', // ダイアログの内容
                    icon: 'success', // 成功アイコン
                    confirmButtonText: 'OK' // 確認ボタンのテキスト
                }).then((result) => {
                    if (result.isConfirmed) {
                        // 確認ボタンが押されたらグループ画面にリダイレクト
                        window.location.href = `message.php?GroupID=<?= $groupID ?>`;
                    }
                });
            <?php endif; ?>

            // 通報確認ダイアログを表示
            $('#myForm').on('submit', function(e) {
                e.preventDefault(); // フォームのデフォルト送信処理を無効化

                // SweetAlert2で確認ダイアログを表示
                Swal.fire({
                    title: '通報確認', // ダイアログのタイトル
                    html: 'ユーザー"<?= $user[0]['UserName'] ?>"を通報しますか？', // ダイアログの内容
                    icon: 'question', // 質問アイコン
                    showCancelButton: true, // キャンセルボタンの表示
                    confirmButtonText: '通報', // 確認ボタンのテキスト
                    cancelButtonText: 'キャンセル', // キャンセルボタンのテキスト
                }).then((result) => {
                    if (result.isConfirmed) {
                        // 確認ボタンが押された場合、フォームを送信
                        this.submit();
                    }
                });
            });
        });
    </script>
</html>
