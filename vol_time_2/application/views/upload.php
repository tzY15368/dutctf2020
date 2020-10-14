<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>志愿时长查询</title>
    <link rel="shortcut icon" href="/vol_time/static/favicon.ico" />
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <script src="https://static.runoob.com/assets/jquery-validation-1.14.0/lib/jquery.js"></script>
    <script src="https://static.runoob.com/assets/jquery-validation-1.14.0/dist/jquery.validate.min.js"></script>
    <style>
        .title{
            margin:40px 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .error{
            font-weight: normal;
            color: rgba(255,0,0,.8);
        }
        #myform{
            padding-bottom:20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">   
        <span style="font-size:28px">
            志愿时长查询增量更新
        </span>
        <span style="float:right;margin-top:10px;">
            <a href="/vol_time">查询页面</a>
        </span>
        </div>
        <h2>flag:<br>dutctf{donot_neglect_sql_injections}</h2>
        <a href="/static/example.csv" style="font-size:20px">样表下载</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong style="color:orange;font-size:20px;">请上传csv格式的文件&nbsp;&nbsp;&nbsp;&nbsp;<a  href="#" onclick="toggleHowTo()">怎么转换</a></strong>
        <p id="howto" style="display:none">
            <img src="/static/vol_time/howto1.png" height="300px" width="300px"/>
            <img src="/static/vol_time/howto2.png" height="300px" width="600px"/>
        </p>
        <script>
            toggleHowTo = ()=>{
                $("#howto").toggle()
            }
        </script>
        <hr>
            <form action="/vol_time/index.php/Vol/upload" method="post" enctype="multipart/form-data" accept-charset="utf-8" class="form-horizontal">
                <input class="btn btn-default" type="file" name="data" accept=".csv" required/>
                <span>

                    <span><input class="" type="checkbox" aria-label="..." name="coverall"></span>
                    <span style="color:red">全表覆盖</span>
                </span>
                <br>
                <input class="btn btn-primary" style="margin-top:10px;"type="submit" value="提交" />
            </form>
            <hr>
            <div>
                <p style="font-size:20px">
                    <?php echo $msg;?>