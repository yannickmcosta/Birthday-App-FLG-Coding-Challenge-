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
				<div class="col-lg-8">
					<h1 class="mt-4">FLG Coding Challenge - Birthday Tracker</h1>
					<br />
					<h3>Today's Birthdays</h3>
					<table class="table table-striped" id="todayBirthdayTable">
						<thead>
							<tr>
								<th>User's Name</th>
								<th>Users Date Of Birth</th>
								<th>Age</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
					<hr />
					<h3>Birthdays in next 2 weeks</h3>
					<table class="table table-striped" id="birthdayTable">
						<thead>
							<tr>
								<th>User's Name</th>
								<th>Users Date Of Birth</th>
								<th class="d-none d-sm-block">How long until</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
					<hr />
					<h3>All birthdays</h3>
					<table class="table table-striped" id="allBirthdayTable">
						<thead>
							<tr>
								<th>User's Name</th>
								<th>Users Date Of Birth</th>
								<th class="d-none d-sm-block">How long until</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
				<div class="col-lg-4">
					<h1 class="mt-4">
						<h1>Useful Info</h1>
						<p>Hi there! This system has been developed as part of a Coding Challenge by FLG. Hope you like it!</p>
						<hr />
						<h3>Add a birthday</h3>
						<form action="process" method="post">
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
				$.ajax({ 
					type	:	"GET",
					url		:	"<?php echo API_ENDPOINT; ?>?today=true",
					success:function(data) {
						console.log(data);
						let birthdayTable	=	$( "#todayBirthdayTable tbody" );
						$.each(data, function(index, element){
							birthdayTable.append("<tr><td>" + element.user_name + "</td><td>" + element.user_dob_formatted + "</td><td>" + element.age + "</td><td><span class=\"delete-entry\"><a class=\"text-danger\" href=\"process?delete=" + element.id + "\"><i class=\"fa fa-trash\"></i> Delete</a></span></td>");
						})
					}
				});
				$.ajax({ 
					type	:	"GET",
					url		:	"<?php echo API_ENDPOINT; ?>?today=false",
					success:function(data) {
						console.log(data);
						let birthdayTable	=	$( "#birthdayTable tbody" );
						$.each(data, function(index, element){
							birthdayTable.append("<tr><td>" + element.user_name + "</td><td>" + element.user_dob_formatted + "</td><td class=\"d-none d-sm-block\">" + element.time_until + "</td><td><span class=\"delete-entry\"><a class=\"text-danger\" href=\"process?delete=" + element.id + "\"><i class=\"fa fa-trash\"></i> Delete</a></span></td>");
						})
					}
				});
				$.ajax({ 
					type	:	"GET",
					url		:	"<?php echo API_ENDPOINT; ?>",
					success:function(data) {
						console.log(data);
						let birthdayTable	=	$( "#allBirthdayTable tbody" );
						$.each(data, function(index, element){
							birthdayTable.append("<tr><td>" + element.user_name + "</td><td>" + element.user_dob_formatted + "</td><td class=\"d-none d-sm-block\">" + element.time_until + "</td><td><span class=\"delete-entry\"><a class=\"text-danger\" href=\"process?delete=" + element.id + "\"><i class=\"fa fa-trash\"></i> Delete</a></span></td>");
						})
					}
				})
			});
			function adaptName() {
				if ($( "#user_name").val() == "" ) {
					$( "#users_name_for_js" ).html("their");
				} else {
					$( "#users_name_for_js" ).html( $( "#user_name").val() + "'s" );
				}
			}
		</script>
		<?php if (isset($_GET['created'])) { ?>
		<script>
			Swal.fire('Created', 'Record added successfully!', 'success');
			window.history.replaceState({}, document.title, "/");
		</script>
		<?php } ?>
		<?php if (isset($_GET['notCreated'])) { ?>
		<script>
			Swal.fire('Error on creation', 'That record didn\'t get added, check the logs.', 'error');
			window.history.replaceState({}, document.title, "/");
		</script>
		<?php } ?>
		
		<?php if (isset($_GET['removed'])) { ?>
		<script>
			Swal.fire('Removed', 'Record successfully removed!', 'success');
			window.history.replaceState({}, document.title, "/");
		</script>
		<?php } ?>
		<?php if (isset($_GET['notRemoved'])) { ?>
		<script>
			Swal.fire('Error on removal', 'That record didn\'t get removed, check the logs.', 'error');
			window.history.replaceState({}, document.title, "/");
		</script>
		<?php } ?>
		
		<?php if (isset($_GET['badRequest'])) { ?>
		<script>
			Swal.fire('Bad Request', 'The request you made was bad, or didn\'t have everything needed, please try again.', 'warning');
			window.history.replaceState({}, document.title, "/");
		</script>
		<?php } ?>
		
		<?php if (isset($_GET['serverError'])) { ?>
		<script>
			Swal.fire('Internal Server Error', 'An internal server error occurred, please try again.', 'warning');
			window.history.replaceState({}, document.title, "/");
		</script>
		<?php } ?>
	</body>
</html>