<a href="<?= current_url() ?>" target="_blank" style="cursor:zoom-in;">
<?php
// Vary tag by image type
switch (pathinfo($filename, PATHINFO_EXTENSION)):
	case 'pdf':
?>
	<object data="data:application/pdf;base64, <?= $data ?>" type="application/pdf"></object>
<?php
	break;
	
	default:
?>
	<img src="data:<?= $mime ?>;base64, <?= $data ?>" alt="<?= $filename ?>">
<?php
endswitch;
?>
</a>
