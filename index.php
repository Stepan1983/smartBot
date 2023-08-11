<?php

require_once 'headers.php';

// получаем слово из запроса
if(isset($_GET['word'])){
  $word = $_GET['word'];

  require_once "bot.php";

// открываем файл для записи (если его нет, то создаем)
/*file_put_contents("words.txt", substr($text_response, 0, 1000));*/


  

}


?>





