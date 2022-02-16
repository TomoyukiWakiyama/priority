<?php 
/*==================================
      submit 'btn_logout' 実行
==================================*/
if(!empty($_POST['btn_logout'])){
  if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit();
  }
  //セッション変数をクリア
  $_SESSION = array();
  //クッキーに登録されているセッションidの情報を削除
  if (ini_get("session.use_cookies")) {
    setcookie(session_name(), '', time() - 42000, '/');
  }
  //セッションを破棄
  session_destroy();
}
?>