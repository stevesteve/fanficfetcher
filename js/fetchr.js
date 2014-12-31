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
		url:"fetchStory",
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
			else if(answer.status == 1){
				$('#hidden').html("<form action='download' method='post' name='download'><input name='id' value='"+id+"' type='hidden' ></input><input name='fname' value='ayy' type='hidden' ></input></form>");
				document.forms["download"].submit();				
				enableFetch()				
			}
		}
	})

}
function refreshProgress(id)
{

	$.ajax({
		url:"getStatus",
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
			"createJob",
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
				refreshProgress(answer.dlid);

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