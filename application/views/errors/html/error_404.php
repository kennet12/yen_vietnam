<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>404 Page Not Found</title>
<style type="text/css">

*{ 
	margin: 0;
	padding: 0;
}

body {
	background-color: #15AB84;
	font: 13px/20px normal Helvetica, Arial, sans-serif;
	color: #fff;
}

a {
	color: #ffeb3b;
	background-color: transparent;
	font-weight: normal;
}
p {
	font-size: 16px;	
}
h1 {
	color: #fff;
    background-color: transparent;
    font-size: 45px;
    font-weight: normal;
    margin-top: 50px;
    padding: 14px 15px 10px 15px;
    font-weight: bold;
}
#container {
	text-align: center;
}
</style>
</head>
<body>
	<div id="container">
		<h1>404 Not Found</h1>
		<img src="<?=IMG_URL.'404_error.gif'?>" alt="">
		<p>Trang này không tồn tại. Vui lòng bấm <a href="<?=BASE_URL?>">vào đây</a> để quay lại trang chủ</p>
	</div>
</body>
</html>