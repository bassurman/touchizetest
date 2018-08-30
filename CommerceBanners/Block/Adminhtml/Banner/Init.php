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
 *  @author    Touchize Sweden AB <magento@touchize.com>
 *  @copyright 2018 Touchize Sweden AB
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of Touchize Sweden AB
 */

namespace Touchize\CommerceBanners\Block\Adminhtml\Banner;


use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Touchize\CommerceBanners\Api\BannerRepositoryInterface;

class Init extends Template
{
    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    protected $_backendUrl;

    /**
     * Init constructor.
     *
     * @param Context                             $context
     * @param \Magento\Backend\Model\UrlInterface $backendUrl
     * @param array                               $data
     */
    public function __construct(
        Template\Context $context,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        BannerRepositoryInterface $bannerRepository,
        \Magento\Framework\Data\Form\FormKey $formKey,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->_backendUrl = $backendUrl;
        $this->bannerRepository = $bannerRepository;
        $this->formKey = $formKey;
    }

    /**
     * @return string
     */
    public function getConfig()
    {
        $bannerModel = $this->getBannerModel();
        $ajaxData = ['form_key'=> $this->getFormKey()];
        $configData = array (
            'Debug' => true,
            'id' => $bannerModel->getId(),
            'list' => $this->_urlBuilder->getUrl('commercebanners/touchapi/listAreas', $ajaxData),
            'add' => $this->_backendUrl->getUrl('commercebanners/touchapi/addArea', $ajaxData),
            'edit' => $this->_backendUrl->getUrl('commercebanners/touchapi/editArea', $ajaxData),
            'delete' => $this->_backendUrl->getUrl('commercebanners/touchapi/deleteArea', $ajaxData),
            'categories' => $this->_backendUrl->getUrl('commercebanners/touchapi/categories', $ajaxData),
            'products' => $this->_backendUrl->getUrl('commercebanners/touchapi/products', $ajaxData),
            'Model' =>
                array (
                    'Id' => $bannerModel->getId(),
                    'Name' => $bannerModel->getImage(),
                    'ImagePath' => $bannerModel->getImagePath(),
                    'ImageUrl' => $bannerModel->getImageUrl(),
                    'Visibility' => $bannerModel->getIsEnabled(),
                ),
        );

        return json_encode($configData);
    }

    /**
     * @return string
     */
    public function getMediaDialogUrl()
    {
        return $this->_backendUrl->getUrl('cms/wysiwyg_images/index');
    }

    public function getBannerModel()
    {
        return $this->bannerRepository->getById(
            $this->getRequest()->getParam('banner_id')
        );
    }

    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }
}