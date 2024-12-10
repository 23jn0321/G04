<?php 
require_once './helpers/userDAO.php';
require_once './helpers/GroupDAO.php';



 $GroupDAO = new GroupDAO();
  $group_list = $GroupDAO->get_group(); 
  $goodsDAO = new GoodsDAO();
  $goods_list= $goodsDAO->get_recommend_goods();
  if(isset($_GET['groupcode'])){
    $groupcode= $_GET['groupcode'];
    $goods_list = $goodsDAO->get_goods_by_groupcode($groupcode);
    }
    else{
        $goods_list= $goodsDAO->get_recommend_goods();
    }
  ?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

<?php include "header.php"; ?>
<table> 
    <?php foreach($group_list as $group) : ?> 
        <tr> 
            <td> 
                <a href="message.php?groupcode=<?= $group->groupcode ?>">
                <?= $goodsgroup->groupname ?> 
            </td> 
        </tr> 
        <?php endforeach ?> 
    </table>  
        <table> 
       
    </table>  
</body>
</html>
