<?php
/**
 * Created by PhpStorm.
 * User: wipzhu
 * Date: 2019/6/5
 * Time: 18:37
 */
?>

<html>
<h1>启动websocket服务（php artisan WkWebsocket），打开浏览器调试工具，console窗口查看输出日志</h1>

<textarea name="code" id="" cols="60" rows="15">
    var ws = new WebSocket("ws://127.0.0.1:9898");

    ws.onopen = function(evt) {  //绑定连接事件
    　　console.log("Connection open ...");
            ws.send("SH601155");
    };

    ws.onmessage = function(evt) {//绑定收到消息事件
    　　console.log( "Received Message: " + evt.data);
    };

    ws.onclose = function(evt) { //绑定关闭或断开连接事件
    　　console.log("Connection closed.");
    };
    ws.onerror = function(evt) { // 绑定错误事件
        console.log(evt.error);
    };
</textarea>
<button name="doSomething" onclick="doWebsocket()">开始执行</button>
<button name="doClose" onclick="doClose()">停止执行</button>

<script>
    function doWebsocket() {
        var ws = new WebSocket("ws://127.0.0.1:9898");

        ws.onopen = function(evt) {  //绑定连接事件
            console.log("Connection open ...");
            // var data = [];
            // data['block'] = '股票\\大智慧自定义\\指数板块\\板块综合\\汽车制造';
            // ws.send('{"block":"股票\\\\大智慧自定义\\\\指数板块\\\\板块综合\\\\汽车制造"}');
            // var data = {block: '股票\\大智慧自定义\\指数板块\\板块综合\\汽车制造'}
            ws.send("?block=股票\\\\大智慧自定义\\\\指数板块\\\\板块综合\\\\汽车制造");
        };

        ws.onmessage = function(evt) {//绑定收到消息事件
            console.log( "Received Message: " + evt.data);
        };

        ws.onclose = function(evt) { //绑定关闭或断开连接事件
            console.log("Connection closed.");
        };

        ws.onerror = function(evt) { // 绑定错误事件
            console.log(evt.error);
        };
    }
    
    function doClose() {
        alert('test');
        
    }

</script>

</html>
