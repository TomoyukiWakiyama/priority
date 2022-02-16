<?php 

  // タイムゾーン設定
  date_default_timezone_set('Asia/Tokyo');

  // 変数の初期化
  $current_date = null;
  $message = array();
  $message_array = array();

  $success_message = null;
  $error_message = array();
  

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
      アップデート処理
  ==================================*/
  if(!empty($_GET['post_id'])){
    $stmt = $pdo -> prepare('SELECT * FROM todos WHERE id = :id');
    $stmt -> bindValue(':id', $_GET['post_id'], PDO::PARAM_INT );
    $stmt -> execute();

    $post_data = $stmt -> fetch();

    if( empty($post_data) ){
      header("Location: ./index.php");
      exit;
    } elseif($_GET['post_id'] === null) {
      header('Location: ./index.php');
    }

  }

$stmt = null;
$pdo = null;
  
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
<h1><a href="./index.php">編集フォーム</a></h1>
<p><?php $test ?></p>
<form action="" method="post">
  <dl>
    <div class="form-item">
      <dt><label for="language" class="icon_dt">言語</label></dt>
      <dd>
        <label class="label-radio"><input id="language" type="radio" name="language" value="HTML"
                <?php if($post_data['language'] === 'HTML') { echo 'checked'; } ?>>HTML</label>
        <label class="label-radio"><input id="language" type="radio" name="language" value="JS"
                <?php if($post_data['language'] === 'JS') { echo 'checked'; } ?>>JS</label>
        <label class="label-radio"><input id="language" type="radio" name="language" value="PHP"
                <?php if($post_data['language'] === 'PHP') { echo 'checked'; } ?>>PHP</label>
      </dd>
    </div>

    <div class="form-item">
      <dt><label for="title" class="icon_dt">気になっていることは？</label></dt>
      <dd><input type="text" id="title" name="title" value="<?php echo $post_data['title'] ?>"></dd>
            
    </div>

    <div class="form-item">
      <dt><label for="priority" class="icon_dt">優先順位</label></dt>
      <dd>
        <label class="label-radio"><input type="radio" id="priority" name="priority" value="3"
               <?php if($post_data['priority'] === '3') { echo 'checked'; } ?>>高</label>
        <label class="label-radio"><input type="radio" id="priority" name="priority" value="2"
               <?php if($post_data['priority'] === '2') { echo 'checked'; } ?>>中</label>
        <label class="label-radio"><input type="radio" id="priority" name="priority" value="1"
               <?php if($post_data['priority'] === '1') { echo 'checked'; } ?>>低</label>
      </dd>
    </div>

    <div class="form-item">
      <dt><label for="post" class="icon_dt">メタ認知</label></dt>
      <dd><textarea id="post" name="post"><?php echo $post_data['post'] ?></textarea></dd>
    </div>
  </dl>

  <div class="form-submit">
    <input type="submit" name="btn_submit" class="button_update" value="更新する">
    <input type="hidden" name="post_id" value="<?php echo $post_data['id']; ?>">
  </div>
  
</form>

</body>
</html>