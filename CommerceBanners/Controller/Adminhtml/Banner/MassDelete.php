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
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;
use Touchize\CommerceBanners\Api\BannerRepositoryInterface;
use Touchize\CommerceBanners\Controller\Adminhtml\Banner;
use Touchize\CommerceBanners\Model\ResourceModel\Banner\CollectionFactory;

class MassDelete extends Banner
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var string
     */
    protected $successMessage;

    /**
     * @var string
     */
    protected $errorMessage;

    /**
     * MassAction constructor.
     *
     * @param Registry $registry
     * @param BannerRepositoryInterface $bannerRepository
     * @param PageFactory $resultPageFactory
     * @param Date $dateFilter
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param $successMessage
     * @param $errorMessage
     */
    public function __construct(
        Registry $registry,
        BannerRepositoryInterface $bannerRepository,
        PageFactory $resultPageFactory,
        Date $dateFilter,
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        parent::__construct($registry, $bannerRepository, $resultPageFactory, $dateFilter, $context);
        $this->filter            = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->successMessage    = __('Banners deleted');
        $this->errorMessage      = __('Something went wrong');
    }

    /**
     * execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $collectionSize = $collection->getSize();
            foreach ($collection as $banner) {
                $this->bannerRepository->delete($banner);
            }
            $this->messageManager->addSuccessMessage(__($this->successMessage, $collectionSize));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __($this->errorMessage));
        }
        $redirectResult = $this->resultRedirectFactory->create();
        $redirectResult->setPath('commercebanners/image');
        return $redirectResult;
    }
}
