<?php
require_once 'DAO.php';

class message{
public int $MessageID;
public int $GroupID;
public int $SendUserID;
public string $MessageDetail;
public DateTime $SendTime;
public int $ChatDeleteFlag;
}

class messageDAO{
    
}
?>