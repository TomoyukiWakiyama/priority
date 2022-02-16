<?php 
  $message= 'message';
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
  <p ref="text" @click="addItem"><?php echo $message ?></p>
  
  
</div>

<script src="https://unpkg.com/vue@next"></script>
<script src="./js/test.js"></script>
</body>
</html>