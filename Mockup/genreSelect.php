<?php 
    require_once './helpers/studentDAO.php';
    require_once './helpers/userDAO.php';


    if(!empty($_SESSION['userInfo'])){
        //セッション変数の会員情報を取得する
        $user = $_SESSION['userInfo'];
        $userDAO = new UserDAO();
        $joinUserGroup = $userDAO->imGroup($user->UserID);
    }

    
    //DBから商品グループを取得する

    $goodsDAO = new GoodsDAO();
    

    if(isset($_GET['groupcode'])){
        //選択されたグループの商品を取得する
        $groupcode = $_GET['groupcode'];
        $goods_list = $goodsDAO->get_goods_by_groupcode($groupcode);
    }
    else{
        //リーダーかどうか取得する
        $goods_list = $goodsDAO->get_recommend_goods();
    }  

?>

<!DOCTYPE html>
<html>
    <body>
        <meta charset="utf-8">
        <header>
<!-- CSS適応 -->
        <link rel="stylesheet" href="CSSUser/Header.css">
        <link rel="stylesheet" href="CSSUser/GenreSelect.css">
        <?php include "header.php"; ?>
 

  <div>
    <p id="group">所属グループ一覧</p>
    <p><a href="groupEdit.html"><input type="button" value="グループ編集" id="groupEdit"></a></p>
  </div>
<!-- 所属グループ -->
<?php foreach($joinUserGroup as $userGroup) : ?>
        <table align="left">
            <tr>
                <td>
                    <a href="groupDetailAfter.html?goodscode=<?=$goods->goodscode?>">
                    <?= $userGroup->groupName ?>
                    </a>
                </td>
            </tr>
            <tr>
                <td>
                    <?= number_format($goods->price) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?= $goods->recommend? "おすすめ" : " " ?>
                </td>
            </tr>
    </table>
    <?php endforeach; ?>
<!-- グループ作成ボタン -->
    <a href="groupCreate.php"><input type="submit" value="グループ作成" id="groupCreate"></a>  

<!-- ジャンル選択 -->
    <div class="genreSelect">
        <details class="accordion-004">
<!-- ゲームジャンル -->
            <summary>ゲーム</summary>
            <label for="checkbox1"><input type="checkbox" id="checkbox1">すべて選択</label>
            <label for="checkbox2"><input type="checkbox" id="checkbox2">FPS</label>
            <label for="checkbox3"><input type="checkbox" id="checkbox3">ソーシャルゲーム</label>
            <label for="checkbox4"><input type="checkbox" id="checkbox4">ボードゲーム</label>
            <label for="checkbox5"><input type="checkbox" id="checkbox5">RPG</label>
            <label for="checkbox6"><input type="checkbox" id="checkbox6">カードゲーム</label>   
        </details>
        <details class="accordion-004">
<!-- 音楽ジャンル -->
            <summary>音楽</summary>
            <label for="checkbox7"><input type="checkbox" id="checkbox7">すべて選択</label>
            <label for="checkbox8"><input type="checkbox" id="checkbox8">邦ロック</label>
            <label for="checkbox9"><input type="checkbox" id="checkbox9">J-POP</label>
            <label for="checkbox10"><input type="checkbox" id="checkbox10">K-POP</label>
            <label for="checkbox11"><input type="checkbox" id="checkbox11">ボーカロイド</label>
        </details>
        <details class="accordion-004"> 
<!-- スポーツジャンル -->
            <summary>スポーツ</summary>
            <label for="checkbox12"><input type="checkbox" id="checkbox12">すべて選択</label>
            <label for="checkbox13"><input type="checkbox" id="checkbox13">サッカー</label>
            <label for="checkbox14"><input type="checkbox" id="checkbox14">テニス</label>
            <label for="checkbox15"><input type="checkbox" id="checkbox15">バスケットボール</label>
            <label for="checkbox16"><input type="checkbox" id="checkbox16">野球</label>
            <label for="checkbox17"><input type="checkbox" id="checkbox17">バレーボール</label>
            <label for="checkbox18"><input type="checkbox" id="checkbox18">ラグビー</label>
            <label for="checkbox19"><input type="checkbox" id="checkbox19">卓球</label>
            <label for="checkbox20"><input type="checkbox" id="checkbox20">バトミントン</label>
        </details>
        <details class="accordion-004">
<!-- 音楽ジャンル -->
            <summary>勉強</summary>
            <label for="checkbox21"><input type="checkbox" id="checkbox21">すべて選択</label>
            <label for="checkbox22"><input type="checkbox" id="checkbox22">資格勉強</label>
            <label for="checkbox23"><input type="checkbox" id="checkbox23">テスト勉強</label>
            <label for="checkbox24"><input type="checkbox" id="checkbox24">プログラミング</label>
        </details>
    </div>

<!-- 検索ボタン 検索結果画面に遷移(search.html) -->
<a href="search.html"><input type="submit" value="検索" id="Search"></a>
    </body>
</html>
