<?php
header("Content-Type: text/html; charset=utf8");

// if(!isset($_POST['submit'])||!isset($_POST['submit1'])){
//     exit("错误执行");
// }//判断是否有submit操作
if (isset($_POST['submit'])) {
    retrpwd();
} else {
    exit("错误执行");
}

function retrpwd()
{
    $name = $_POST['name']; //post获取表单里的name
    // $token = md5($name . time());
    $email = trim($_POST['email']);

    include 'connect.php'; //链接数据库
    include 'sendMail.php'; //发送激活邮件
    mysqli_query($con, "set names utf8"); //utf8 设为对应的编码
    $sql = "select * from user where username = '$name' and email='$email'"; //检测数据库是否有对应的username和password的sql
    $result = $con->query($sql);

    $rows = mysqli_fetch_array($result); //

    if (!$result) {
        echo "用户名或邮箱输入错误！";
    } else {
        echo "请点击邮箱链接重置密码！"; //成功输出注册成功
        echo "3秒后将跳转登录界面！";
        $nowtime = time();
        $sql = "update user set respwd='1',restime='$nowtime' where username='$name'";
        $con->query($sql);
        $token = $rows['token'];
        // postmail('15658050107@163.com', '密码找回', $token, $name, 'retrpwd');
        postmail($email, '密码找回', $token, $name, 'retrpwd');
        header("refresh:3;url=login.html"); //如果成功跳转至登陆页面
        exit;
    }

    mysqli_close($con); //关闭数据库
}
