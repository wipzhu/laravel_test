<?php
/**
 * Created by PhpStorm.
 * User: wipzhu
 * Date: 2019/6/5
 * Time: 18:37
 */
?>

<html>
<h1>启动websocket服务（php artisan workerman），打开浏览器调试工具，console窗口查看输出日志</h1>

<textarea name="code" id="" cols="60" rows="15">
    var ws = new WebSocket("ws://127.0.0.1:8686");

    ws.onopen = function(evt) {  //绑定连接事件
    　　console.log("Connection open ...");
    　　ws.send("发送的数据");
    };

    ws.onmessage = function(evt) {//绑定收到消息事件
    　　console.log( "Received Message: " + evt.data);
    };

    ws.onclose = function(evt) { //绑定关闭或断开连接事件
    　　console.log("Connection closed.");
    };
</textarea>
<button name="doSomething" onclick="doSomething()">开始执行</button>

<script>
    function doSomething() {
        var ws = new WebSocket("ws://127.0.0.1:8686");

        ws.onopen = function(evt) {  //绑定连接事件
            console.log("Connection open ...");
            ws.send("发送的数据");
        };

        ws.onmessage = function(evt) {//绑定收到消息事件
            console.log( "Received Message: " + evt.data);
        };

        ws.onclose = function(evt) { //绑定关闭或断开连接事件
            console.log("Connection closed.");
        };
    }

</script>

</html>
