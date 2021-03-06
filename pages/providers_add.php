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
	$panel_notice = '';
	# Is name empty? if not proceed, otherwise show red panel.
	if (empty($_POST['name'])) { $panel_type = 'panel-default'; }
	else {
		# if name is set and match 'A-Z, a-z, 0-9, - and space' proceed. Otherwise show red panel.
		if (!preg_match('!^[\w /-]*$!', $_POST['name'])) { 
			$panel_type = 'panel-danger';
			$panel_notice .= "Error: Name contain illegal character(s). ";
		}
		if (!empty($_POST['website']) && filter_var($_POST['website'], FILTER_VALIDATE_URL) == false) { 
			$panel_type = 'panel-danger';
			$panel_notice .= "Error: Website contain illegal character(s). ";
		}
		if (!empty($_POST['address']) && !preg_match('!^[\w \.\/\-\r\n]*$!', $_POST['address'])) { 
			$panel_type = 'panel-danger';
			$panel_notice .= "Error: Address contain illegal character(s). ";
		}
		if (!empty($_POST['phone']) && !preg_match('!^[\w /-]*$!', $_POST['phone'])) { 
			$panel_type = 'panel-danger';
			$panel_notice .= "Error: Phone contain illegal character(s). ";
		}
		if (!empty($_POST['note']) && !preg_match('!^[\w \.\/\-\r\n]*$!', $_POST['note'])) { 
			$panel_type = 'panel-danger';
			$panel_notice .= "Error: Note contain illegal character(s). ";
		}

		if ($panel_type == 'panel-default' && !empty($_POST['name'])) {
			$name = $_POST['name'];
			$website = isset($_POST['website']) ? $_POST['website'] : '';
			$address = isset($_POST['address']) ? $_POST['address'] : '';
			$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
			$note = isset($_POST['note']) ? $_POST['note'] : '';
			
			# Execute MySQL. If there is not error show green panel and notification.
			# Else show red panel and error notification.
			$sql = "INSERT INTO `providers` (`pid`, `name`, `website`, `address`, `phone`, `notes`, `hide`) VALUES (NULL, '$name', '$website', '$address', '$phone', '$note', '0')";
			$result = mysqli_query($link, $sql);
			if ($result) {
				$panel_type = 'panel-success';
				$panel_notice = "Provider has been added. <a href=\"providers.php\" title=\"Return\" alt=\"Return\">Return to providers</a>";
			}
			else {
				$panel_type = 'panel-danger';
				$panel_notice = "Error: Can't add provider to database.";
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
	
	<!-- FA IconPicker -->
	<link rel="stylesheet" href="../css/image-picker.css" type="text/css" />
	
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
					<h1 class="page-header">Providers - Add</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
			<div class="row">
				<div class="col-lg-12">
					<!-- CODE -->
					
					<div class="panel <?PHP print $panel_type; ?>">
						<div class="panel-heading">
							Add a new provider
						</div>
						<div class="panel-body">
							<form role="form" method="post">
								<?PHP if (!empty($panel_notice)) { print "<div>$panel_notice</div><br>"; } ?>
								<div class="form-group">
									<input class="form-control" placeholder="Name" name="name">
									<p class="help-block">Name is mandatory. A-Z, a-z, 0-9, -, / and space.</p>
								</div>
								<div class="form-group">
									<input class="form-control" placeholder="Website" name="website">
									<p class="help-block">ex: http://google.ca or https://exemple.google.ca/details</p>
								</div>
								<div class="form-group">
									<label>Address:</label>
									<textarea name="address" class="form-control"></textarea>
								</div>
								<div class="form-group">
									<input class="form-control" placeholder="Phone" name="phone">
									<p class="help-block">ex: 555-555-5555</p>
								</div>
								<div class="form-group">
									<label>Note:</label>
									<textarea name="note" class="form-control"></textarea>
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
	
	<!-- FA IconPicker --><!--
	<script type="text/javascript" src="../js/image-picker.js" ></script>
	<script type="text/javascript" >
		$('.image-picker').imagePicker({
			selectorHeight: 100,
			selectorWidth: 1000,
			imageMaxHeight: 100,
			imageMaxWidth: 48
		});
	</script> -->
</body>

</html>

<?PHP include('xinc.foot.php'); ?>