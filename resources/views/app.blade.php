<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>{{ isset($title) ? $title . ' - ' : null }}Laravel - The PHP Framework For Web Artisans</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="author" content="Taylor Otwell">
	<meta name="description" content="Laravel - The PHP framework for web artisans.">
	<meta name="keywords" content="laravel, php, framework, web, artisans, taylor otwell">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--[if lte IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<link rel="apple-touch-icon" href="/favicon.png">
	<link rel="stylesheet" href="{{mix('/css/app.css')}}">
</head>

<body class="@yield('body-class', 'docs') language-php">
	<script src="{{mix('/js/app.js')}}"></script>
	<span class="overlay"></span>

	<nav class="main">
		<div class="container">
			<a href="/" class="brand">
				<img src="/assets/img/laravel-logo.png" height="30" alt="Laravel logo">
				Laravel
			</a>

			<div class="responsive-sidebar-nav">
				<a href="#" class="toggle-slide menu-link btn">&#9776;</a>
			</div>


			<ul class="main-nav">
			</ul>
		</div>
	</nav>

	<div class="container">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
			 <div class="panel panel-default">

				<div class="panel-body">
					@yield('content')
			 	</div>

			 </div>
			</div>
		</div>
	</div>

</body>
</html>
