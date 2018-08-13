<?php

namespace Touchize\Commerce\Controller;

class TouchizeRouter implements \Magento\Framework\App\RouterInterface
{
    protected $actionFactory;
    protected $_response;
    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Magento\Framework\App\ResponseInterface $response,
        \Touchize\Commerce\Model\Api\Touchize $apiTouchize,
        \Touchize\Commerce\Model\PageConfigFactory $pageConfigFactory
    ) {
        $this->actionFactory = $actionFactory;
        $this->pageConfigFactory = $pageConfigFactory;
        $this->_response = $response;
        $this->_apiTouchize = $apiTouchize;
    }
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        $identifier = trim($request->getPathInfo(), '/');
        if(strpos($identifier, 'touchizecommerce/api') !== false) {
            $actionName = $this->getActionName($request);
            if (!$actionName) {
                return false;
            }
            $request->setModuleName('touchizecommerce')
                ->setControllerName('api')
                ->setActionName($actionName);
        } else {
            return false;
        }

        return $this->actionFactory->create(
            'Magento\Framework\App\Action\Forward',
            ['request' => $request]
        );
    }

    protected function getActionName($request)
    {
        $pathInfo = $request->getPathInfo();
        $path = explode('/',$pathInfo);
        return end($path);
    }
}