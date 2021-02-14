<?php

// 接受要删除的 id 号
function Deletedate () {
	if(empty($_GET['id'])) {
		exit ('<h1>必须传入指定的参数</h1>');
	}
	// $id 为局部变量，全局访问不到，必须申明为全局变量
	global $id;
	$id = $_GET['id'];
}
if($_SERVER['REQUEST_METHOD'] === 'GET')
{
	Deletedate ();

}
$connection = mysqli_connect('localhost','root','0000','Case05');

if(!$connection) {
	exit ('<h1>数据库连接失败</h1>');
}

$query = mysqli_query($connection,'delete from alluser where id in (' . $id . ');');

if (!$query) {
	exit ('<h1>数据查询失败</h1>');
}

$affected = mysqli_affected_rows($connection);
if($affected <= 0) {
	exit ('<h1>删除失败</h1>');
}
header('Location: index.php');