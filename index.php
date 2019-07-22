<?php
	require(__DIR__ . "/config/config.php");
	require(APP_ROOT . "/classes/class.interactor.php");
	
	try {
		$interactor		=	new interactor();
		$birthdayData	=	$interactor->get();
		$birthdayData	=	json_decode($birthdayData, FALSE);
	} catch (Exception $e) {
		echo $e;
	}
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
							<?php foreach ($birthdayData as $birthday) { ?>
							<tr>
								<td><?php echo $birthday->user_name; ?></td>
								<td><?php echo date("jS F Y", strtotime($birthday->user_dob)); ?></td>
								<td><?php echo interactor::betweenDates($birthday->user_dob, date("Y-m-d")); ?></td>
								<td><a class="text text-danger" href="process?action=delete&entry=<?php echo $birthday->id; ?>"><i class="fa fa-trash"></i> Delete</a></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<div class="col-lg-3">
					<h1 class="mt-4">
						<h1>Useful Info</h1>
						<p>Hi there! This system has been developed as part of a Coding Challenge by FLG. Hope you like it!</p>
						<hr />
						<h3>Add a birthday</h3>
						<form action="debug.php" method="post">
							<label for="user_name">What's the name</label>
							<input type="text" name="user_name" id="user_name" class="form-control" required="require" autofocus="autofocus" onkeyup="adaptName();" />
							<br />
							<label for="user_dob">What's <span id="users_name_for_js">their</span> date of birth?</label>
							<input type="date" name="user_dob" id="user_dob" class="form-control"  max="<?php echo date("Y-m-d", strtotime("today")); ?>" required="required" />
							<br />
							<button type="submit" class="form-control btn btn-success">Submit</button>
						</form>
					</h1>
				</div>
			</div>
		</div>
		<?php include(APP_ROOT . "/includes/corejs.php"); ?>
		<script>
			$(function () {
				$('[data-toggle="tooltip"]').tooltip()
			})
			function adaptName() {
				if ($( "#user_name").val() == "" ) {
					$( "#users_name_for_js" ).html("their");
				} else {
					$( "#users_name_for_js" ).html( $( "#user_name").val() + "'s" );
				}
			}
		</script>
	</body>
</html>