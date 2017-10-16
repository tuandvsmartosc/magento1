<?php
class Basetut_Helloworld_Model_DataManager extends Mage_Catalog_Model_Abstract{

    public function getCustomerData(){
        $customer = Mage::getModel('customer/customer')->getCollection()//getmoodel -> den class customer
            //get getcollection. ham getcolloction de tao cau query
            ->addAttributeToSelect('*')
            ->setOrder('entity_id', 'ASC')
            ->setPageSize(100)
        //get default bill setting
        ->joinAttribute('billing_street', 'customer_address/street', 'default_billing', null, 'left')
            ->joinAttribute('billing_postcode', 'customer_address/postcode', 'default_billing', null, 'left')
            ->joinAttribute('billing_city', 'customer_address/city', 'default_billing', null, 'left')
            ->joinAttribute('billing_telephone', 'customer_address/telephone', 'default_billing', null, 'left')
            ->joinAttribute('billing_fax', 'customer_address/fax', 'default_billing', null, 'left')
            ->joinAttribute('billing_region', 'customer_address/region', 'default_billing', null, 'left')
            ->joinAttribute('billing_country_code', 'customer_address/country_id', 'default_billing', null, 'left')
            //get default ship setting
            ->joinAttribute('shipping_street', 'customer_address/street', 'default_shipping', null, 'left')
            ->joinAttribute('shipping_postcode', 'customer_address/postcode', 'default_shipping', null, 'left')
            ->joinAttribute('shipping_city', 'customer_address/city', 'default_shipping', null, 'left')
            ->joinAttribute('shipping_telephone', 'customer_address/telephone', 'default_shipping', null, 'left')
            ->joinAttribute('shipping_fax', 'customer_address/fax', 'default_shipping', null, 'left')
            ->joinAttribute('shipping_region', 'customer_address/region', 'default_shipping', null, 'left')
            ->joinAttribute('shipping_country_code', 'customer_address/country_id', 'default_shipping', null, 'left')
            ->joinAttribute('taxvat', 'customer/taxvat', 'entity_id', null, 'left');
        foreach ($customer as $custome) {
//            var_dump($custome->getData());die;
            $arr_customer[] = $custome->toArray(array('entity_id', 'firstname' ,'lastname', 'email', 'group_id', 'shipping_street','billing_city'));
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
        $payments = Mage::getSingleton('payment/config')->getActiveMethods(); // chưa hiểu getsingleton là gì?

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
        $methods = Mage::getSingleton('shipping/config')->getActiveCarriers(); // chưa hiểu getsingleton là gì.

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