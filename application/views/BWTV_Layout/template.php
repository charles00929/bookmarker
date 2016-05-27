<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html>
<html>
	<head>
		<title>Bookmark Tagger</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<!-- 載入CSS,JS -->

		<?php foreach($css_files as $css):?>
		<link rel="stylesheet" type="text/css" href="<?=base_url($css)?>"/>
		<?php endforeach;?>
	</head>
	<body>
		<?php if(!empty($header)):?><header><?=$header?></header><?php endif;?>
		<?php if(!empty($menu)):?><menu><?=$menu?></menu><?php endif;?>
		<?php if(!empty($L_side)):?><aside class='left_side'><?=$L_side?></aside><?php endif;?>
		<?php if(!empty($main)):?><main><?=$main?></main><?php endif;?>
		<?php if(!empty($R_side)):?><aside><?=$R_side?></aside><?php endif;?>
		<?php if(!empty($footer)):?><footer><?=$footer?></footer><?php endif;?>
	</body>
		<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
		<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js" integrity="sha256-xNjb53/rY+WmG+4L6tTl9m6PpqknWZvRt0rO1SRnJzw=" crossorigin="anonymous"></script>

		<?php foreach($js_files as $js) :?>
		<script type='text/javascript' src="<?=base_url($js)?>"></script>
		<?php endforeach;?>

		
</html>