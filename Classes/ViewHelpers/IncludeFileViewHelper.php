<?php

namespace T3SBS\T3sbootstrap\ViewHelpers;


use TYPO3\CMS\Core\Http\ApplicationType;
/*
 * This file is part of the TYPO3 extension t3sbootstrap.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;
use TYPO3\CMS\Frontend\Resource\FilePathSanitizer;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;


/**
 * ViewHelper to include a css/js file
 *
 * # Example: Basic example
 * <code>
 * <t3sbs:includeFile path="{settings.cssFile}" />
 * </code>
 * <output>
 * This will include the file provided by {settings} in the header
 * </output>
 *
 */
class IncludeFileViewHelper extends AbstractViewHelper
{
	use CompileWithRenderStatic;

	/**
	 */
	public function initializeArguments()
	{
		$this->registerArgument('path', 'string', 'Path to the CSS/JS file which should be included', true);
		$this->registerArgument('compress', 'bool', 'Define if file should be compressed', false, false);
	}

	/**
	 * @param array $arguments
	 * @param \Closure $renderChildrenClosure
	 * @param RenderingContextInterface $renderingContext
	 */
	public static function renderStatic(
		array $arguments,
		\Closure $renderChildrenClosure,
		RenderingContextInterface $renderingContext
	) {

		$path = $arguments['path'];
		$compress = (bool)$arguments['compress'];
		$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
		if (ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isFrontend()) {
			$path = GeneralUtility::makeInstance(FilePathSanitizer::class)->sanitize($path);

			// JS
			if (strtolower(substr($path, -3)) === '.js') {
				$pageRenderer->addJsFooterFile($path, null, $compress, false, '', true);

			// CSS
			} elseif (strtolower(substr($path, -4)) === '.css') {
				$pageRenderer->addCssFile($path, 'stylesheet', 'all', '', $compress, false, '', true);
			}
		} else {
			// JS
			if (strtolower(substr($path, -3)) === '.js') {
				$pageRenderer->addJsFooterFile($path, null, $compress, false, '', true);

			// CSS
			} elseif (strtolower(substr($path, -4)) === '.css') {
				$pageRenderer->addCssFile($path, 'stylesheet', 'all', '', $compress, false, '', true);
			}
		}
	}
}
