<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">	
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<title>fetchr</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/fetchr.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="js/bootstrap.min.js" type="text/javascript" ></script>
	<script src="js/fetchr.js"></script>

</head>
<body>

	<div id="hidden" style="display:none"></div>
	<div class="page-header">
		<h1 style="text-align:center">fetchr</h1>	
	</div>
	<div class="container">
		<div id="alerts" style="position:relative;height:55px;width:100%;margin-top:0;margin-bottom:20px;overflow:hidden"> <!-- alerts & msgs -->
			<!--<div class="alert alert-success" role="alert">...</div>-->
		</div>

		<div class="input-group">
			<span class="input-group-addon protocol">http://</span>
			<input name="url" type="text" class="form-control url" placeholder="URL">
			<span class="input-group-btn">
				<button class="btn btn-default fetch" type="button">				
					Fetch!
				</button>
			</span>

		</div>
		<div class="progress">
			<div class="progress-bar progress-bar-success" role="progressbar"></div>
		</div>
	</div>
</body>
</html>
