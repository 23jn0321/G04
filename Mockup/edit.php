<?php
    // 必要なDAO（データアクセスオブジェクト）ファイルを読み込み
    require_once 'helpers/userDAO.php';
    require_once 'helpers/studentDAO.php';

    // セッションの開始（まだ開始されていない場合のみ開始）
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    // ユーザーがログイン中か確認
    if(!empty($_SESSION['userInfo'])){
        // ログイン中の場合、セッション変数からユーザー情報を取得
        $user = $_SESSION['userInfo'];
    }

    // 初期値の設定
    $nickName = ''; // 入力されたニックネーム
    $comment = ''; // 入力されたひとことコメント
    $errs = []; // 入力エラーを格納する配列

    // フォームがPOSTメソッドで送信された場合の処理
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        // フォームから送信されたニックネームとコメントを取得
        $nickName = $_POST['nickName'];
        $comment = $_POST['comment'];

        // DAOインスタンスの生成
        $userDAO = new userDAO();

        // 入力値を使ってユーザー情報を更新
        $editInfo = $userDAO->update($nickName, $comment, $user->UserID);

        // 入力チェック（ニックネームが空の場合、エラーメッセージを追加）
        if($nickName === ''){
            $errs[] = 'ニックネームを入力してください。';
        }

        // 入力チェック（ひとことコメントが空の場合、エラーメッセージを追加）
        if($comment === ''){
            $errs[] = 'ひとことコメントを入力してください';
        }

        // エラーがない場合、データベースの更新処理を実行
        if(empty($errs)){
            // 再度ユーザー情報を更新
            $user = $userDAO->update($nickName, $comment, $user->UserID);

            // データ更新後、ページ遷移の処理
            if($student !== false){
                // セッションIDを変更し、ユーザーのセキュリティを強化
                // プロフィール編集後、home.phpにリダイレクト
                header('Location: home.php');
                exit;
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<meta charset="utf-8">
<header>
  <!-- 外部CSSファイルを適用 -->
  <link rel="stylesheet" href="CSSUser/Header.css">
</header>
<?php include "header.php"; ?> <!-- ヘッダー部分を別ファイルからインクルード -->

<?php
    // セッションからログインユーザー情報を取得
    $user = $_SESSION['userInfo'];

    // StudentDAOを使ってログインユーザーの情報を取得
    $studentDAO = new StudentDAO();
    $userName = $studentDAO->get_newUserInfo($user->UserID);
?>

<!-- プロフィール編集フォーム -->
<form id="myForm" action="" method="POST">
    <table id="profileTable" class="box">
        <tr>
            <th colspan="2">
                プロフィール編集 <!-- フォームのタイトル -->
            </th>
        </tr>
        <tr>
            <td>ニックネーム</td>
            <td>
                <!-- 現在のニックネームを初期値として表示 -->
                <input type="text" required name="nickName" class="input" value="<?= $userName->UserName ?>" autofocus>
            </td>
        </tr>
        <tr>
            <td>ひとことコメント</td>
            <td>
                <!-- 現在のコメントを初期値として表示 -->
                <input type="text" required name="comment" class="input" value="<?= $userName->ProfileComment ?>" autofocus>
            </td>
        </tr>
        <tr>
            <td>
                <!-- フォームの送信ボタン -->
                <button type="submit" id="submitButton">送信</button>
            </td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">
                <!-- エラーメッセージを表示（エラーがある場合のみ） -->
                <?php foreach($errs as $e) : ?>
                    <span style="color:red"><?= $e ?></span>
                    <br>
                <?php endforeach; ?>
            </td>
        </tr>
    </table>
</form>

<script src="./jquery-3.6.0.min.js"></script> <!-- jQueryライブラリを読み込み -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2ライブラリを読み込み -->

<script>
$(document).ready(function() {
    // フォームの送信イベントをカスタマイズ
    $('#myForm').on('submit', function(e) {
        e.preventDefault(); // デフォルトの送信処理を防ぐ

        // SweetAlert2を使って確認ダイアログを表示
        Swal.fire({
            title: '編集確認', // ダイアログのタイトル
            text: '編集を確定しますか？', // ダイアログの内容
            icon: 'question', // アイコン（質問マーク）
            showCancelButton: true, // キャンセルボタンを表示
            confirmButtonText: '確定', // 確定ボタンのテキスト
            cancelButtonText: 'キャンセル', // キャンセルボタンのテキスト
        }).then((result) => {
            if (result.isConfirmed) {
                // 確定ボタンが押された場合、フォームを送信
                this.submit();
            }
        });
    });
});
</script>

