<?php
/**
 * Created by PhpStorm.
 * User: wipzhu
 * Date: 2019/6/6
 * Time: 9:53
 */
?>
<?php
/**
 * Created by PhpStorm.
 * User: wipzhu
 * Date: 2019/6/5
 * Time: 18:37
 */
?>

<html>
<h1>启动http服务（php artisan WkHttp），打开浏览器调试工具，console窗口查看输出日志</h1>

{{--<textarea name="code" id="" cols="60" rows="15">--}}
{{--    var ws = new Http("http://0.0.0.0:8687");--}}

{{--    ws.onopen = function(evt) {  //绑定连接事件--}}
{{--    　　console.log("Connection open ...");--}}
{{--    　　ws.send("发送的数据");--}}
{{--    };--}}

{{--    ws.onmessage = function(evt) {//绑定收到消息事件--}}
{{--    　　console.log( "Received Message: " + evt.data);--}}
{{--    };--}}

{{--    ws.onclose = function(evt) { //绑定关闭或断开连接事件--}}
{{--    　　console.log("Connection closed.");--}}
{{--    };--}}

{{--    ws.onerror = function(evt) { // 绑定错误事件--}}
{{--        console.log(evt.error);--}}
{{--    };--}}
{{--</textarea>--}}
{{--<button name="doSomething" onclick="longPolling()">开始执行</button>--}}
{{--<div id="state" style="border:1px red solid"></div>--}}
<form id="form1" runat="server">
    <div>
        <input id="Button1" type="button" value="AjaxLongPoll"/>
        <label id="ajaxMessage"></label>
    </div>
</form>
<script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        var url = "http://127.0.0.1:8687";
        $("#Button1").bind("click", {btn: $("#Button1")}, function (evdata) {
            $.ajax({
                type: "POST",
                url: url,
                dataType: "text",
                timeout: 10000,
                data: {ajax: "1", time: "10000"},
                success: function (data, textStatus) {
                    //alert("ok!");
                    evdata.data.btn.click();
                },
                complete: function (XMLHttpRequest, textStatus) {
                    if (XMLHttpRequest.readyState == "4") {
                        console.log(XMLHttpRequest.responseText);
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    //$("#ajaxMessage").text($(this).text()+" out!")
                    console.log("error:" + textStatus);
                    if (textStatus == "timeout") {
                        evdata.data.btn.click();
                    }
                }
            });
        });

        /*$("#ajaxMessage").ajaxStart(function(){
            $(this).text("准备建立请求.readyState0:");
        });
        $("#ajaxMessage").ajaxSend(function(evt, request, settings){
            $(this).text("开始请求,准备发送数据.readyState1:"+request.readyState);
        });
        $("#ajaxMessage").ajaxComplete(function(event,request, settings){
            if(request.status==200)
                $(this).text("请求完成.readyState4:"+request.readyState);
        });
        $("#ajaxMessage").ajaxStop(function(){
            $(this).text("请求结束.");
        });*/
    });


    // function doHttp() {
    //     var url = "http://127.0.0.1:8687";
    //     $.post({
    //         url:url,
    //     });
    //
    // }
    function longPolling() {
        var url = "http://127.0.0.1:8687";
        $.ajax({
            url: url,
            data: {"timed": new Date().getTime()},
            dataType: "text",
            timeout: 5000,
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $("#state").append("[state: " + textStatus + ", error: " + errorThrown + " ]<br/>");
                if (textStatus == "timeout") { // 请求超时
                    longPolling(); // 递归调用
                    // 其他错误，如网络错误等
                } else {
                    longPolling();
                }
            },
            success: function (data, textStatus) {
                console.log(data);
                console.log(textStatus);
                $("#state").append("[state: " + textStatus + ", data: { " + data + "} ]<br/>");
                if (textStatus == "success") { // 请求成功
                    longPolling();
                }
            }
        });
    }

</script>

</html>
