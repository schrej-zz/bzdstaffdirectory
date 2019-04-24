<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

// get extension confArr
$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['bzdstaffdirectory']);

// l10n_mode for text fields
$l10n_mode = ($confArr['l10n_mode_prefixLangTitle']?'prefixLangTitle':'');

// l10n_mode for text fields that probably won't be translated (like the name, phone number and so on)
$l10n_mode_merge = '';//($confArr['l10n_mode_prefixLangTitle']?'mergeIfNotBlank':'');

// l10n_mode for the image field
$l10n_mode_image = ($confArr['l10n_mode_imageExclude']?'exclude':'mergeIfNotBlank');

// hide new localizations
$hideNewLocalizations = ($confArr['hideNewLocalizations']?'mergeIfNotBlank':'');

?>
