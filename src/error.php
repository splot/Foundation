<?php
/**
 * Error handler page that is used by SplotFoundation's error handler.
 * 
 * @package SplotFoundation
 * @author Michał Dudek <michal@michaldudek.pl>
 * 
 * @copyright Copyright (c) 2013, Michał Dudek
 * @license MIT
 */?><html>
<head>
	<title>Splot Error</title>
</head>
<body>

	<h2>
		<?php if ($type == 'exception'): ?>
			Exception occured:
		<?php else: ?>
			Error occured:
		<?php endif; ?>
	</h2>
	<h1><?php echo $message; ?></h1>
	<h3><?php echo $file; ?></h3>
	<h3><?php if ($code) echo $code; ?> <?php echo $name; ?></h3>

	<h2>Trace:</h2>
	<ul id="trace">
		<?php $i = count($trace); 
		foreach ($trace as $item): ?>
			<li>
				<span class="number"><?php echo $i--; ?>.</span>
				<span class="function"><?php echo $item['function']; ?></span>
				<span class="file"><?php echo $item['file']; ?></span>
				<ul class="arguments">
					<?php foreach($item['arguments'] as $argument): ?>
						<li><?php echo \dump($argument, true); ?></li>
					<?php endforeach; ?>
				</ul>
			</li>
		<?php endforeach; ?>
	</ul>

	<h2>Log:</h2>
	<ul id="log">
		<?php $l = count($log);
		foreach ($log as $item): ?>
			<li>
				<span class="number"><?php echo $l--; ?>.</span>
				<span class="time"><?php echo $item['time']; ?></span>
				<span class="category"><?php echo $item['category']; ?></span>
				<span class="message"><?php echo $item['message']; ?></span>
				<p class="additional"><?php echo \dump($item['additional'], true); ?></p>
			</li>
		<?php endforeach; ?>
	</ul>

</body>
</html>