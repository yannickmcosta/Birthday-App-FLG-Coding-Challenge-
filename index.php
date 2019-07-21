<?php
	include(__DIR__ . "/config/config.php"); 
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Birthdays 🎉 - FLG Coding Challenge</title>
		<?php include(APP_ROOT . "/includes/head.php"); ?>
	</head>
	<body>
		<!-- Navigation -->
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
			<div class="container">
				<a class="navbar-brand" href="#">Birthday Tracker 🎉</a>
			</div>
		</nav>
		<!-- Page Content -->
		<div class="container">
			<div class="row">
				<div class="col-lg-9">
					<h1 class="mt-4">FLG Coding Challenge - Birthday Tracker</h1>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>User's Name</th>
								<th>Users Date Of Birth</th>
								<th>How long until</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Yannick</td>
								<td>1st January 1970</td>
								<td>x days</td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="col-lg-3">
					<h1 class="mt-4">
						<h1>Useful Info</h1>
						<p>Hi there! This system has been developed as part of a Coding Challenge by FLG. Hope you like it!</p>
					</h1>
				</div>
			</div>
		</div>
		<?php include(APP_ROOT . "/includes/corejs.php"); ?>
	</body>
</html>