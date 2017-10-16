<?php

class Basetut_Helloworld_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * index action
     */
    public function customerAction()
    {
    $data =  Mage::getModel('helloworld/dataManager')->getCustomerData(); //getModel gọi đến class helloworld-model-dataManager-> hàm getCustomer.
        return $this->getResponse()
            ->setHeader('Content-type', 'application/json')
            ->setBody(json_encode($data));//chuyển data về chuỗi json.
    }

    public function productAction()
    {
//        die('dsfsd');
        $data =  Mage::getModel('helloworld/dataManager')->getProductData();
        return $this->getResponse()
            ->setHeader('Content-type', 'application/json')
            ->setBody(json_encode($data));
    }
    public function paymentAction()
    {
        $data =Mage::getModel('helloworld/dataManager') ->getActivePaymentMethods();
        return $this->getResponse()
            ->setBody(json_encode($data))
        ->setHeader('Content-type', 'application/json');

    }
    public function shippingAction()
    {
        $data =Mage::getModel('helloworld/dataManager') ->getShippingOptionArray();
        return $this->getResponse()
            ->setBody(json_encode($data))
        ->setHeader('Content-type', 'application/json');
    }

}