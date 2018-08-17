<?php
/**
 * 2018 Touchize Sweden AB.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL":
 * http"://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to magento@touchize.com so we can send you a copy immediately.
 *
 * @author Touchize Sweden AB <magento@touchize.com>
 * @copyright 2018 Touchize Sweden AB
 * @licensehttp"://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of Touchize Sweden AB
 */

namespace Touchize\Commerce\Model\PageConfig;

use Magento\Framework\Locale\Bundle\CurrencyBundle as CurrencyBundle;
use Magento\Framework\View\Element\Template\Context;
use Magento\Directory\Helper\Data;

class TouchizecommerceApiSelectors extends NoConfig
{
    const ROUTE_PATH = 'directory/currency/switch';

    /**
     * @var \Magento\Directory\Model\CurrencyFactory
     */
    protected $_currencyFactory;

    /**
     * @var bool
     */
    protected $_storeInUrl;

    /**
     * @var \Touchize\Commerce\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * TouchizecommerceApiSelectors constructor.
     *
     * @param Context                                     $context
     * @param \Magento\Framework\Registry                 $registry
     * @param \Touchize\Commerce\Helper\Config            $configHelper
     * @param \Touchize\Commerce\Helper\Data              $dataHelper
     * @param \Magento\Directory\Model\CurrencyFactory    $currencyFactory
     * @param \Magento\Framework\Locale\ResolverInterface $localeResolver
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Registry $registry,
        \Touchize\Commerce\Helper\Config $configHelper,
        \Touchize\Commerce\Helper\Data $dataHelper,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Magento\Framework\Locale\ResolverInterface $localeResolver
    ) {
        parent::__construct($context, $registry, $configHelper);
        $this->dataHelper = $dataHelper;
        $this->_storeManager = $context->getStoreManager();
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_currencyFactory = $currencyFactory;
        $this->localeResolver = $localeResolver;
        $this->_urlBuilder = $context->getUrlBuilder();
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $selectorsData = [];
        $selectorsData['Stores'] = $this->getStoresData();
        $selectorsData['Currencies'] = $this->getCurrenciesData();
        $selectorsData['BackButtonTitle'] = __($this->dataHelper->getBackButtonTitle());

        return $selectorsData;
    }

    public function getStoresData()
    {
        $storeData = [];
        $stores = $this->getRawStores();
        $currentGroupId = $this->getCurrentGroupId();
        $storeId = $this->getCurrentStoreId();
        if (count($stores[$currentGroupId]) == 1) {
            return [];
        }
        foreach ($stores[$currentGroupId] as $_store) {
            $storeData [] = [
                'Url' => $_store->getHomeUrl(),
                'Name' => $_store->getName(),
                'Selected' => $storeId == $_store->getId(),
            ];
        }
        return $storeData;
    }

    /**
     * @return array
     */
    public function getCurrenciesData()
    {
        $currenciesData = [];
        $currencies = $this->getCurrencies();
        if (count($currencies) == 1) {
            return [];
        }
        $currentCode = $this->getCurrentCurrencyCode();
        foreach ($currencies as $code => $name) {
            $currenciesData [] = [
                'Url' => $this->getSwitchUrl($code),
                'Name' => $name,
                'Selected' => $currentCode == $code,
                'ISOCode' => $code,
            ];
        }
        return $currenciesData;
    }

    /**
     * @return array
     */
    public function getRawStores()
    {
        if (!$this->hasData('raw_stores')) {
            $websiteStores = $this->_storeManager->getWebsite()->getStores();
            $stores = [];
            foreach ($websiteStores as $store) {
                /* @var $store \Magento\Store\Model\Store */
                if (!$store->isActive()) {
                    continue;
                }
                $localeCode = $this->_scopeConfig->getValue(
                    Data::XML_PATH_DEFAULT_LOCALE,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $store
                );
                $store->setLocaleCode($localeCode);
                $params = ['_query' => []];
                if (!$this->isStoreInUrl()) {
                    $params['_query']['___store'] = $store->getCode();
                }
                $baseUrl = $store->getUrl('', $params);

                $store->setHomeUrl($baseUrl);
                $stores[$store->getGroupId()][$store->getId()] = $store;
            }
            $this->setData('raw_stores', $stores);
        }
        return $this->getData('raw_stores');
    }

    /**
     * @return array|mixed|null
     */
    public function getCurrencies()
    {
        $currencies = $this->getData('currencies');
        if ($currencies === null) {
            $currencies = [];
            $codes = $this->_storeManager->getStore()->getAvailableCurrencyCodes(true);
            if (is_array($codes) && count($codes) > 1) {
                $rates = $this->_currencyFactory->create()->getCurrencyRates(
                    $this->_storeManager->getStore()->getBaseCurrency(),
                    $codes
                );

                foreach ($codes as $code) {
                    if (isset($rates[$code])) {
                        $allCurrencies = (new CurrencyBundle())->get(
                            $this->localeResolver->getLocale()
                        )['Currencies'];
                        $currencies[$code] = $allCurrencies[$code][1] ?: $code;
                    }
                }
            }

            $this->setData('currencies', $currencies);
        }
        return $currencies;
    }

    /**
     * @return bool
     */
    public function isStoreInUrl()
    {
        if ($this->_storeInUrl === null) {
            $this->_storeInUrl = $this->_storeManager->getStore()->isUseStoreInUrl();
        }
        return $this->_storeInUrl;
    }

    /**
     * @return int
     */
    public function getCurrentGroupId()
    {
        return $this->_storeManager->getStore()->getGroupId();
    }

    /**
     * @return int
     */
    public function getCurrentStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * @return string
     */
    public function getCurrentCurrencyCode()
    {
        return $this->_storeManager->getStore()->getCurrentCurrencyCode();
    }

    /**
     * @param $code
     * @return string
     */
    public function getSwitchUrl($code)
    {
        return $this->_urlBuilder->getUrl(self::ROUTE_PATH, ['currency' => $code]);
    }
}

