<?php
/**
 * 2018 Touchize Sweden AB.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to magento@touchize.com so we can send you a copy immediately.
 *
 * @author    Touchize Sweden AB <magento@touchize.com>
 * @copyright 2018 Touchize Sweden AB
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of Touchize Sweden AB
 */

namespace Touchize\Commerce\Plugin\Result;

use Magento\Framework;
use Magento\Framework\View;
use Magento\Framework\App\Response\HttpInterface as HttpResponseInterface;

class Page extends \Magento\Framework\View\Result\Page
{
    const TOUCHIZE_ROOT_TEMPLATE = 'Touchize_Commerce::root.phtml';

    public function __construct(
        View\Element\Template\Context $context,
        View\LayoutFactory $layoutFactory,
        View\Layout\ReaderPool $layoutReaderPool,
        Framework\Translate\InlineInterface $translateInline,
        View\Layout\BuilderFactory $layoutBuilderFactory,
        View\Layout\GeneratorPool $generatorPool,
        View\Page\Config\RendererFactory $pageConfigRendererFactory,
        View\Page\Layout\Reader $pageLayoutReader,
        $template,
        $isIsolated = false,
        View\EntitySpecificHandlesList $entitySpecificHandlesList = null,
        \Touchize\Commerce\Helper\Data $touchizeHelper
    ) {
       parent::__construct($context, $layoutFactory, $layoutReaderPool, $translateInline, $layoutBuilderFactory, $generatorPool, $pageConfigRendererFactory, $pageLayoutReader, $template, $isIsolated, $entitySpecificHandlesList);
       $this->touchizeHelper = $touchizeHelper;
       if ($this->touchizeHelper->isAllowedToView()) {
           $this->template = self::TOUCHIZE_ROOT_TEMPLATE;
       }
   }

    /**
     * {@inheritdoc}
     */
    protected function render(HttpResponseInterface $response)
    {
        $this->pageConfig->publicBuild();
        if ($this->touchizeHelper->isAllowedToView() && $this->getPageLayout()) {
            $config = $this->getConfig();
            $this->addDefaultBodyClasses();
            $addBlock = $this->getLayout()->getBlock('head.additional');
            $headContent = $this->getHeadContent();
            $requireJs = $this->getLayout()->getBlock('require.js');
            $this->assign([
                'requireJs' => $requireJs ? $requireJs->toHtml() : null,
                'touchizeHeadContent'    => $headContent,
                'headContent' => $headContent,
                'headAdditional' => $addBlock ? $addBlock->toHtml() : null,
                'htmlAttributes' => $this->pageConfigRenderer->renderElementAttributes($config::ELEMENT_TYPE_HTML),
                'headAttributes' => $this->pageConfigRenderer->renderElementAttributes($config::ELEMENT_TYPE_HEAD),
                'bodyAttributes' => $this->pageConfigRenderer->renderElementAttributes($config::ELEMENT_TYPE_BODY),
                'loaderIcon'     => $this->getViewFileUrl('images/loader-2.gif'),
            ]);

            $this->getLayout()->unsetElement('search_result_list');

            $output = $this->getLayout()->getOutput();

            $this->assign('layoutContent', $output);
            $output = $this->renderPage();
            $this->translateInline->processResponseBody($output);
            $response->appendBody($output);
            return $this;
        }

        return parent::render($response);
    }

    protected function getHeadContent()
    {
        $headContent = '';
        $headContent .= $this->pageConfigRenderer->renderMetadata();
        $headContent .= $this->pageConfigRenderer->renderTitle();
        $this->pageConfigRenderer->prepareFavicon();
        return $headContent;
    }

}