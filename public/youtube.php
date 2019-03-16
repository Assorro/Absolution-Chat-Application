	<div class="yt_search_container">
		<table width="100%">
			<tr>
				<td style="width:25%;padding-right:10px;">
					<div id="noback2-container"><img src="yticon3.png" alt="" style="height:40px;"></div>
				</td>
				<td style="padding-bottom:15px;">
					<form class="inline" role="form" name="searchForm2" method="post" action="" onSubmit="getVideoList(keyword.value); return false;">
						<table cellpadding="0" cellspacing="0" style="width:100%; margin-top:0px;">
							<tr valign="top">
								<td style="width:100%;" align="right"><input type="text" id="searchterm" class="searchfield" name="keyword" value="Search YouTube"></td>
								<td align="left" style=""><button id="go" class="ripple" onClick="getVideoList(keyword.value);indexCenter_request();" style="margin-left:0px;width:50px;border:none;height:45px;"> &#9658;</button></td>
							</tr>
						</table>
					</form>
					
				</td>
			</tr>
		</table>
		<div id="topofvids"></div>
		<div class="videoList" id="videoList"></div>
	</div>
	<script type="text/javascript" src="yt/yt.js"></script>
	<script>
		var nameElement = document.forms.searchForm2.searchterm;
		function nameFocus( e ) {
			var element = e.target || window.event.srcElement;
			if ( element.value == "Search YouTube" )
			element.value = "";
		}
		function nameBlur(e) {
			var element = e.target || window.event.srcElement;
			if ( element.value === "" )
			element.value = "Search YouTube";
		}
		if ( nameElement.addEventListener ) {
			nameElement.addEventListener("focus", nameFocus, false);
			nameElement.addEventListener("blur", nameBlur, false);
		} else if ( nameElement.attachEvent ) {
			nameElement.attachEvent("onfocus", nameFocus);
			nameElement.attachEvent("onblur", nameBlur);
		}
	</script>
	<script>
		getVideoList('space documentaries');
		var tag = document.createElement('script');
		tag.src = "https://www.youtube.com/iframe_api";
		var firstScriptTag = document.getElementsByTagName('script')[0];
		firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
		var player;
		function onYouTubeIframeAPIReady() {
			player = new YT.Player('player', {
				videoId: 'W_MThsOC2Eg',
				playerVars: {
					controls: 1,
					disablekb: 1
				},
				events: {
					'onReady': onPlayerReady,
					'onStateChange': onPlayerStateChange
				}
			});
		}
		function onPlayerReady(event) {
			event.target.playVideo();
			myVidId.innerHTML = 'hS5CfP8n_js';
		  }
		  function onPlayerStateChange(event) {
			
		  }
	</script>