<?php
require_once 'helpers/StudentDAO.php';

$studentDAO = new StudentDAO();
$students = $studentDAO->get_freezeUser();  // 複数の凍結ユーザー情報を取得

//var_dump($students);
if (isset($_GET['userId'])) {
    $userId = $_GET['userId'];
    $studentDAO->unfreezeUser($userId);  // 凍結解除
    header('Location: freezeUser.php');  // ページをリロードしてGETリクエストを送信
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>凍結者確認画面</title>
    <link rel="stylesheet" href="CSSAdmin/freezeUser.css">
    <!-- SweetAlertのCDNを読み込む -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <header>
        <a href="admin.html">
            <img src="jecMatching/Jec.jpg" width="450px" alt="Jec Logo">
        </a>
        <hr>
    </header>

    <script>
        // 凍結ユーザー情報をテキストボックスに表示するために、PHPで配列から情報を取り出してJavaScriptに渡す
        document.addEventListener('DOMContentLoaded', function() {
            var students = <?php echo json_encode($students); ?>; // PHP配列をJavaScriptに渡す

            // 凍結者が存在する場合にテキストボックスとボタンを表示
            if (students.length > 0) {
                for (let i = 0; i < students.length; i++) {
                    let freezeUserName = students[i].UserName || '不明';
                    let freezeReason = students[i].FreezeReason || '理由なし';

                    let freezeUserInfo = document.getElementById('freezeUserInfo' + (i + 1));
                    freezeUserInfo.value = `凍結ユーザー: ${freezeUserName} 凍結理由: ${freezeReason}`;
                    document.getElementById('freezeUserInfo' + (i + 1)).style.display = 'inline'; // テキストボックスを表示
                    document.getElementById('unfreezeButton' + (i + 1)).style.display = 'inline'; // ボタンを表示
                    // ボタンにuserIdをデータ属性として設定
                    document.getElementById('unfreezeButton' + (i + 1)).setAttribute('data-userid', students[i].UserID);
                }
            } else {
                // 凍結者がいない場合、メッセージを表示
                document.getElementById('noFreezeUsersMessage').style.display = 'block';
            }
        });

    // 凍結解除ボタンがクリックされた時に呼ばれる関数
    function confirmUnfreeze(userIndex) {
    const userId = document.getElementById('unfreezeButton' + userIndex).getAttribute('data-userid');
    const tmpUserName = document.getElementById('freezeUserInfo' + userIndex).value;
    
    // ユーザーネームを取り出す（"凍結ユーザー:"の後の部分を抽出）
    const userName = tmpUserName.split(' ')[1];  // 例: "凍結ユーザー: ゆきもん" から "ゆきもん" を取り出す

    Swal.fire({
        html: `本当にユーザーネーム: <strong>${userName}</strong> ユーザーID: <strong>${userId}</strong><br> を凍結解除しますか？`,
        showCancelButton: true,
        confirmButtonText: 'OK',
        reverseButtons: true,
        icon: 'info'
    }).then((result) => {
        if (result.isConfirmed) {
            // URLのクエリパラメータにuserIdを追加して同じページにGETリクエストを送信
            window.location.href = `?userId=${userId}`;  // ページをリロードしてGETリクエストを送信
        } else {
            // キャンセルボタンが押された時
            Swal.fire("凍結解除がキャンセルされました。", {
                icon: "info",
            });
        }
    });
}


    </script>

    <!-- 凍結者がいない場合のメッセージ -->
    <div id="noFreezeUsersMessage" style="display:none; color: red; text-align: center;">
        現在、凍結されているユーザーはありません。
    </div>

    <table>
        <!-- テキストボックスのidをユニークに設定 -->
        <?php for ($i = 1; $i <= 10; $i++): ?>
            <tr>
                <td><input type="text" id="freezeUserInfo<?php echo $i; ?>" name="freezeUserInfo<?php echo $i; ?>" disabled style="display: none;"></td>
                <td><button id="unfreezeButton<?php echo $i; ?>" onclick="confirmUnfreeze(<?php echo $i; ?>)" style="display: none;">凍結解除</button></td>
            </tr>
        <?php endfor; ?>
    </table>

</body>

</html>