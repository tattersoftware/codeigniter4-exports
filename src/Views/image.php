<a href="<?= current_url() ?>" target="_blank" style="cursor: zoom-in;">
<?php if ($fileMime === 'application/pdf'): ?>
	<object data="data:application/pdf;base64, <?= $data ?>" type="application/pdf"></object>
<?php else: ?>
	<img src="data:<?= $fileMime ?>;base64, <?= $data ?>" alt="<?= $fileName ?>">
<?php endif; ?>
</a>
