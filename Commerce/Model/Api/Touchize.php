<?php
namespace Touchize\Commerce\Model\Api;

class Touchize
{
    public function __construct(
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
    }

    public function execute()
    {
        $data = json_decode('[{"Id":"404","SKU":"msj006c","Title":"Plaid Cotton Shirt",
        "SingleVariantId":null,"Url":"men\/shirts\/plaid-cotton-shirt-577.html","Price":160,"DiscountedPrice":null,"FPrice":"$160.00","FDiscountedPrice":null,"Images":[{"Name":"http:\/\/touchizem1.loc\/media\/catalog\/product\/cache\/1\/small_image\/210x\/9df78eab33525d08d6e5fb8d27136e95\/m\/s\/msj006t.jpg","UseCDN":true,"Alt":"Plaid Cotton Shirt"}]},{"Id":"403","SKU":"msj003c","Title":"Slim fit Dobby Oxford Shirt","SingleVariantId":null,"Url":"men\/shirts\/slim-fit-dobby-oxford-shirt-575.html","Price":175,"DiscountedPrice":140,"FPrice":"$175.00","FDiscountedPrice":"$140.00","Images":[{"Name":"http:\/\/touchizem1.loc\/media\/catalog\/product\/cache\/1\/small_image\/210x\/9df78eab33525d08d6e5fb8d27136e95\/m\/s\/msj003t_2.jpg","UseCDN":true,"Alt":"Slim fit Dobby Oxford Shirt"}]},{"Id":"402","SKU":"msj000c","Title":"French Cuff Cotton Twill Oxford","SingleVariantId":null,"Url":"men\/shirts\/french-cuff-cotton-twill-oxford-573.html","Price":190,"DiscountedPrice":null,"FPrice":"$190.00","FDiscountedPrice":null,"Images":[{"Name":"http:\/\/touchizem1.loc\/media\/catalog\/product\/cache\/1\/small_image\/210x\/9df78eab33525d08d6e5fb8d27136e95\/m\/s\/msj000t_2.jpg","UseCDN":true,"Alt":"French Cuff Cotton Twill Oxford"}]}]',true);
        $result = $this->resultJsonFactory->create();
        return $result->setData($data);
    }
}
