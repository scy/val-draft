<?php

$translations = array(
	'subtitle' => array(
		'de' => 'Eine Ferienkolonie im grünen Herzen Frankreichs',
		'fr' => 'Une colonie de vacances dans le cœur vert de la France',
		),
	'nav_house' => array(
		'de' => 'Das Haus',
		'fr' => 'La maison',
		),
	'nav_rencontres' => array(
		'de' => 'Der Verein',
		'fr' => 'L’association',
		),
	'nav_where' => array(
		'de' => 'Anfahrt und Umgebung',
		'fr' => 'Situation et environnement',
		),
	'nav_booking' => array(
		'de' => 'Buchen',
		'fr' => 'Réserver',
		),
	'nav_contact' => array(
		'de' => 'Kontakt',
		'fr' => 'Contact',
		),
);

$codes = array(
	403 => 'Forbidden',
	404 => 'Not Found',
	500 => 'Internal Server Error',
);

$basedir = dirname($_SERVER['PHP_SELF']);
$doesmatch = preg_match("#$basedir/(de|fr)(|/.*)$#", $_SERVER['REQUEST_URI'], $matches);
if (!$doesmatch) {
	redirect('/', 'de');
}
$lang = $matches[1];
$path = $matches[2];
$source = preg_replace(array('#[^a-zA-Z0-9/]#', '#/#'), array('', '_'), trim($path, '/'));
$source = './pages/' . ($source == '' ? 'home' : $source) . '.php';

if (!is_file($source)) {
	err(404, $path);
}
if (!is_readable($source)) {
	err(403, $path);
}
include $source;
go();

function err($code, $more) {
	global $codes, $page, $title, $path, $right;
	$title = "$code {$codes[$code]}";
	$path = '/';
	$page = "err$code";
	$right = text("<h3>$title</h3>");
	header("{$_SERVER['SERVER_PROTOCOL']} $title");
	go();
	exit();
}

function redirect($to, $ovrlang = null) {
	global $basedir, $lang;
	if (!$ovrlang) {
		$ovrlang = $lang;
	}
	$ssl = substr($_SERVER['SERVER_PROTOCOL'], 0, 5) == 'HTTPS' ? 's' : '';
	$port = (int)$_SERVER['SERVER_PORT'];
	$url = "http$s://" . $_SERVER['SERVER_NAME'] .
	       ((($ssl == '' && $port == 80) || ($ssl == 's' && $port == 443)) ? '' : ":$port") .
	       "$basedir/$ovrlang$to";
	header("Location: $url");
	exit();
}

function href($ovrpath = null, $ovrlang = null) {
	global $basedir, $lang, $path;
	if ($ovrpath === null) {
		$ovrpath = $path;
	}
	if ($ovrlang === null) {
		$ovrlang = $lang;
	}
	$slash = $ovrlang ? '/' : '';
	return "$basedir$slash$ovrlang$ovrpath";
}

function label($text) {
	return '<div class="label">_' . $text . '</div>';
}

function text($text) {
	return '<div class="text">' . $text . '</div>';
}

function T($key) {
	global $translations, $lang;
	return (isset($translations[$key]) && isset($translations[$key][$lang]))
	     ? $translations[$key][$lang] : $key;
}

function go() {
	global $basedir, $path, $lang;
	global $morecss, $morejs;
	global $title;
	global $page, $left, $right, $center;
?>
<!DOCTYPE html>
<html class="no-js">
<head>
	<link rel="stylesheet" href="<?php echo href('/css/2011/screen.css', ''); ?>" />
	<?php if (is_array($morecss)) {
		foreach ($morecss as $file) { ?>
	<link rel="stylesheet" href="<?php echo href($file, ''); ?>" />
		<?php }
	} ?>
	<meta http-equiv="Content-Type" value="text/html;charset=utf-8" />
	<meta name="viewport" content="width=800" />
	<title><?php if (isset($title)) { echo "{$title} « "; } ?>Val Sainte Marie</title>
	<script>
		window.valstemarie = {
			onload: []
		};
	</script>
</head>
<body class="<?php echo "$lang $page"; ?>"><div id="wrapper">
<div id="top">
	<h1><a href="<?php echo href('/'); ?>">Val Sainte Marie</a></h1>
	<h2><a href="<?php echo href('/'); ?>"><?php echo T('subtitle'); ?></a></h2>
	<div id="langswitch"><a href="<?php echo href(null, 'de');; ?>">auf Deutsch</a><span class="delim"> | </span><a href="<?php echo href(null, 'fr'); ?>">en français</a></div>
</div>
<div id="middle">
	<div id="social"><a href="">Twitter</a><span class="delim"> | </span><a href="http://www.facebook.com/group.php?gid=120928374601886">Facebook</a><span class="delim"> | </span><a href="">Flickr</a></div>
	<div id="main">
		<?php if (isset($left)) { ?><div id="left"><?php echo $left; ?></div><?php } ?>
		<?php if (isset($right)) { ?><div id="right"><?php echo $right; ?></div><?php } ?>
		<div id="center"><?php if (isset($center)) { echo $center; } ?></div>
	</div>
</div>
<div id="bottom">
	<a href="<?php echo href('/house/'); ?>"><?php echo T('nav_house'); ?></a><span class="delim"> | </span><a href="<?php echo href('/rencontres/'); ?>"><?php echo T('nav_rencontres'); ?></a><span class="delim"> | </span><a href="<?php echo href('/where/'); ?>"><?php echo T('nav_where'); ?></a><span class="delim"> | </span><a href="<?php echo href('/booking/'); ?>"><?php echo T('nav_booking'); ?></a><span class="delim"> | </span><a href="<?php echo href('/contact/'); ?>"><?php echo T('nav_contact'); ?></a>
</div>
</div>
<script src="<?php echo href('/js/modernizr-1.6.min.js', ''); ?>"></script>
<script src="<?php echo href('/js/jquery-1.5.min.js', ''); ?>"></script>
<script src="<?php echo href('/js/jquery.mousewheel.min.js', ''); ?>"></script>
<?php if (is_array($morejs)) {
	foreach ($morejs as $file) { ?>
<script src="<?php echo href($file, ''); ?>"></script>
	<?php }
} ?>
<script>
$.each(window.valstemarie.onload, function (idx, fun) {
	fun();
});
</script>
</body>
</html>
<?php
}
