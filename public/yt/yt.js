// Developed By TechnologyMantraBlog.com
// Author: Mr. Hrishabh Sharma
// http://technologymantrablog.com/
// Please do not remove the credit info if you respect my efforts

var playListArray;

$(document).ready(function(){
   playListArray = new Array();
 });

function playInPlayList(index)
{
	player.playVideoAt(index);
}

function playThis(videoID)
{
	 player.loadVideoById(videoID);
	
}

function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData("text", ev.target.id);
}

function drop(ev) {
    ev.preventDefault();
    var data = ev.dataTransfer.getData("text");
    ev.target.appendChild(document.getElementById(data));
	
	playListArray.push(data);
	player.cuePlaylist(playListArray);
	
	var vidIndex = playListArray.length - 1;
	document.getElementById(data).setAttribute('onClick', 'playInPlayList('+vidIndex+')');
}

function nextPage(keyword,token,pagenum) {
	document.getElementById("videoList").innerHTML="<div class=\"loadings\"></div>";
	  if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp1=new XMLHttpRequest();
	  } else { // code for IE6, IE5
		xmlhttp1=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	  xmlhttp1.onreadystatechange=function() {
		if (xmlhttp1.readyState==4 && xmlhttp1.status==200) {
		  document.getElementById("videoList").innerHTML=xmlhttp1.responseText;
		}
	  }

	  	keyword = keyword.replace(/ /g, '%2B');
		xmlhttp1.open("GET","yt/find.php?keyword="+keyword+"&nextPage="+token+"&up="+pagenum,true);
	  	xmlhttp1.send();
	}
	
function prevPage(keyword,token,pagenum) {
	document.getElementById("videoList").innerHTML="<div class=\"loadings\"></div>";
	  if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp1=new XMLHttpRequest();
	  } else { // code for IE6, IE5
		xmlhttp1=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	  xmlhttp1.onreadystatechange=function() {
		if (xmlhttp1.readyState==4 && xmlhttp1.status==200) {
		  document.getElementById("videoList").innerHTML=xmlhttp1.responseText;
		}
	  }
	  	keyword = keyword.replace(/ /g, '%2B');
		xmlhttp1.open("GET","yt/find.php?keyword="+keyword+"&nextPage="+token+"&down="+pagenum,true);
	  	xmlhttp1.send();
	}

function getVideoList(keyword) {
	document.getElementById("videoList").innerHTML="<div class=\"loadings\"></div>";
	  if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp2 = new XMLHttpRequest();
	  } else { // code for IE6, IE5
		xmlhttp2 = new ActiveXObject("Microsoft.XMLHTTP");
	  }
	  xmlhttp2.onreadystatechange=function() {
		if (xmlhttp2.readyState==4 && xmlhttp2.status==200) {
		  document.getElementById("videoList").innerHTML = xmlhttp2.responseText;
		}
	  }
	  
	  if(keyword.length > 0)
	  {
	  	keyword = keyword.replace(/ /g, '%2B');
		xmlhttp2.open("GET","yt/find.php?keyword="+keyword,true);
	  }
	  else
	  {
	    xmlhttp2.open("GET","yt/find.php?keyword=documentary",true);
	  }
	  
	  xmlhttp2.send();
	}
	