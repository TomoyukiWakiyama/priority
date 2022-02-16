$(function() {
  
  // 投稿が成功した動作
  $('.success_message').fadeOut(1300);

  // 削除時の確認メッセージ
  $('.button_delete').click(function(){
    if(confirm('投稿を削除しますがよろしいですか')){
      return true;
    } else {
      return false;
    }
  });
  
});