<?php
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
defined('TYPO3_MODE') or die();

ExtensionManagementUtility::addStaticFile('t3sbootstrap', 'Configuration/TypoScript', 'Bootstrap Components');
