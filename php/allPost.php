<?php 
  // メッセージ一覧の取得
  if( !empty($pdo) ):
    $stmt = $pdo -> prepare('SELECT * FROM todos ORDER BY created DESC');
    $stmt -> execute();
    $message_array = $stmt->fetchAll();
  endif;
?>