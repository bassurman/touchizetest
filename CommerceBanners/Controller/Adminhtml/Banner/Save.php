<?php
/*
 * Touchize_CommerceBanners

 * @category   Touchize
 * @package    Touchize_CommerceBanners
 * @copyright  Copyright (c) 2017 Touchize

 * @version    1.0.0
 */
namespace Touchize\CommerceBanners\Controller\Adminhtml\Banner;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Message\Manager;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use Magento\Framework\View\Result\PageFactory;
use Touchize\CommerceBanners\Api\BannerRepositoryInterface;
use Touchize\CommerceBanners\Api\Data\BannerInterface;
use Touchize\CommerceBanners\Api\Data\BannerInterfaceFactory;
use Touchize\CommerceBanners\Controller\Adminhtml\Banner;
use Touchize\CommerceBanners\Model\Uploader;
use Touchize\CommerceBanners\Model\UploaderPool;

class Save extends Banner
{
    /**
     * @var Manager
     */
    protected $messageManager;

    /**
     * @var BannerRepositoryInterface
     */
    protected $bannerRepository;

    /**
     * @var BannerInterfaceFactory
     */
    protected $imageFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var UploaderPool
     */
    protected $uploaderPool;

    /**
     * Save constructor.
     *
     * @param Registry $registry
     * @param BannerRepositoryInterface $bannerRepository
     * @param PageFactory $resultPageFactory
     * @param Date $dateFilter
     * @param Manager $messageManager
     * @param BannerInterfaceFactory $imageFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param UploaderPool $uploaderPool
     * @param Context $context
     */
    public function __construct(
        Registry $registry,
        BannerRepositoryInterface $bannerRepository,
        PageFactory $resultPageFactory,
        Date $dateFilter,
        Manager $messageManager,
        BannerInterfaceFactory $imageFactory,
        DataObjectHelper $dataObjectHelper,
        UploaderPool $uploaderPool,
        Context $context
    ) {
        parent::__construct($registry, $bannerRepository, $resultPageFactory, $dateFilter, $context);
        $this->messageManager   = $messageManager;
        $this->imageFactory      = $imageFactory;
        $this->bannerRepository   = $bannerRepository;
        $this->dataObjectHelper  = $dataObjectHelper;
        $this->uploaderPool = $uploaderPool;
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $id = $this->getRequest()->getParam('banner_id');
            if ($id) {
                $model = $this->bannerRepository->getById($id);
            } else {
                unset($data['banner_id']);
                $model = $this->imageFactory->create();
            }

            try {
                $image = $this->getUploader('image')->uploadFileAndGetName('image', $data);
                $data['image'] = $image;

                $this->dataObjectHelper->populateWithArray($model, $data, BannerInterface::class);
                $this->bannerRepository->save($model);
                $this->messageManager->addSuccessMessage(__('Banner was successfully saved.'));
                $this->_getSession()->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['banner_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException(
                    $e,
                    __('Something went wrong while saving the image:' . $e->getMessage())
                );
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['banner_id' => $this->getRequest()->getParam('banner_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param $type
     * @return Uploader
     * @throws \Exception
     */
    protected function getUploader($type)
    {
        return $this->uploaderPool->getUploader($type);
    }
}
