<?php
	// Check for user object, create one if none
	if (!isset($_SESSION['activeUser'])) {
		$user = $userModel->newUser();
	} else {
		$user = $_SESSION['activeUser'];
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Clash of Clans Toolkit</title>
		
		<!-- Temporarily using Google Web Fonts. Will use cached fonts via @font-face at some point later -->
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Lato:300,400">

		<!-- Temporarily linked to remote framework stylesheet. Will be cached locally at some point later -->
		<link rel="stylesheet" type="text/css" href="http://projects.zachsaffrin.com/overeasy/style/css/overeasy.css">

		<!-- Local stylesheet -->
		<link rel="stylesheet" type="text/css" href="<?php echo URL ?>public/css/clashtool-style.css">
	</head>
	<body>

		<!-- Banner and Primary Navigation -->
		<div class="section">
			<div class="container">
				<div class="size-1-1">
					<header class="align-c">
						<h1>Clash of Clans Toolkit</h1>
					</header>
				</div>
				<div class="size-1-1">
					<nav>
						<ul class="nav-left">
							<li><a href="<?php echo URL ?>" class="button">Home</a>
						</ul>
						<ul class="nav-right">
							<?php
							if ($user->level>1) { ?>
								<li><b><?php echo $user->firstname.' '.$user->lastname; ?></b>
								<li><a href="<?php echo URL.'home/logout'; ?>" class="button">Log Out</a>
							<?php } else { ?>
								<li><a href="#" class="button inactive">Sign Up</a>
								<li><a href="<?php echo URL.'home/login'; ?>" class="button">Log In</a>
							<?php }
							?>
							
						</ul>
					</nav>
				</div>
			</div>
		</div>
		