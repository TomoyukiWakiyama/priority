<?php 


if(!empty($_POST['btn_delete'])){
  if(empty($_POST['post_id'])){
    $error_message[] = '投稿IDの取得に失敗しました';
  }

  if(empty($error_message)){
    // 削除の処理を書く
    $pdo -> beginTransaction();
    try {
      $stmt = $pdo -> prepare("DELETE FROM todos WHERE id = :id");
      $stmt -> bindValue(':id', $_POST['post_id'], PDO::PARAM_INT);
      $stmt -> execute();
      $res = $pdo -> commit();
    } catch(PDOException $e) {
      $pdo -> rollBack();
    }
    // 削除に成功したら一覧に戻る
    if( $res ) {
      $_SESSION['success_message'] = '投稿を削除しました';
    } else {
      $error_message[] = '投稿の削除に失敗しました';
    }
    
  }

  $stmt = null;
  header('Location: ./');
  exit;
}
?>