<?php

$hcoords = '47.063415° N, 6.021783° E';
$coords = preg_replace('#[^0-9.,]#', '', $hcoords);

$left = text('
<h3>Val Sainte Marie</h3>
<p>
25330 Malans<br />
Franche-Comté<br />
Frankreich
</p>
<p>'.$hcoords.'</p>
<p>
<a href="http://maps.google.'.$lang.'/maps?q=Val+Sainte+Marie+%40'.$coords.'&amp;z=7&amp;t=m" target="_blank" class="iframe">Route planen mit Google Maps</a>
</p>
');

$center = '
<div id="map" style="width:486px; height:243px; background-image:url(http://maps.google.com/maps/api/staticmap?sensor=false&amp;language='.$lang.'&amp;center='.$coords.'&amp;size=486x243&amp;maptype=street&amp;zoom=7&amp;markers='.$coords.')">
</div>
<script src="http://maps.google.com/maps/api/js?sensor=false&amp;language='.$lang.'&amp;region=fr"></script>
<script>
window.valstemarie.onload.push(function () {
	$("#map").css("background-image", "none");
	var val = new google.maps.LatLng('.$coords.');
	var map = new google.maps.Map(document.getElementById("map"), {
		center: val,
		mapTypeId: google.maps.MapTypeId.HYBRID,
		zoom: 7
	});
	var valmarker = new google.maps.Marker({
		position: val,
		map: map,
		title: "Val Sainte Marie"
	});
});
</script>
';
