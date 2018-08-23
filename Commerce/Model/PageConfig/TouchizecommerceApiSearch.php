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

namespace Touchize\Commerce\Model\PageConfig;

use Touchize\Commerce\Model\PageConfig\CatalogProductView;
use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Resolver;
use Touchize\Commerce\Helper\Data as TouchizeData;

class TouchizecommerceApiSearch extends NoConfig
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected $_collection;

    /**
     * @var Resolver
     */
    protected $layerResolver;

    /**
     * @var \Touchize\Commerce\Helper\Product
     */
    protected $productHelper;

    public function __construct(
        Context $context,
        \Magento\Framework\Registry $registry,
        \Touchize\Commerce\Helper\Config $configHelper,
        \Touchize\Commerce\Helper\Product $productHelper,
        Resolver $layerResolver
    ) {
        parent::__construct($context, $registry, $configHelper);
        $this->layerResolver = $layerResolver;
        $this->productHelper = $productHelper;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $searchData = [];
        $searchData['SearchResult']['Result'] = $this->getSearchResults();
        $searchData['SearchResult']['Sorting'] = array (
            'Directions' =>
                array (
                    'Available' =>
                        array (
                            0 =>
                                array (
                                    'Value' => 'desc',
                                    'Title' => 'Set Descending Direction',
                                ),
                            1 =>
                                array (
                                    'Value' => 'asc',
                                    'Title' => 'Set Ascending Direction',
                                ),
                        ),
                    'Current' => 'desc',
                    'Default' => 'desc',
                    'SQ' => 'dir',
                ),
            'Orders' =>
                array (
                    'Available' =>
                        array (
                            0 =>
                                array (
                                    'Value' => 'relevance',
                                    'Title' => 'Relevance',
                                ),
                            1 =>
                                array (
                                    'Value' => 'name',
                                    'Title' => 'Name',
                                ),
                            2 =>
                                array (
                                    'Value' => 'price',
                                    'Title' => 'Price',
                                ),
                        ),
                    'Current' => 'relevance',
                    'Default' => 'relevance',
                    'SQ' => 'order',
                ),
        );
        $searchData['SearchResult']['Filters'] = NULL;
        $searchData['SearchResult']['Url'] = $this->_configHelper->getQueryUrl();

        return $searchData;
    }

    public function getSearchResults()
    {
        $searchResults = [];
        $collection = $this->getProductCollection();
        $searchResults['Count'] = $collection->count();
        $searchResults['List'] = $this->getProductListData($collection);
        $searchResults['SQ'] = TouchizeData::SEARCH_QUERY_PARAM;
        $searchResults['Title'] = sprintf('Search results for %s', $this->getQueryString());
        $searchResults['Msg'] = __('Search message');

        return $searchResults;
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getProductCollection()
    {
        if (is_null($this->_collection)) {
            $this->layerResolver->create(Resolver::CATALOG_LAYER_SEARCH);
            $layer = $this->getLayer();
            $this->_collection = $layer->getProductCollection();
        }
        return $this->_collection;
    }

    /**
     * @param $collection
     *
     * @return array
     */
    public function getProductListData($collection)
    {
        return $this->productHelper->getAdaptedProductList($collection);
    }

    /**
     * Get catalog layer model
     *
     * @return Layer
     */
    public function getLayer()
    {
        return $this->layerResolver->get();
    }
}

