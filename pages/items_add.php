<?PHP
	session_start();
	include('xinc.config.php');

	# Check if session exists.
	#  If Session (UID) is not existing, redirect to login.php
	#  Else show the page.
	if (empty($_SESSION['username'])) {
		header('location:login.php');
		die();
	}
	
	# Submission
	$panel_type = 'panel-default';
	# Is name empty? if not proceed, otherwise show red panel.
	if (empty($_POST['name']) && empty($_POST['details'])) { $panel_type = 'panel-default'; }
	elseif (!empty($_POST['details']) && empty($_POST['name'])) { $panel_type = 'panel-danger'; $panel_notice = "ERROR: Name is mandatory."; }
	else {
		# if name is set and match 'A-Z, a-z, 0-9, - and space' proceed. Otherwise show red panel.
		if (!preg_match('!^[\w -]*$!', $_POST['name'])) { 
			$panel_type = 'panel-danger';
			$panel_notice = "Error: Name contain illegal character(s).";
		}
		else {
			$name = $_POST['name'];
			$cid = $_POST['category'];
			if (!empty($_POST['details'])) { $details = $_POST['details']; }
			else { $details = ''; }
			
			# Execute MySQL. If there is not error show green panel and notification.
			# Else show red panel and error notification.
			$sql = "INSERT INTO `items` (`iid`, `name`, `details`, `cat`, `hide`) VALUES (NULL, '$name', '$details', '$cid', '0')";
			$result = mysqli_query($link, $sql);
			if ($result) {
				$panel_type = 'panel-success';
				$panel_notice = "Item has been added. <a href=\"items.php\" title=\"Return\" alt=\"Return\">Return to Items</a>";
			}
			else {
				$panel_type = 'panel-danger';
				$panel_notice = "Error: Can't add item to database.";
			}
		}
	}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<title><?PHP print $g_title; ?></title>

	<!-- Bootstrap Core CSS -->
	<link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- MetisMenu CSS -->
	<link href="../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

	<!-- Timeline CSS -->
	<link href="../dist/css/timeline.css" rel="stylesheet">

	<!-- Custom CSS -->
	<link href="../dist/css/sb-admin-2.css" rel="stylesheet">

	<!-- Morris Charts CSS -->
	<link href="../bower_components/morrisjs/morris.css" rel="stylesheet">

	<!-- Custom Fonts -->
	<link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->

</head>

<body>

	<div id="wrapper">

		<!-- Navigation -->
		<?PHP include('xinc.nav.php'); ?>

		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">Items - Add</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
			<div class="row">
				<div class="col-lg-12">
					<!-- CODE -->
					
					<div class="panel <?PHP print $panel_type; ?>">
					<div class="panel-heading">
						Add a new item
					</div>
					<div class="panel-body">
						<form role="form" method="post">
							<?PHP if (!empty($panel_notice)) { print "<div>$panel_notice</div><br>"; } ?>
							<div class="form-group">
								<input class="form-control" placeholder="Name" name="name">
								<p class="help-block">Name is mandatory. A-Z, a-z, 0-9, - and space.</p>
							</div>
							<div class="form-group">
								<input class="form-control" placeholder="Details" name="details" autocomplete="off">
							</div>
							<div class="form-group">
								<label>Category</label>
								<select class="form-control" name="category">
									<option value="">-</option>
									<?php
										$sql = "SELECT * FROM `category` ORDER BY `name` ASC";
										$result = mysqli_query($link, $sql);
										if (mysqli_num_rows($result) < 1) { print ""; }
										else {
											while($row = mysqli_fetch_assoc($result)) {
												print '<option value="'. $row["cid"] .'">' . $row["name"] . '</option>';
											}
										}
									?>
								</select>
							</div>
							<button type="submit" class="btn btn-default">Submit</button>
						</form>
					</div>
					</div>
					<!-- /CODE -->
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
		</div>
		<!-- /#page-wrapper -->

	</div>
	<!-- /#wrapper -->

	<!-- jQuery -->
	<script src="../bower_components/jquery/dist/jquery.min.js"></script>

	<!-- Bootstrap Core JavaScript -->
	<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

	<!-- Metis Menu Plugin JavaScript -->
	<script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

	<!-- Custom Theme JavaScript -->
	<script src="../dist/js/sb-admin-2.js"></script>
</body>

</html>

<?PHP include('xinc.foot.php'); ?>