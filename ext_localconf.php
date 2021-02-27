<?php
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconProvider\FontawesomeIconProvider;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use T3SBS\T3sbootstrap\Controller\ConsentController;
use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;
use T3SBS\T3sbootstrap\Hooks\FlexFormHook;
use T3SBS\T3sbootstrap\Backend\FormDataProvider\FlexFormManipulation;
use TYPO3\CMS\Backend\Form\FormDataProvider\TcaFlexPrepare;
use TYPO3\CMS\Backend\Form\FormDataProvider\TcaFlexProcess;
use TYPO3\CMS\Core\Core\Environment;
use T3SBS\T3sbootstrap\Hooks\PageLayoutView\CardPreviewRenderer;
use T3SBS\T3sbootstrap\Updates\t3sbConstantsUpdateWizard;
use T3SBS\T3sbootstrap\Updates\t3sbGridUpdateWizard;
defined('TYPO3_MODE') or die();

call_user_func(function () {

	/***************
	 * Register Icons
	 */
	if (TYPO3_MODE === 'BE' || ExtensionManagementUtility::isLoaded('frontend_editing')) {
		$iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
		$icons = ['bs-card', 'bs-button', 'bs-carousel', 'ge-2_col', 'ge-3_col', 'ge-4_col', 'ge-card-container', 'ge-background_wrapper', 'ge-parallax_wrapper', 'ge-carousel-container', 'ge-accordion-container', 'ge-accordion-element', 'ge-modal', 'ge-tab-container', 'ge-tab-element', 'bs-fluidtemplate', 'bs-gallery'];
		foreach ($icons as $icon) {
			$iconRegistry->registerIcon(
				$icon,
				SvgIconProvider::class,
				['source' => 'EXT:t3sbootstrap/Resources/Public/Icons/Register/'.$icon.'.svg']
			);
		}
		// FontawesomeIconProvider
		$iconRegistry->registerIcon(
			'buttongroup',
			FontawesomeIconProvider::class,
			['name' => 'bars']
		);
		unset($iconRegistry);
	}

	/***************
	 * TsConfig
	 */
	 # Page
	ExtensionManagementUtility::addPageTSConfig("@import 'EXT:t3sbootstrap/Configuration/TSConfig/Page.tsconfig'");
	# CKEditor
	ExtensionManagementUtility::addPageTSConfig("@import 'EXT:t3sbootstrap/Configuration/TSConfig/CKEditor.tsconfig'");

	/***************
	 * Default Constants
	 */
	ExtensionManagementUtility::addTypoScriptConstants('bootstrap.ext.form.ajax = 0');
	ExtensionManagementUtility::addTypoScriptConstants('bootstrap.ext.typoscriptRendering = 0');
	ExtensionManagementUtility::addTypoScriptConstants('bootstrap.ext.indexedsearch = 0');
	ExtensionManagementUtility::addTypoScriptConstants('bootstrap.ext.news = 0');
	ExtensionManagementUtility::addTypoScriptConstants('bootstrap.ext.wsScss = 0');
	ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.codesnippet = 0');
	ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.imgCopyright = 0');
	ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.linkHoverEffect = 0');
	ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.fontawesomepagetitle = 0');
	ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.fontawesomeCss = 0');
	ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.cookieconsent = 0');
	ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.lazyLoad = 0');
	ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.animateCss = 0');
	ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.container = 0');
	ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.spacing = 0');
	ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.color = 0');
	ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.cTypeClass = 0');
	ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.customScss = 0');
	ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.customScssPath = 0');
	ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.editScss = 0');
	ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.expandedContent = 0');
	ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.bootswatch = 0');
	ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.rollyourown = 0');
	ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.customSectionOrder = 0');
	ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.fixedButton = 0');
	ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.webp = 0');

	/***************
	 * Extension configuration
	 */
	$extconf = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('t3sbootstrap');

	/***************
	 * Other Extensions
	 */
	# if typoscript_rendering is loaded
	if ( ExtensionManagementUtility::isLoaded('typoscript_rendering') ) {

		/***************
		 * plugin content consent
		 */
		ExtensionUtility::configurePlugin(
			'T3sbootstrap',
			'Pi1',
			[
				ConsentController::class => 'index, ajax',
			],
			// non-cacheable actions
			[
				ConsentController::class => 'ajax',
			]
		);
		ExtensionManagementUtility::addTypoScriptConstants('bootstrap.ext.typoscriptRendering = 1');
	}
	# if indexed_search is loaded
	if ( ExtensionManagementUtility::isLoaded('indexed_search') ) {
		 # Setup
		ExtensionManagementUtility::addTypoScript('t3sbootstrap',
			'setup','<INCLUDE_TYPOSCRIPT: source="FILE:EXT:t3sbootstrap/Resources/Private/Extensions/indexed_search/Configuration/TypoScript/setup.typoscript">','defaultContentRendering'
		);
		ExtensionManagementUtility::addTypoScriptConstants('bootstrap.ext.indexedsearch = 1');
	}
	 # if news is loaded
	if ( ExtensionManagementUtility::isLoaded('news') && array_key_exists('extNews', $extconf) && $extconf['extNews'] === '1' ) {
	 	# TsConfig
	 	ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:t3sbootstrap/Resources/Private/Extensions/news/Configuration/TSconfig/templateLayouts.tsconfig">');
		ExtensionManagementUtility::addTypoScript('t3sbootstrap',
				 'setup','<INCLUDE_TYPOSCRIPT: source="FILE:EXT:t3sbootstrap/Resources/Private/Extensions/news/Configuration/TypoScript/setup.typoscript">','defaultContentRendering'
		);
		ExtensionManagementUtility::addTypoScriptConstants('bootstrap.ext.news = 1');
	}
	// Optional flexform extend
	if (array_key_exists('flexformExtend', $extconf) && $extconf['flexformExtend'] === '1') {
		$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][FlexFormTools::class]['flexParsing'][]
		 = FlexFormHook::class;

		// Optional "flexform path"
		if (array_key_exists('flexformPath', $extconf)) {
			ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.flexformPath = '.$extconf['flexformPath']);
		}
	}
	// Optional modify flexform fields
	if (array_key_exists('flexformModify', $extconf) && $extconf['flexformModify'] === '1') {
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][FlexFormManipulation::class] = [
				 'depends' => [
				  TcaFlexPrepare::class,
				 ],
				 'before' => [
				  TcaFlexProcess::class,
				 ],
		];
	}
	// Optional custom translations
	if (array_key_exists('customTranslations', $extconf) && $extconf['customTranslations'] === '1') {
		$extPath = 'EXT:t3sbootstrap/Resources/Private/Language/';
		if (array_key_exists('customTranslationsPath', $extconf)) {
			$ctPath = $extconf['customTranslationsPath'];
		} else {
			$ctPath = 'fileadmin/T3SB/Language/';
		}
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride'][$extPath . 'locallang.xlf'][] = Environment::getPublicPath() . $ctPath . 'locallang.xlf';
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride'][$extPath . 'locallang_m1.xlf'][] = Environment::getPublicPath() . $ctPath . 'locallang_m1.xlf';
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride'][$extPath . 'locallang_db.xlf'][] = Environment::getPublicPath() . $ctPath . 'locallang_db.xlf';
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride'][$extPath . 'locallang_be.xlf'][] = Environment::getPublicPath() . $ctPath . 'locallang_be.xlf';
	}
	// Optional CKEditor plugin "Code Snippet"
	if (array_key_exists('codesnippet', $extconf) && $extconf['codesnippet'] === '1') {
		ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.codesnippet = 1');
		// if rte_ckeditor_fontawesome is loaded
		if ( ExtensionManagementUtility::isLoaded('rte_ckeditor_fontawesome') ) {
			if (array_key_exists('fontawesomeCss', $extconf) && $extconf['fontawesomeCss'] === '2') {
				$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['t3sbootstrap'] = 'EXT:t3sbootstrap/Configuration/RTE/CodesnippetFaPro.yaml';
			} else {
				$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['t3sbootstrap'] = 'EXT:t3sbootstrap/Configuration/RTE/CodesnippetFa.yaml';
			}
		} else {
			$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['t3sbootstrap'] = 'EXT:t3sbootstrap/Configuration/RTE/Codesnippet.yaml';
		}
	} else {
		// if rte_ckeditor_fontawesome is loaded
		if ( ExtensionManagementUtility::isLoaded('rte_ckeditor_fontawesome') ) {
			if (array_key_exists('fontawesomeCss', $extconf) && $extconf['fontawesomeCss'] === '2') {
				$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['t3sbootstrap'] = 'EXT:t3sbootstrap/Configuration/RTE/DefaultFaPro.yaml';
			} else {
				$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['t3sbootstrap'] = 'EXT:t3sbootstrap/Configuration/RTE/DefaultFa.yaml';
			}
		} else {
			$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['t3sbootstrap'] = 'EXT:t3sbootstrap/Configuration/RTE/Default.yaml';
		}
	}
	// Optional Hover Link Effect (FAL)
	if (array_key_exists('linkHoverEffect', $extconf) && $extconf['linkHoverEffect'] === '1') {
		ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.linkHoverEffect = 1');
	}
	// Optional Copyright notice (FAL)
	if (array_key_exists('imgCopyright', $extconf) && $extconf['imgCopyright'] === '1') {
		ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.imgCopyright = 1');
	} elseif (array_key_exists('imgCopyright', $extconf) && $extconf['imgCopyright'] === '2') {
		ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.imgCopyright = 2');
	}
	// Optional fontawesomepagetitle
	if (array_key_exists('fontawesomepagetitle', $extconf) && $extconf['fontawesomepagetitle'] === '1') {
		ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.fontawesomepagetitle = 1');
	}
	// Optional fontawesomeCss
	if (array_key_exists('fontawesomeCss', $extconf) && $extconf['fontawesomeCss'] === '1') {
		ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.fontawesomeCss = 1');
	} elseif (array_key_exists('fontawesomeCss', $extconf) && $extconf['fontawesomeCss'] === '2') {
		ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.fontawesomeCss = 2');
	}
	// Optional cookieconsent
	if (array_key_exists('cookieconsent', $extconf) && $extconf['cookieconsent'] === '1') {
		ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.cookieconsent = 1');
	}
	// Optional lazyLoad
	if (array_key_exists('lazyLoad', $extconf) && $extconf['lazyLoad'] === '1') {
		ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.lazyLoad = 1');
	} elseif (array_key_exists('lazyLoad', $extconf) && $extconf['lazyLoad'] === '2') {
		ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.lazyLoad = 2');
	}
	// Optional animateCss
	if (array_key_exists('animateCss', $extconf) && $extconf['animateCss'] > '0') {
		ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.animateCss = '.$extconf['animateCss']);
	}
	// Optional select-field for a .container or .container-fluid class in any content element
	if (array_key_exists('container', $extconf) && $extconf['container'] === '1') {
		ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.container = 1');
	}
	// Optional select-fields for margin and padding in any content element
	if (array_key_exists('spacing', $extconf) && $extconf['spacing'] === '1') {
		ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.spacing = 1');
	}
	// Optional "Bootstrap color palette"
	if (array_key_exists('color', $extconf) && $extconf['color'] === '1') {
		ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.color = 1');
	}
	// Optional "cType in class"
	if (array_key_exists('cTypeClass', $extconf) && $extconf['cTypeClass'] === '1') {
		ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.cTypeClass = 1');
	}
	// Optional "rollyourown"
	if (array_key_exists('rollyourown', $extconf) && $extconf['rollyourown'] === '1') {
		ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.rollyourown = 1');
	}
	// Optional "custom scss" if ws_scss is loaded
	if ( ExtensionManagementUtility::isLoaded('ws_scss') ) {
		ExtensionManagementUtility::addTypoScriptConstants('bootstrap.ext.wsScss = 1');
		if (array_key_exists('customScss', $extconf) && $extconf['customScss'] === '1') {
			ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.customScss = 1');
			// Optional "bootswatch theme"
			if (array_key_exists('bootswatch', $extconf) && $extconf['bootswatch'] !== 'none') {
				ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.bootswatch = '.$extconf['bootswatch']);
			}
			// Edit in BE
			if (array_key_exists('editScss', $extconf) && $extconf['editScss'] === '1') {
				ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.editScss = 1');
			}
			// Optional "custom scss path"
			if (array_key_exists('customScssPath', $extconf)) {
				ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.customScssPath = '.$extconf['customScssPath']);
			}
		}
	}
	// Optional "expanded content"
	if (array_key_exists('expandedContent', $extconf) && $extconf['expandedContent'] === '1') {
		# expanded content on top and bottom
		ExtensionManagementUtility::addPageTSConfig("@import 'EXT:t3sbootstrap/Configuration/TSConfig/BackendLayouts/Expanded/_main.tsconfig'");
		ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.expandedContent = 1');
	} else {
		ExtensionManagementUtility::addPageTSConfig("@import 'EXT:t3sbootstrap/Configuration/TSConfig/BackendLayouts/Default/_main.tsconfig'");
	}
	// Optional "custom section menu order"
	if (array_key_exists('sectionOrder', $extconf) && $extconf['sectionOrder'] === '1') {
		ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.sectionOrder = tx_t3sbootstrap_sectionOrder');
	} else {
		ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.sectionOrder = sorting');
	}
	// Optional "fixed button on left or right browser edge"
	if (array_key_exists('fixedButton', $extconf) && $extconf['fixedButton'] === '1') {
		ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.fixedButton = 1');
	} else {
		ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.fixedButton = 0');
	}
	# if webp is loaded
	if ( ExtensionManagementUtility::isLoaded('webp') ) {
		ExtensionManagementUtility::addTypoScriptConstants('bootstrap.extconf.webp = 1');
	}

	/***************
	 * Show preview of tt_content elements in page module
	 */
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem']['t3sbs_card'] =
	 CardPreviewRenderer::class;

	/***************
	 * Add RootLine Fields: keywords & description
	 */
	$rootlinefields = &$GLOBALS["TYPO3_CONF_VARS"]["FE"]["addRootLineFields"];
	if($rootlinefields != '') $rootlinefields .= ' , ';
	$rootlinefields .= 'keywords,description';

	/***************
	 * Registering wizards
	 */
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['t3sbConstantsUpdateWizard']
	= t3sbConstantsUpdateWizard::class;

	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['t3sbGridUpdateWizard']
	= t3sbGridUpdateWizard::class;

});
