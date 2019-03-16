<?php

$p = '';
$p1 = '';
$p2 = '';


if(isset($_GET['keyword']) and !isset($_GET['nextPage']))
{
	if(isset($_GET["p"])){ $p = preg_replace('#[^0-9]#i', '', $_GET['p']); } else { $p = 1; }
	$keyword = $_GET['keyword'];
	$keyword=preg_replace("/ /","+",$keyword);
	$keyword=preg_replace("/\'/","",$keyword);
	$response = file_get_contents("https://www.googleapis.com/youtube/v3/search?part=snippet&q={$keyword}&type=video&key=AIzaSyCMRKhHFAEtEtUg00qt6KI3Ix_0Xrqt3uo&maxResults=5");
	$searchResponse = json_decode($response,true);
	$nextPage = $searchResponse['nextPageToken'];
	$totalResults = $searchResponse['pageInfo']['totalResults'];
	if($totalResults <= 5) { $p2 = $totalResults; } else { $p2 = 5; }
	?>
	<div align="center" style="margin:10px 0;text-align:center;padding:10px;display:inline-block;width:100%;">
		<div style="margin:0 0 5px 0;text-align:center;">Results: 1-<?= $p2 ?> of <?php echo $totalResults; ?></div>
		<button class="button-small2 brown_normal" disabled style="display:inline-block;">PREV</button>
		<button class="button-small2 brown_normal ripple" onClick="nextPage('<?php echo $keyword; ?>', '<?php echo $nextPage; ?>', '<?= $p ?>')" style=" display:inline-block;">NEXT</button>
	</div>
	<div>
<?php
	$k = 1;
	foreach ($searchResponse['items'] as $searchResult) {
	$a = $searchResult['id']['videoId'];
	$b = preg_replace('/[^a-zA-Z 0-9]/', '', $searchResult['snippet']['title']);
	$travelTitle = str_replace(" ", "_", $b);
	$c =  $searchResult['snippet']['description'];

		$JSON= file_get_contents('https://www.googleapis.com/youtube/v3/videos?part=contentDetails&id='.$a.'&key=AIzaSyCMRKhHFAEtEtUg00qt6KI3Ix_0Xrqt3uo&maxResults=1');
		$JSON_Data = json_decode($JSON);
		foreach ($JSON_Data->items as $ids) {
			$date = new DateTime('1970-01-01');
			$date->add(new DateInterval($ids->contentDetails->duration));
			$h = $date->format('H');
			$m = $date->format('i');
			$s = $date->format('s');
			if($h == '00') { $thehours = ''; } else { $thehours = (int)$h.':'; }
			if($m == '00') 
			{ 
				if($thehours != '')
				{ 
					$themins = sprintf("%02s", $m).':'; 
				} 
				else 
				{ 
					$themins = "0:"; 
				} 
			} 
			else 
			{ 
				if($thehours != '') 
				{ 
					$themins = sprintf("%02s", $m).':'; 
				} 
				else 
				{ 
					$themins = (int)$m.':'; 
				} 
			}
			$thesecs = sprintf("%02s", $s);
		}
		$theduration = $thehours.$themins.$thesecs;
		if($k == 1) { $forumcolor = 'lightrow rowstyle'; } else { $forumcolor = ''; }
	?>

			<div class="yt_vid_search_container">
				<table class="yt_vid_search_container_table">
					<tr>
						<td style="width:30%;">
							<span class="video_thumb thumbbox" id="vidimg-container">
								<a href="" onclick="parent.open_dock_window('<?= $a ?>'); return false;"><img src="<?php echo $searchResult['snippet']['thumbnails']['high']['url']; ?>" alt="" ></a>
								<span class="duration2"><?php echo $thehours.$themins.$thesecs; ?></span>
							</span>
						</td>
						<td class="yt_vid_details">
							<div id="yt_details_link"><a href="" onclick="parent.open_dock_window('<?= $a ?>'); return false;"><?= $b ?></a></div>
							<?= $c ?>
							<div style="margin: 10px 0 0 0;">
								<button class="button-small2 blue_dark ripple" onclick="$('#chat_message').val($('#chat_message').val()+'\r\n[youtube=<?= $b ?>]<?= $a ?>[/youtube]'); document.getElementById('chat_message').focus();" style="width:100px;">POST</button>
							</div>
						</td>
					</tr>
				</table>
			</div>
			<div id="preview_<?= $a ?>" class="showwindow"></div>
			<div id="<?= $a ?>_error"></div>
			<div id="playlistport_<?= $a ?>" class="savewindow"></div>
	</div>
		<?php
		if($k == 1) { $k++; } else { $k = 1; }
	}
			
		?>
		<div align="center" style="margin:10px 0;text-align:center;padding:10px;display:inline-block;width:100%;">
			<button class="button-small2 brown_normal" disabled style="display:inline-block;">PREV</button>
			<button class="button-small2 brown_normal ripple" onClick="nextPage('<?php echo $keyword; ?>', '<?php echo $nextPage; ?>', '<?= $p ?>'); $('html, body').animate({ scrollTop: $(vidcontent).offset().top-40 }, 0);" style="display:inline-block;">NEXT</button>
		</div>
		
	<?php
	//echo $response;
	// $nextPage = $searchResponse['nextPageToken'];
}

