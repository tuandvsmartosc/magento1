<?php
class Basetut_Helloworld_Model_DataManager extends Mage_Catalog_Model_Abstract{

    public function getCustomerData(){
        $customer = Mage::getModel('customer/customer')->getCollection()//getmoodel -> den class customer
            //get getcollection. ham getcolloction de tao cau query
            ->addAttributeToSelect('*')
            ->setOrder('entity_id', 'ASC')
            ->setPageSize(100);
        foreach ($customer as $custome) {
//            var_dump($custome->getData());die;
            $arr_customer[] = $custome->toArray(array('entity_id', 'firstname' ,'lastname', 'email', 'group_id'));
        }

        return $arr_customer;
    }
    public function getProductData(){
//           die('tuananaana');
        $product = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('*')
            ->setOrder('entity_id', 'DESC')
            ->setPageSize(100);
        foreach ($product as $pro) {
//            var_dump($pro->getData());

            $arr_products[] = $pro->toArray(array('entity_id','type_id','name', 'sku', 'price'));
        }
//        die;
        return  $arr_products;
    }
    public function getActivePaymentMethods()
    {
        $payments = Mage::getSingleton('payment/config')->getActiveMethods();

        $methods = array(array('value'=>'', 'label'=>Mage::helper('adminhtml')->__('--Please Select--')));

        foreach ($payments as $paymentCode=>$paymentModel) {
            $paymentTitle = Mage::getStoreConfig('payment/'.$paymentCode.'/title');
            $methods[] = array(

                'label'   => $paymentTitle,
                'value' => $paymentCode,
            );
        }

        return $methods;

    }

    public function getShippingOptionArray($isMultiSelect = false)
    {
        $methods = Mage::getSingleton('shipping/config')->getActiveCarriers();

        $options = array();

        foreach($methods as $_code => $_method)
        {
            if(!$_title = Mage::getStoreConfig("carriers/$_code/title"))
                $_title = $_code;

            $options[] = array('value' => $_code, 'label' => $_title . " ($_code)");
        }

        if($isMultiSelect)
        {
            array_unshift($options, array('value'=>'', 'label'=> Mage::helper('adminhtml')->__('--Please Select--')));
        }

        return $options;
    }

}