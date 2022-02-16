<?php 

  // タイムゾーン設定
  date_default_timezone_set('Asia/Tokyo');

  // 変数の初期化
  $current_date = null;
  $message = array();
  $message_array = array();

  $success_message = null;
  $error_message = array();

  //セッションを使うことを宣言
  session_start();
  

    // DB接続
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', 'root');
    define('DB_NAME', 'todolist');
  
    // DB <=> SQL接続
    $pdo = null;
    $stmt = null;
    $res = null;
    $option = null;
  
    
  /*==================================
      DB接続
  ==================================*/
  try {
    $option = array(
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
    );
    // データベースに接続
    $pdo = new PDO('mysql:charset=UTF8;dbname='.DB_NAME.';host='.DB_HOST , DB_USER, DB_PASS, $option);
  } catch(PDOException $e){
    // 接続エラー時、エラー内容を取得格納する
    $error_message[] = $e -> getMessage();
  }

  /*==================================
        submit 'btn_login' 実行
        1. ログイン済みならトップページへ戻る
        2. 空白除去
        3. 入力フォームチェック
        4. メールアドレス&パスワードチェック
  ==================================*/

  //ログイン状態の場合ログイン後のページにリダイレクト
  if (isset($_SESSION["login"])) {
    session_regenerate_id(TRUE);
    header("Location: index.php");
    exit();
  }

  if(!empty($_POST['btn_login'])){
    // 空白除去
    $email = preg_replace( '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST['email']);
    $password = preg_replace( '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST['password']);
    // 入力フォームチェック
    if(empty($email)){
      $error_message[] = 'メールアドレスが入力されていません';
    }
    if(empty($password)){
      $error_message[] = 'パスワードが入力されていません';
    }
    
    if(empty($error_message)){
      // トランザクションの開始
      $pdo -> beginTransaction();
      try {
        $stmt = $pdo -> prepare('SELECT * FROM users WHERE email = :email');
        $stmt -> bindParam(':email', $email, PDO::PARAM_STR);
        $stmt -> execute();
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);

        // パスワードが一致していた場合
        if(!password_verify($password, $result['password'])){
          session_regenerate_id(TRUE); //セッションidを再発行
          $_SESSION["login"] = true; // セッションを張る
          $_SESSION['user_id'] = $result['id'];
          $_SESSION['success_message'] = $result['name'] . 'さん ようこそ！';
          // トップページへ戻る(可能ならユーザがその前に訪れたページへ飛ばしたい)
          header("Location: index.php"); //ログイン後のページにリダイレクト
          exit();

        } else {
          $error_message[] = '入力された内容に誤りがあります';  
        }
      } catch(PDOException $e) {
        $pdo -> rollBack();
        $error_message[] = $e -> getMessage();
      }
    }

  } else {
    
  }


$pdo = null;
// 参考サイト：https://yama-itech.net/php-login-with-session
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="./css/style.css?<?php echo date('Ymd-His'); ?>">
</head>
<body>
<div id="app">
  <h1><a href="./index.php">ログインフォーム</a></h1>

  <?php if( !empty($error_message) ): ?>
    <ul class="error_message">
      <?php foreach( $error_message as $value ): ?>
        <li>・<?php echo $value; ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <form action="" method="post">
    <dl>
      <div class="form-item">
        <dt><label for="email">メールアドレス</label></dt>
        <dd><input type="email" id="email" name="email" value=""></dd>
      </div>

      <div class="form-item">
        <dt><label for="password">パスワード</label></dt>
        <dd><input type="password" id="password" name="password" value=""></dd>
      </div>
    </dl>

    <div class="form-submit">
      <input type="submit" name="btn_login" class="button_update" value="ログインする">
    </div>
  </form>

</div>

<script src="https://unpkg.com/vue@next"></script>
<script src="./js/vue.js"></script>
</body>
</html>