if(isset($_GET['keyword']) and isset($_GET['nextPage']))
{
	if(isset($_GET["up"])){ $p = preg_replace('#[^0-9]#i', '', $_GET['up']);$p = $p + 1; }
	if(isset($_GET["down"])){ $p = preg_replace('#[^0-9]#i', '', $_GET['down']);$p = $p - 1; }
	$nextPage = $_GET['nextPage'];
	$keyword = $_GET['keyword'];
	$keyword = preg_replace("/ /","+",$keyword);
	$response = file_get_contents("https://www.googleapis.com/youtube/v3/search?part=snippet&q={$keyword}&type=video&key=AIzaSyCMRKhHFAEtEtUg00qt6KI3Ix_0Xrqt3uo&maxResults=5&pageToken={$nextPage}");
	$searchResponse = json_decode($response,true);
	$nextPage = $searchResponse['nextPageToken'];
	$totalResults = $searchResponse['pageInfo']['totalResults'];
	$p1 = ((($p-1) * 5) + 1);
	if($totalResults <= 5) { $p2 = $totalResults; } else { $p2 = ($p1 + 4); }
	$currentResults = $p1."-".$p2;
	if($p1 != 1) { $prevPage = $searchResponse['prevPageToken']; }
?>
	<div align="center" style="margin:10px 0;text-align:center;padding:10px;display:inline-block;width:100%;">
		<div style="margin:0 0 5px 0;text-align:center;">Results: <?= $currentResults ?> of <?php echo number_format($totalResults); ?></div>
		<button class="button-small2 brown_normal ripple" <?php if($p1 == 1) { ?>disabled <?php } ?>onClick="prevPage('<?php echo $keyword; ?>', '<?php echo $prevPage; ?>', '<?= $p ?>');" style="display:inline-block;">PREV</button>
		<button class="button-small2 brown_normal ripple" onClick="nextPage('<?php echo $keyword; ?>', '<?php echo $nextPage; ?>', '<?= $p ?>');" style="display:inline-block;">NEXT</button>
	</div>
	<div>
<?php	
	$k = 1;
	foreach ($searchResponse['items'] as $searchResult) {
	$a = $searchResult['id']['videoId'];
	$b = preg_replace('/[^a-zA-Z 0-9]/', '', $searchResult['snippet']['title']);
	$travelTitle = str_replace(" ", "_", $b);
	$c =  $searchResult['snippet']['description'];
$JSON= file_get_contents('https://www.googleapis.com/youtube/v3/videos?part=contentDetails&id='.$a.'&key=AIzaSyCMRKhHFAEtEtUg00qt6KI3Ix_0Xrqt3uo&maxResults=1');
$JSON_Data = json_decode($JSON);
foreach ($JSON_Data->items as $ids) {
        $date = new DateTime('1970-01-01');
        $date->add(new DateInterval($ids->contentDetails->duration));
		$h = $date->format('H');
		$m = $date->format('i');
		$s = $date->format('s');
	if($h == '00') { $thehours = ''; } else { $thehours = (int)$h.':'; }
	if($m == '00') 
	{ 
		if($thehours != '')
		{ 
			$themins = sprintf("%02s", $m).':'; 
		} 
		else 
		{ 
			$themins = "0:"; 
		} 
	} 
	else 
	{ 
		if($thehours != '') 
		{ 
			$themins = sprintf("%02s", $m).':'; 
		} 
		else 
		{ 
			$themins = (int)$m.':'; 
		} 
	}
	$thesecs = sprintf("%02s", $s);
}
	$theduration = $thehours.$themins.$thesecs;
	if($k == 1) { $forumcolor = 'lightrow rowstyle'; } else { $forumcolor = ''; }
	?>
			<div class="yt_vid_search_container">
				<table class="yt_vid_search_container_table">
					<tr>
						<td style="width:30%;">
							<span class="video_thumb thumbbox" id="vidimg-container">
								<a href="" onclick="parent.open_dock_window('<?= $a ?>'); return false;"><img src="<?php echo $searchResult['snippet']['thumbnails']['high']['url']; ?>" alt="" ></a>
								<span class="duration2"><?php echo $thehours.$themins.$thesecs; ?></span>
							</span>
						</td>
						<td class="yt_vid_details">
							<div id="yt_details_link"><a href="" onclick="parent.open_dock_window('<?= $a ?>'); return false;"><?= $b ?></a></div>
							<?= $c ?>
							<div style="margin: 10px 0 0 0;">
								<button class="button-small2 blue_dark ripple" onclick="$('#chat_message').val($('#chat_message').val()+'\r\n[youtube=<?= $b ?>]<?= $a ?>[/youtube]'); document.getElementById('chat_message').focus();" style="width:100px;">POST</button>
							</div>
						</td>
					</tr>
				</table>
			</div>
			<div id="preview_<?= $a ?>" class="showwindow"></div>
			<div id="<?= $a ?>_error"></div>
			<div id="playlistport_<?= $a ?>" class="savewindow"></div> 
	</div>
		<?php if($k == 1) { $k++; } else { $k = 1; } } ?>
	<div align="center" style="margin:10px 0;text-align:center;padding:10px;display:inline-block;width:100%;">
		<button class="button-small2 brown_normal ripple" <?php if($p1 == 1) { ?>disabled <?php } ?>onClick="prevPage('<?php echo $keyword; ?>', '<?php echo $prevPage; ?>', '<?= $p ?>'); $('html, body').animate({ scrollTop: $(vidcontent).offset().top-40 }, 0);" style="display:inline-block;">PREV</button>
		<button class="button-small2 brown_normal ripple" onClick="nextPage('<?php echo $keyword; ?>', '<?php echo $nextPage; ?>', '<?= $p ?>'); $('html, body').animate({ scrollTop: $(vidcontent).offset().top-40 }, 0);" style="display:inline-block;">NEXT</button>
	</div>
	<?php } ?>