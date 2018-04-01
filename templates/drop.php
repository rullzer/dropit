<?php
/** @var $l \OCP\IL10N */
/** @var $_ array */
?>
<div class="drop-area dz-clickable">
	<div class="icon-download"></div>
	<p><?php p($l->t('Drop your file here to generate a link')); ?></p>
</div>
<div class="drop-text">
	<div class="hint">
		<div class="icon-filetype-text"></div>
		<p><?php p($l->t('Write here what you want to drop')); ?></p>
	</div>
	<textarea class="text-area" placeholder="<?php p($l->t('')); ?>"></textarea>
	<button class="text-submit"><?php p($l->t('Drop text')); ?></button>
</div>
<div class="url-share">
	<label><?php p($l->t('Link')); ?></label>
	<input id="url-drop" />
	<span class="copyButton icon-clippy" data-clipboard-target="#url-drop" title="<?php p($l->t('Copy')); ?>"></span>
</div>
