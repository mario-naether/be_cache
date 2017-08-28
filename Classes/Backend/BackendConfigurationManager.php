<?php
namespace MN\BeCache\Backend;


use TYPO3\CMS\Core\Utility\GeneralUtility;

class BackendConfigurationManager extends \TYPO3\CMS\Extbase\Configuration\BackendConfigurationManager
{
    /**
     * @return mixed
     */
    public function getTypoScriptSetup()
    {

        $pageId = $this->getCurrentPageId();

        $cacheId = sha1($pageId . __CLASS__);

        /* @var $cache \TYPO3\CMS\Core\Cache\Frontend\FrontendInterface */
        $cache = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Cache\CacheManager::class)->getCache('be_cache');

        if($cachedTS = $cache->get($cacheId)){
            $this->typoScriptSetupCache[$pageId] = $cachedTS;
        }

        if (!array_key_exists($pageId, $this->typoScriptSetupCache)) {

            /** @var $template \TYPO3\CMS\Core\TypoScript\TemplateService */
            $template = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\TypoScript\TemplateService::class);
            // do not log time-performance information
            $template->tt_track = 0;
            // Explicitly trigger processing of extension static files
            $template->setProcessExtensionStatics(true);
            $template->init();
            // Get the root line
            $rootline = [];
            if ($pageId > 0) {
                /** @var $sysPage \TYPO3\CMS\Frontend\Page\PageRepository */
                $sysPage = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Frontend\Page\PageRepository::class);
                // Get the rootline for the current page
                $rootline = $sysPage->getRootLine($pageId, '', true);
            }
            // This generates the constants/config + hierarchy info for the template.
            $template->runThroughTemplates($rootline, 0);
            $template->generateConfig();


            $this->typoScriptSetupCache[$pageId] = $template->setup;


            $cache->set($cacheId, $this->typoScriptSetupCache[$pageId]);
        }
        #debug(\PHP_Timer::secondsToTimeString(\PHP_Timer::stop()).__METHOD__);
        return $this->typoScriptSetupCache[$pageId];
    }
}