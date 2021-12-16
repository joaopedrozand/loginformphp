<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '353534534533453f43rf34f34f3');
define('DB_NAME', 'site');
 
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
if($link === false){
    die("Erro: Não foi possível conectar. " . mysqli_connect_error());
}
?>