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

namespace Touchize\Commerce\Helper;

use Magento\Framework\App\Helper\Context;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var array
     */
    protected $_config = [];

    /**
     * @var \Touchize\Commerce\Model\PageConfigFactory
     */
    protected $pageConfigFactory;

    public function __construct(
        Context $context,
        \Touchize\Commerce\Model\PageConfigFactory $pageConfigFactory
    ) {
        parent::__construct($context);
        $this->pageConfigFactory = $pageConfigFactory;
    }


    /**
     * @return array
     */
    public function getActiveConfig()
    {
        if (empty($this->_config)) {
            $this->generate();
        }

        return $this->_config;
    }

    public function generate()
    {
        $this->generateBases()->fillRouter()->fillEndpoints();
        $this->fillMainMenu()->fillTopMenu()->fillActiveProducts();
        return $this;
    }

    /**
     * @return $this
     */
    protected function generateBases()
    {
        $this->_config['Debug' ] = false;
        $this->_config['ApiServer' ] = $this->_urlBuilder->getUrl();
        $this->_config['MediaServer' ] = $this->_urlBuilder->getUrl('media/touchizecommerce');
        $this->_config['MediaPath' ] = '/';

        return $this;
    }

    /**
     * @return $this
     */
    protected function fillEndpoints()
    {
        $this->_config['Endpoints'] = [
            'Products' =>
                array (
                    'Endpoint' => 'touchizecommerce/api/productlist',
                ),
            'ProductDetails' =>
                array (
                    'Endpoint' => 'touchizecommerce/product',
                ),
            'Campaigns' =>
                array (
                    'Endpoint' => 'touchizecommerce/api/touchmap',
                ),
            'AutoSearch' =>
                array (
                    'Endpoint' => 'touchizecommerce/search/autocomplete',
                ),
            'Cart' =>
                array (
                    'Endpoint' => 'touchizecommerce/cart',
                ),
            'Selectors' =>
                array (
                    'Endpoint' => 'touchizecommerce/selectors',
                ),
            'Taxonomies' =>
                array (
                    'Endpoint' => 'touchizecommerce/taxonomy',
                ),
        ];

        return $this;
    }

    /**
     * @return $this
     */
    protected function fillRouter()
    {
        $this->_config['Router'] = [
            'SiteUrl' => $this->_urlBuilder->getUrl(),
            'qs' => '',
            'tid' => '8',
            'pid' => NULL,
            'page' => NULL,
            'search' => NULL,
        ];
        return $this;
    }

    protected function fillMainMenu()
    {
        $this->_config['MainMenu'] = [];
        return $this;
    }

    protected function fillTopMenu()
    {
        $this->_config['StartupModules']['TaxonomyMenu'] = [
            'Params' =>
                array (
                    'Model' =>
                        array (
                            'Id' => '2',
                            'Tree' =>
                                array (
                                    0 =>
                                        array (
                                            'Id' => '4',
                                            'ParentId' => '2',
                                            'Name' => 'Women',
                                            'IsActive' => '1',
                                            'Position' => '2',
                                            'Level' => '2',
                                            'Url' => 'women.html',
                                            'SubTaxa' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'Id' => '10',
                                                            'ParentId' => '4',
                                                            'Name' => 'New Arrivals',
                                                            'IsActive' => '1',
                                                            'Position' => '1',
                                                            'Level' => '3',
                                                            'Url' => 'women/tops-women/hoodies-and-sweatshirts-women.html',
                                                            'SubTaxa' =>
                                                                array (
                                                                ),
                                                        ),
                                                    1 =>
                                                        array (
                                                            'Id' => '11',
                                                            'ParentId' => '4',
                                                            'Name' => 'Tops & Blouses',
                                                            'IsActive' => '1',
                                                            'Position' => '2',
                                                            'Level' => '3',
                                                            'Url' => 'women/tops-blouses.html',
                                                            'SubTaxa' =>
                                                                array (
                                                                ),
                                                        ),
                                                    2 =>
                                                        array (
                                                            'Id' => '12',
                                                            'ParentId' => '4',
                                                            'Name' => 'Pants & Denim',
                                                            'IsActive' => '1',
                                                            'Position' => '3',
                                                            'Level' => '3',
                                                            'Url' => 'women/pants-denim.html',
                                                            'SubTaxa' =>
                                                                array (
                                                                ),
                                                        ),
                                                    3 =>
                                                        array (
                                                            'Id' => '13',
                                                            'ParentId' => '4',
                                                            'Name' => 'Dresses & Skirts',
                                                            'IsActive' => '1',
                                                            'Position' => '4',
                                                            'Level' => '3',
                                                            'Url' => 'women/dresses-skirts.html',
                                                            'SubTaxa' =>
                                                                array (
                                                                ),
                                                        ),
                                                ),
                                        ),
                                    1 =>
                                        array (
                                            'Id' => '5',
                                            'ParentId' => '2',
                                            'Name' => 'Men',
                                            'IsActive' => '1',
                                            'Position' => '3',
                                            'Level' => '2',
                                            'Url' => 'men.html',
                                            'SubTaxa' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'Id' => '14',
                                                            'ParentId' => '5',
                                                            'Name' => 'New Arrivals',
                                                            'IsActive' => '1',
                                                            'Position' => '1',
                                                            'Level' => '3',
                                                            'Url' => 'men/new-arrivals.html',
                                                            'SubTaxa' =>
                                                                array (
                                                                ),
                                                        ),
                                                    1 =>
                                                        array (
                                                            'Id' => '15',
                                                            'ParentId' => '5',
                                                            'Name' => 'Shirts',
                                                            'IsActive' => '1',
                                                            'Position' => '2',
                                                            'Level' => '3',
                                                            'Url' => 'men/shirts.html',
                                                            'SubTaxa' =>
                                                                array (
                                                                ),
                                                        ),
                                                    2 =>
                                                        array (
                                                            'Id' => '16',
                                                            'ParentId' => '5',
                                                            'Name' => 'Tees, Knits and Polos',
                                                            'IsActive' => '1',
                                                            'Position' => '3',
                                                            'Level' => '3',
                                                            'Url' => 'men/tees-knits-and-polos.html',
                                                            'SubTaxa' =>
                                                                array (
                                                                ),
                                                        ),
                                                    3 =>
                                                        array (
                                                            'Id' => '17',
                                                            'ParentId' => '5',
                                                            'Name' => 'Pants & Denim',
                                                            'IsActive' => '1',
                                                            'Position' => '4',
                                                            'Level' => '3',
                                                            'Url' => 'men/pants-denim.html',
                                                            'SubTaxa' =>
                                                                array (
                                                                ),
                                                        ),
                                                    4 =>
                                                        array (
                                                            'Id' => '40',
                                                            'ParentId' => '5',
                                                            'Name' => 'Blazers',
                                                            'IsActive' => '1',
                                                            'Position' => '5',
                                                            'Level' => '3',
                                                            'Url' => 'men/blazers.html',
                                                            'SubTaxa' =>
                                                                array (
                                                                ),
                                                        ),
                                                ),
                                        ),
                                    2 =>
                                        array (
                                            'Id' => '6',
                                            'ParentId' => '2',
                                            'Name' => 'Accessories',
                                            'IsActive' => '1',
                                            'Position' => '4',
                                            'Level' => '2',
                                            'Url' => 'accessories.html',
                                            'SubTaxa' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'Id' => '18',
                                                            'ParentId' => '6',
                                                            'Name' => 'Eyewear',
                                                            'IsActive' => '1',
                                                            'Position' => '1',
                                                            'Level' => '3',
                                                            'Url' => 'accessories/eyewear.html',
                                                            'SubTaxa' =>
                                                                array (
                                                                ),
                                                        ),
                                                    1 =>
                                                        array (
                                                            'Id' => '19',
                                                            'ParentId' => '6',
                                                            'Name' => 'Jewelry',
                                                            'IsActive' => '1',
                                                            'Position' => '2',
                                                            'Level' => '3',
                                                            'Url' => 'accessories/jewelry.html',
                                                            'SubTaxa' =>
                                                                array (
                                                                ),
                                                        ),
                                                    2 =>
                                                        array (
                                                            'Id' => '20',
                                                            'ParentId' => '6',
                                                            'Name' => 'Shoes',
                                                            'IsActive' => '1',
                                                            'Position' => '3',
                                                            'Level' => '3',
                                                            'Url' => 'accessories/shoes.html',
                                                            'SubTaxa' =>
                                                                array (
                                                                ),
                                                        ),
                                                    3 =>
                                                        array (
                                                            'Id' => '21',
                                                            'ParentId' => '6',
                                                            'Name' => 'Bags & Luggage',
                                                            'IsActive' => '1',
                                                            'Position' => '4',
                                                            'Level' => '3',
                                                            'Url' => 'accessories/bags-luggage.html',
                                                            'SubTaxa' =>
                                                                array (
                                                                ),
                                                        ),
                                                ),
                                        ),
                                    3 =>
                                        array (
                                            'Id' => '7',
                                            'ParentId' => '2',
                                            'Name' => 'Home & Decor',
                                            'IsActive' => '1',
                                            'Position' => '5',
                                            'Level' => '2',
                                            'Url' => 'home-decor.html',
                                            'SubTaxa' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'Id' => '22',
                                                            'ParentId' => '7',
                                                            'Name' => 'Books & Music',
                                                            'IsActive' => '1',
                                                            'Position' => '1',
                                                            'Level' => '3',
                                                            'Url' => 'home-decor/books-music.html',
                                                            'SubTaxa' =>
                                                                array (
                                                                ),
                                                        ),
                                                    1 =>
                                                        array (
                                                            'Id' => '23',
                                                            'ParentId' => '7',
                                                            'Name' => 'Bed & Bath',
                                                            'IsActive' => '1',
                                                            'Position' => '2',
                                                            'Level' => '3',
                                                            'Url' => 'home-decor/bed-bath.html',
                                                            'SubTaxa' =>
                                                                array (
                                                                ),
                                                        ),
                                                    2 =>
                                                        array (
                                                            'Id' => '24',
                                                            'ParentId' => '7',
                                                            'Name' => 'Electronics',
                                                            'IsActive' => '1',
                                                            'Position' => '3',
                                                            'Level' => '3',
                                                            'Url' => 'home-decor/electronics.html',
                                                            'SubTaxa' =>
                                                                array (
                                                                ),
                                                        ),
                                                    3 =>
                                                        array (
                                                            'Id' => '25',
                                                            'ParentId' => '7',
                                                            'Name' => 'Decorative Accents',
                                                            'IsActive' => '1',
                                                            'Position' => '4',
                                                            'Level' => '3',
                                                            'Url' => 'home-decor/decorative-accents.html',
                                                            'SubTaxa' =>
                                                                array (
                                                                ),
                                                        ),
                                                ),
                                        ),
                                    4 =>
                                        array (
                                            'Id' => '8',
                                            'ParentId' => '2',
                                            'Name' => 'Sale',
                                            'IsActive' => '1',
                                            'Position' => '6',
                                            'Level' => '2',
                                            'Url' => 'sale.html',
                                            'SubTaxa' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'Id' => '26',
                                                            'ParentId' => '8',
                                                            'Name' => 'Women',
                                                            'IsActive' => '1',
                                                            'Position' => '1',
                                                            'Level' => '3',
                                                            'Url' => 'sale/women.html',
                                                            'SubTaxa' =>
                                                                array (
                                                                ),
                                                        ),
                                                    1 =>
                                                        array (
                                                            'Id' => '27',
                                                            'ParentId' => '8',
                                                            'Name' => 'Men',
                                                            'IsActive' => '1',
                                                            'Position' => '2',
                                                            'Level' => '3',
                                                            'Url' => 'sale/men.html',
                                                            'SubTaxa' =>
                                                                array (
                                                                ),
                                                        ),
                                                    2 =>
                                                        array (
                                                            'Id' => '28',
                                                            'ParentId' => '8',
                                                            'Name' => 'Accessories',
                                                            'IsActive' => '1',
                                                            'Position' => '3',
                                                            'Level' => '3',
                                                            'Url' => 'sale/accessories.html',
                                                            'SubTaxa' =>
                                                                array (
                                                                ),
                                                        ),
                                                    3 =>
                                                        array (
                                                            'Id' => '29',
                                                            'ParentId' => '8',
                                                            'Name' => 'Home & Decor',
                                                            'IsActive' => '1',
                                                            'Position' => '4',
                                                            'Level' => '3',
                                                            'Url' => 'sale/home-decor.html',
                                                            'SubTaxa' =>
                                                                array (
                                                                ),
                                                        ),
                                                ),
                                        ),
                                    5 =>
                                        array (
                                            'Id' => '9',
                                            'ParentId' => '2',
                                            'Name' => 'VIP',
                                            'IsActive' => '1',
                                            'Position' => '7',
                                            'Level' => '2',
                                            'Url' => 'vip.html',
                                            'SubTaxa' =>
                                                array (
                                                ),
                                        ),
                                ),
                        ),
                ),
        ];
        return $this;
    }

    /**
     * @return $this
     */
    protected function fillActiveProducts()
    {
        $actionName = $this->getActionName();
        $configModel = $this->pageConfigFactory->getConfigModel($actionName);
        $params = $configModel->getConfig();
        $this->_config['StartupModules']['Content']['Params']['Template']['Blocks'][] = [
            'Module' => 'ProductList',
            'Params' => ['Model' => $params]
        ] ;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getActionName()
    {
        return $this->_getRequest()->getFullActionName();
    }
}