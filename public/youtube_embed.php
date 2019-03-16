<?php
if(isset($_GET["vidid"])){ $videoID = preg_replace('#[^a-z0-9_-]#i', '', $_GET['vidid']); } else { exit; }
$therand = rand(1000,999999);
?>
<div class="preview-container" style="background-color:#000;" id="videoframe_<?= $videoID ?>">
	<iframe class="video" id="vidframe" width="80%" height="262px" style="border:none;" src="http://www.youtube.com/embed/<?= $videoID ?>?version=3&autoplay=1&autohide=1&modestbranding=1&loop=0&wmode=opaque&iv_load_policy=3&rel=0&showinfo=1&fs=1" allowfullscreen></iframe>
</div>