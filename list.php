<?php

// Check if we are a user
OCP\User::checkLoggedIn();

$tmpl = new OCP\Template('dropit', 'drop', '');
\OCP\Util::addScript('dropit', 'dist/dropzone');
\OCP\Util::addScript('dropit', 'drop');
\OCP\Util::addStyle('dropit', 'drop');
$tmpl->printPage();
