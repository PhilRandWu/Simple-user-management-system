
<?php 
	
	if(empty($_GET['id'])) {
		exit('<h1>必须传入编辑的参数</h1>');
	}
	$id = $_GET['id'];
	
		
	$connection = mysqli_connect('localhost','root','0000','case05');
	if(!$connection) {
		$GLOBALS['error_message'] = '数据库连接失败';
		return;
	} 
	// var_dump("insert into alluser values(null,'{$name}', {$gender}, '{$birthday}', '{$avatar}');");
	// var_dump("select * from alluser where id = {$id};");

	// 因为ID是唯一的，那么找到第一个满足条件的就不用再继续查询了
	$query = mysqli_query($connection,"select * from alluser where id = $id limit 1;");

	if(!$query) {
		$GLOBALS['error_message'] = '数据查询失败';
		return;
	}

	$user = mysqli_fetch_assoc($query);
	if(!$user) {
		exit('<h1>查找不到要编辑的数据</h1>');
	}

	// var_dump($user);

	$rows = mysqli_affected_rows($connection);
	if($rows != 1) {
		$GLOBALS['error_message'] = '添加失败';
		return;
	}

	// 响应
	// header('Location: index.php'); 
	mysqli_free_result($query);
	mysqli_close($connection);
	
	function POSTBACK () {
		global $user;
		// 姓名
		if(empty($_POST['name'])) {
			$GLOBALS['error_message'] = '请输入姓名';
			return;
		}

		// 性别
		if (!(isset($_POST['gender']) && $_POST['gender'] !== '-1')) {
    		$GLOBALS['error_message'] = '请选择性别';
    		return;
  		}
		// 生日
		if(empty($_POST['birthday'])) {
			$GLOBALS['error_message'] = '请输入生日';
			return;
		}
		
		$user['name'] = $_POST['name'];
		$user['gender'] = $_POST['gender'];
		$user['birthday'] = $_POST['birthday'];

		// 头像文件
		// 有文件就修改
		if(isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
			// 用户上传了文件 -->  用户希望修改文件
			$data = $_FILES['avatar'];
			$ext = pathinfo($data['name'], PATHINFO_EXTENSION);
			$source = $data['tmp_name'];
			$target = 'img/avatar-' . uniqid() . '.' . $ext;  // 目标文件放在那里
			$moved = move_uploaded_file($source, $target);   // 返回一个值代表是否移动成功
			if(!$moved) {
				$GLOBALS['error_message'] = '上传失败';
				return; 
			}
			// 移动成功(上传整个过程OK)

			$user['avatar'] = substr($target,0);
			

			// $user   ---> 修改后的信息 
			
		}
		// var_dump($user); 


		// if(empty($_FILES['avatar'])) {
  //   		$GLOBALS['error_message'] = '请上传头像';
  //   		return;
  // 		}
		// if($_FILES['avatar']['error'] != UPLOAD_ERR_OK) {
		// 	$GLOBALS['error_message'] = '上传失败';
		// 	return;
		// }
		// $data = $_FILES['avatar'];
		// $ext = pathinfo($data['name'], PATHINFO_EXTENSION);
		// $source = $data['tmp_name'];

		// // 移动的目标路径中文件夹一定是一个已经存在的目录
		// // 可以通过代码创建文件夹
		// // move_uploaded_file在Windows中文系统下，要求传入的参数如果有中文必须是GBK编码
		// // 将 UTF_8 编码 转化成 GBK 编码
		// // iconv('UTF-8','GBK',$data['name']); 
		// // 切记在接受文件时注意文件名的中文问题，通过 iconv函数 转化中文编码为 GBK编码 
		// // $target = 'img/avatar-' . uniqid() . '.' . iconv('UTF-8','GBK',$data['name']);  // 目标文件放在那里
		// $target = 'img/avatar-' . uniqid() . '.' . $ext;  // 目标文件放在那里
		// $moved = move_uploaded_file($source, $target);   // 返回一个值代表是否移动成功
		// if(!$moved) {
		// 	$GLOBALS['error_message'] = '上传失败';
		// 	return; 
		// }
		// 移动成功(上传整个过程OK)

		// $avatar = substr($target,0);
		// var_dump($name);
		// var_dump($gender);
		// var_dump($birthday);
		// var_dump($avatar);






		$connection = mysqli_connect('localhost','root','0000','case05');
		if(!$connection) {
			$GLOBALS['error_message'] = '数据库连接失败';
			return;
		} 
		$id = $user['id'];
		$name = $user['name'];
		$gender = $user['gender'];
		$birthday = $user['birthday'];
		$avatar = $user['avatar'];
		$query = mysqli_query($connection,"replace into alluser values($id,'$name', '$gender', '$birthday', '$avatar');");
		// $query = mysqli_query($connection,"insert into alluser values(null,'{$name}', {$gender}, '{$birthday}', '{$avatar}');");
		if(!$query) {
			$GLOBALS['error_message'] = '数据查询失败';
			return;
		}

		$rows = mysqli_affected_rows($connection);
		if($rows <= 0) {
			$GLOBALS['error_message'] = '修改失败';
			return;
		}

		// 响应
		mysqli_free_result($query);
		mysqli_close($connection);
		header('Location: index.php'); 
		
	}

	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		POSTBACK ();
	}
		

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>XXX管理系统</title>
  <link rel="stylesheet" href="bootstrap.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <nav class="navbar navbar-expand navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="#">XXX管理系统</a>
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="index.html">用户管理</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">商品管理</a>
      </li>
    </ul>
  </nav>
  <main class="container">
    <h1 class="heading">编辑 "<?php echo $user['name'];?>"</h1>
    <?php if (isset($error_message)): ?>
    	<div class="alert alert-warning">
    		<?php echo $error_message; ?>
    	</div>
    <?php endif ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $user['id']; ?>" method="POSt" enctype="multipart/form-data" autocomplete="off">
      <div class="form-group">
        <label for="avatar">头像</label>
        <input type="file" class="form-control" id="avatar" name="avatar">
      </div>
      <div class="form-group">
        <label for="name">姓名</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name']; ?>">
      </div>
      <div class="form-group">
        <label for="gender">性别</label>
        <select class="form-control" id="gender" name="gender">
          <option value="-1">请选择性别</option>
          <option value="1"<?php echo $user['gender'] === '1' ? ' selected' : '';?>>男</option>
          <option value="0"<?php echo $user['gender'] === '0' ? ' selected' : '';?>>女</option>
        </select>
      </div>
      <div class="form-group">
        <label for="birthday">生日</label>
        <input type="date" class="form-control" id="birthday" name="birthday" value="<?php echo $user['birthday']; ?>">
      </div>
      <button class="btn btn-primary btn-block">保存</a></button>
    </form>
  </main>
</body>
</html>
