<?php
defined('TYPO3_MODE') || die('Access denied.');


$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Extbase\Configuration\BackendConfigurationManager::class] = array(
    'className' => \MN\BeCache\Backend\BackendConfigurationManager::class,
);

if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['be_cache'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['be_cache'] = array(
        'backend' => \TYPO3\CMS\Core\Cache\Backend\SimpleFileBackend::class,

       #  'frontend' => \TYPO3\CMS\Core\Cache\Frontend\PhpFrontend::class,
        'groups' => ['system'],
        'options' => []
    );
}