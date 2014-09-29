$(function () {
  // 開いているページがChatページでなければ,returnする
  if($("title")[0].innerHTML.replace(/(\n)|( )/g, "") != "chat") { return; }
  
  // WebSocketのインスタンスを生成(WebSocketサーバーを立ち上げているIPアドレスを指定)
  ws = new WebSocket("ws://192.168.33.10:51234");

  // WebSocketサーバーからデータが送信された場合に呼ばれる
  ws.onmessage = function (event) {
    // WebSocketサーバーから届いたJSONデータをparseし,HTMLの文字列を生成(CSSクラスの適用)
    message = JSON.parse(event.data);
    messageLayoutClass = '<div class="row u-border-lightgray">';
    imageClass         = '<div class="col-xs-2 u-margin-top-5"> <img class="right" src="' + message["image_url"] + '" alt="' + message["screen_name"] + '" width="40" height="40"></img> </div>';
    messageClass       = '<div class="col-xs-10 u-padding-0"> <p class="u-text-color-gray">' + message["screen_name"] + '</p>' + '<p>' + message["body"] + '</p> </div> </div>';
    
    // chat_areaに追加
    $(".p-chat__area").append(messageLayoutClass + imageClass + messageClass);
    
    // chatの一番下にscrollする
    $(".p-chat__area").scrollTop($(".p-chat__area")[0].scrollHeight);
  };

  // sendボタンが押された時の処理
  $(".p-chat__submit-button").click(function () {
    // 必要なデータを取得
    screen_name = $(".p-chat__user-image").attr("alt");
    messageBody = $(".p-chat__text").val();
    if(messageBody == "") {
      return;
    }
    imageUrl    = $(".p-chat__user-image").attr("src");
    message = { screen_name: screen_name, body: messageBody, image_url: imageUrl };

    // WebSocketサーバーにJSONにしたデータを送信
    ws.send(JSON.stringify(message));

    // ajaxでCakePHPのChatsコントローラーのsendアクションを呼び出す
    $.ajax({
      type: "post",
      url: "/chats/send",
      data: "body="+ messageBody
    });
    
    // Text欄を空白にする
    $(".p-chat__text").val("");
  });
});
