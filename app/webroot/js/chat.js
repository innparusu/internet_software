$(function () {
  if($("title")[0].innerHTML.replace(/(\n)|( )/g, "") != "chat") { return; }
  ws = new WebSocket("ws://192.168.33.10:51234");

  ws.onmessage = function (event) {
    message = JSON.parse(event.data);
    messageLayoutClass = '<div class="row u-border-lightgray">';
    imageClass         = '<div class="col-xs-2 u-margin-top-5"> <img class="right" src="' + message["image_url"] + '" alt="' + message["screen_name"] + '" width="40" height="40"></img> </div>';
    messageClass       = '<div class="col-xs-10 u-padding-0"> <p class="u-text-color-gray">' + message["screen_name"] + '</p>' + '<p>' + message["body"] + '</p> </div> </div>';
    $(".p-chat__area").append(messageLayoutClass + imageClass + messageClass);
    $(".p-chat__area").scrollTop($(".p-chat__area")[0].scrollHeight);
  };

  $(".p-chat__submit-button").click(function () {
    screen_name = $(".p-chat__user-image").attr("alt");
    messageBody = $(".p-chat__text").val();
    if(messageBody == "") {
	return;
    }
    imageUrl    = $(".p-chat__user-image").attr("src");
    message = { screen_name: screen_name, body: messageBody, image_url: imageUrl };
    ws.send(JSON.stringify(message));
    $.ajax({
      type: "post",
      url: "/chats/send",
      data: "body="+ messageBody
    });
    $(".p-chat__text").val("");
  });
});
