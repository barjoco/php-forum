const $chatPeopleList = Array.from($("chat_people_list").getElementsByTagName("li"));

$chatPeopleList.forEach($x => {
  $x.onclick = () => location.href = `?c=${$x.getAttribute("data-recipient")}`;
});

var host = 'ws://0.0.0.0:12345/websockets.php';
var socket = new WebSocket(host);
socket.onmessage = function (e) {
  console.log(e.data);
};