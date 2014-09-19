$(function () {
  if($('title')[0].innerHTML.replace(/(\n)|( )/g, "") != "chat") { return; }
  ws = new WebSocket("ws://192.168.33.10:51234");

  ws.onmessage = function (event) {
    $(".chat-area").append("<p>" + event.data + "</p>");
    $(".p-chat__text").val("");
  };

  $(".p-chat__submit-button").click(function () {
    message = $(".p-chat__text").val();
    ws.send(message);
  });
});

