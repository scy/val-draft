<?php

function calendarNav($year, $month) {
	global $basedir, $lang, $path;
	$here = "$basedir/$lang$path";
	$monthname = date('M', gmmktime(0, 0, 0, $month, 1, $year));
	$prevyear      = $year - 1;
	$prevmonth     = ($month == 1 ? 12 : $month - 1);
	$prevmonthyear = ($month == 1 ? $prevyear : $year);
	$nextyear      = $year + 1;
	$nextmonth     = ($month == 12 ? 1 : $month + 1);
	$nextmonthyear = ($month == 12 ? $nextyear : $year);
	return "<div id=\"calendarNav\">
	<a href=\"$here?year=$prevyear&amp;month=$month\">«</a>
	<a href=\"$here?year=$prevmonthyear&amp;month=$prevmonth\">&lt;</a>
	$monthname $year
	<a href=\"$here?year=$nextmonthyear&amp;month=$nextmonth\">&gt;</a>
	<a href=\"$here?year=$nextyear&amp;month=$month\">»</a>
</div>";
}

function calendarEntry($year, $month, $day) {
	$s = array('free', 'occupied', 'occupiedByUs', 'closed');
	$r = mt_rand(0, 10);
	return isset($s[$r]) ? $s[$r] : $s[0];
}

function calendarCell($year, $month, $day, $classes) {
	return array(
		'year' => $year,
		'month' => $month,
		'day' => $day,
		'time' => gmmktime(0, 0, 0, $month, $day, $year),
		'classes' => array_merge($classes, array(calendarEntry($year, $month, $day))),
	);
}

function calendarArray($year, $month) {
	if (!checkdate($month, 1, $year)) {
		return false;
	}
	$monthstart = gmmktime(0, 0, 0, $month, 1, $year);
	$monthstartwkday = date('N', $monthstart);
	$daysfromlastmonth = $monthstartwkday - 1;
	$lastmonthmonth = ($month == 1 ? 12 : $month - 1);
	$lastmonthyear = ($month == 1 ? $year - 1 : $year);
	$lastdaylastmonth = 31;
	while (!checkdate($lastmonthmonth, $lastdaylastmonth, $lastmonthyear)) {
		$lastdaylastmonth--;
	}
	$array = array(array());
	$row = 0;
	$inrow = 0;
	$dayfromlastmonth = $lastdaylastmonth - ($daysfromlastmonth - 1);
	for ($i = 0; $i < $daysfromlastmonth; $i++) {
		$array[0][] = calendarCell($lastmonthyear, $lastmonthmonth, $dayfromlastmonth, array('lastmonth', 'passivemonth'));
		$inrow++;
		$dayfromlastmonth++;
	}
	for ($day = 1; checkdate($month, $day, $year); $day++) {
		if (!isset($array[$row])) {
			$array[$row] = array();
		}
		$array[$row][] = calendarCell($year, $month, $day, array('thismonth', 'activemonth'));
		$inrow++;
		if ($inrow > 6) {
			$inrow = 0;
			$row++;
		}
	}
	$year = ($month == 12 ? $year + 1 : $year);
	$month = ($month == 12 ? 1 : $month + 1);
	$day = 1;
	for (; $inrow < 7; $inrow++) {
		$array[$row][] = calendarCell($year, $month, $day, array('nextmonth', 'passivemonth'));
		$day++;
	}
	return $array;
}

function calendarTable($year, $month) {
	$array = calendarArray($year, $month);
	if (!is_array($array)) {
		return '';
	}
	$r = '<table><tr><th>mo</th><th>di</th><th>mi</th><th>do</th><th>fr</th><th>sa</th><th>so</th>';
	foreach ($array as $row) {
		$r .= "\n	<tr>";
		foreach ($row as $col) {
			$classes = implode(' ', $col['classes']);
			$r .= "\n		<td class=\"$classes\">{$col['day']}</td>";
		}
		$r .= "\n	</tr>";
	}
	$r .= "\n</table>";
	return $r;
}

if (!isset($_GET['year']) || !isset($_GET['month'])) {
	redirect(sprintf('/booking/?year=%d&month=%d', date('Y'), date('m')));
}

$year  = (int)$_GET['year'];
$month = (int)$_GET['month'];

$page = 'booking';

$right = text('Anfrageformular.');

$center = '
<div id="calendarLegend">
</div>
<div id="calendar">
	<div class="tableWrapper">
' . calendarNav($year, $month) . '
' . calendarTable($year, $month) . '
	</div>
</div>
';
