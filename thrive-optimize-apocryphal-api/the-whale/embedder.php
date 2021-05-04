<?php

namespace apt\thewhale;

//Include framework classes
if (!class_exists('apt\thewhale\the_whale_framework')) {
	$class_dir = $dir . '/the-whale/lib/*.php';

	foreach (glob($class_dir) as $filename) {
		include_once $filename;
	}
}

//Include specific classes
$class_dir = $dir . '/infrastructure/*/*/*.php';

foreach (glob($class_dir) as $filename) {
	include_once $filename;
}
