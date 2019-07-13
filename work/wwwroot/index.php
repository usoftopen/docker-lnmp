<!DOCTYPE html>
<html>
<head>
	<title>Docker LNMP</title>
	<style type="text/css">
		html{
			font-family:sans-serif;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%
		}
		.mine{
			font-size:30px;margin-top:200px;
			color:#333;
		}
		.desc{
			text-align: center;
			color:#ccc;
			margin-bottom: 50px;
			line-height: 30px;
		}
		footer{
			text-align: center;
		}
		footer a{
			color:#fff;
			background: #F95445;
			display: block;
			width: 180px;
			margin:0 auto;
			text-decoration: none;
			line-height: 40px;
			height: 40px;
			border-radius: 3px;
		}
	</style>
</head>
<body>

<p align="center" class="mine">Docker LNMP</p>

<p class="desc">
	Version：3.3<br />
	Time：<?= date_default_timezone_get() . "&nbsp;/&nbsp;" . date("Y-m-d H:i:s");?>
</p>

<footer>
	<a href="https://github.com/exc-soft/docker-lnmp" target="_blank">Get Documentation</a>

	<p style="color:#ccc;font-size:12px;margin-top:100px;">
		&copy; <?php echo date("Y")?>&nbsp;usoftglobal.com
	</p>
</footer>

</body>
</html>