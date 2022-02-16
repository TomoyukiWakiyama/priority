<?php 
/*==================================
      submit 'btn_submit' 実行
==================================*/
if(!empty($_POST['btn_submit'])){

  // filter_input

  // 空白除去
  $title = preg_replace( '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST['title']);
  $post = preg_replace( '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST['post']);

  if(empty($title)){
    $error_message[] = '気になっていることが入力されていません';
  }
  if(empty($post)){
    $error_message[] = 'メタ認知が入力されていません';
  } 

  // 空白チェック
  if( empty($error_message) ) {
    // トランザクションの開始
    $pdo -> beginTransaction();
    try{
      
      $stmt = $pdo -> prepare('INSERT INTO todos (title, post,language, priority) VALUES(:title, :post, :language, :priority)');

      $stmt -> bindParam(':title', $title, PDO::PARAM_STR);
      $stmt -> bindParam(':post', $post, PDO::PARAM_STR);
      $stmt -> bindParam(':language', $_POST['language'], PDO::PARAM_STR);
      $stmt -> bindParam(':priority', $_POST['priority'], PDO::PARAM_INT);
    
      $res = $stmt -> execute();
      $res = $pdo -> commit();
    } catch(PDOException $e){
      $pdo -> rollBack();
    } // トランザクションの終了
  }

  if($res){
    $_SESSION['success_message'] = 'メッセージを書き込みました。';
    $stmt = null;
    header('Location: ./');
  exit;
  } else {
    $error_message[] = 'メッセージの投稿に失敗しました';
    $stmt = null;
  }

  
}
?>