<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<title>fetchr</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="js/bootstrap.min.js" type="text/javascript" ></script>
	<!-- Prompt IE 6 users to install Chrome Frame. Remove this if you want to support IE 6.
	chromium.org/developers/how-tos/chrome-frame-getting-started -->
				<!--[if lt IE 7 ]>
					<script defer src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
					<script defer>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
					<![endif]-->
					<script>
						String.prototype.startsWith = function(prefix) {
						    return this.indexOf(prefix) === 0;
						}

						String.prototype.endsWith = function(suffix) {
						    return this.match(suffix+"$") == suffix;
						};
						function setProgress(percent)
						{
							$('.progress-bar').css({width:percent+"%"})
						}
						function startFetch(id)
						{
							$.ajax({
								url:"download.php",
								type: "POST",
								data: {id:id},
								
								async: true,
								cache: false,

								success:function(answer){
									console.log(answer)
									if(answer.status == -1)
									{
										console.log("answer was fail")
										enableFetch()
										showError("",answer.msg)
									}
								}
							})
							
						}
						function refreshProgress(id)
						{
							
							$.ajax({
								url:"getstatus.php",
								type: "POST",
								data: {id:id},
								
								async: true,
								cache: false,
								success: function(data){
									try{
										
										setProgress((data.currentChapter / data.totalChapters) * 100)
									}catch(err){}
									
									console.log(data)

									try{
										if(data.totalChapters != data.currentChapter){
											refreshProgress(id);
										} else {
											enableFetch()	
											$('#hidden').html("<form action='transmit.php' method='post' name='transmit'><input name='id' value='"+id+"' type='hidden' ></input><input name='fname' value='ayy' type='hidden' ></input></form>");
											document.forms["transmit"].submit();
											setProgress(0)
										}
									}catch(err){alert(err);refreshProgress(id);}
									

								}
							})
						}
						function enableFetch(){
							$('.fetch').html('Fetch!')
							$('.fetch').removeAttr("disabled")
						}
						function disableFetch(){
							$('.fetch').html('Fetching...<span class="glyphicon loading" style="top:3px; display: inline-block; width:16px; height:16px; background-image: url(img/spinner.gif); background-repeat:no-repeat; background-size:cover;"></span>')
							$('.fetch').attr("disabled","disabled")
						}
						function showError(errorTitle, errorMessage){
							element = $('<div class="alert alert-danger" role="alert">'+errorMessage+'</div>')

							$('#alerts').append(element)
							element.hide().fadeIn(200).delay(5000).fadeOut(200)


						}
						$('document').ready(function(){
							$('.fetch').on("click", function(){
								disableFetch()
								$.post(
									"fetchr.php",
									{url: $('.url').val()}

								)
								.done(function(answer){
									console.log(answer);
									if(answer.status == -1)
									{
										console.log("answer was fail")
										enableFetch()
										showError("",answer.msg)
									}

									if(answer.status == 1)
									{
										console.log("starting fetch")
										startFetch(answer.dlid)
										//refreshProgress(answer.dlid);
										
										//document.forms["download"].submit();
										
									}

								})
							})
							$('.url').on("input",function(){
								url = $('.url').val();
								console.log(url)
								if(url.startsWith("http://")){
									url = url.replace('http://','')
									$('.url').val(url)
									$('.protocol').text("http://")
								}
								if(url.startsWith("https://")){
									url = url.replace('https://','')
									$('.url').val(url)
									$('.protocol').text("https://")
								}
							})
						})

					</script>
					<style>
						.loading{
							display:inline;
							-webkit-animation-name: spin;
							-webkit-animation-duration: 1000ms;
							-webkit-animation-iteration-count: infinite;
							-webkit-animation-timing-function: linear;


							-moz-animation-name: spin;
							-moz-animation-duration: 1000ms;
							-moz-animation-iteration-count: infinite;
							-moz-animation-timing-function: linear;

							animation-name: spin;
							animation-duration: 1000ms;
							animation-iteration-count: infinite;
							animation-timing-function: linear;
						}
						@-moz-keyframes spin {

							from { -moz-transform: rotate(0deg); }
							to { -moz-transform: rotate(360deg); }
						}
						@-webkit-keyframes spin {

							from { -webkit-transform: rotate(0deg); }
							to { -webkit-transform: rotate(360deg); }
						}
						@keyframes spin {

							from {transform:rotate(0deg);}
							to {transform:rotate(360deg);}
						}
						.progress{
							height:5px;
							border:0;
							border-top-right-radius: 0;
							border-top-left-radius: 0;
						}
						.input-group *{
							border-bottom-right-radius: 0;
							border-bottom-left-radius: 0;
							border-bottom: 0;
						}
					</style>
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
							<div class="progress-bar progress-bar-success" role="progressbar">

							</div>
						</div>


					</div>


				</body>
				</html>


				<?php

				if(isset($_POST["url"])){

				}


				?>