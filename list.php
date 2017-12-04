<?php

// Check if we are a user
OCP\User::checkLoggedIn();

$tmpl = new OCP\Template('nextdrop', 'drop', '');
\OCP\Util::addScript('nextdrop', 'dist/dropzone');
\OCP\Util::addScript('nextdrop', 'drop');
\OCP\Util::addStyle('nextdrop', 'drop');
$tmpl->printPage();
