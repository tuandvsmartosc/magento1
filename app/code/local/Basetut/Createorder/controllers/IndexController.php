<?php

class Basetut_Createorder_IndexController extends Mage_Core_Controller_Front_Action {

    protected function _getOrderCreateModel() {
        return Mage::getSingleton('adminhtml/sales_order_create');
    }

    /**
     * index action
     */
    public function indexAction() {
        /** @var Mage_Adminhtml_Model_Sales_Order_Create $create */
        //$create = $this->_getOrderCreateModel();

        /**
         * @var Mage_Sales_Model_Quote
         */
        if (isset($_POST["customer_id"])) {
            $customerId   = $_POST["customer_id"];
            $quote        = Mage::getModel('sales/quote');
            $customerData = Mage::getModel('customer/customer')->load($customerId);
            $quote->assignCustomer($customerData);


            if (isset($_POST["storedId"])) {
                $storeId = $_POST["storedId"];
                $quote->setStoreId($storeId);

                if (isset($_POST["arrIdProduct"]) && isset($_POST["quantity"])) {
                    $arrproductId = $_POST["arrIdProduct"];
                    $quantity     = $_POST["quantity"];

                    foreach ($arrproductId as $arrproduct) {
                        $product = Mage::getModel('catalog/product')->load($arrproduct);
                        $buyInfo = ['qty' => $quantity];
                        $quote->addProduct($product, new Varien_Object($buyInfo));

                    }
                    $quote->getBillingAddress()
                          ->addData($customerData->getDefaultBillingAddress()->getData());
                    if (isset($_POST["paymentMethod"])) {
                        $paymentMethod = $_POST["paymentMethod"];
                        $quote->getPayment()->setMethod($paymentMethod);
                        $quote->getPayment()->importData(['method' => $paymentMethod]);
                    }
                    $result = null;
                    if (isset($_POST["shippingMethod"])) {
                        if ($_POST['shippingMethod'] == 1) {
                            $quote->getShippingAddress()
                                  ->setRecollect(true)
                                  ->addData($customerData->getDefaultShippingAddress()->getData())
                                  ->setCollectShippingRates(true)
                                  ->collectShippingRates();
                            $rates = [];
                            foreach ($quote->getShippingAddress()->getAllShippingRates() as $allShippingRate) {
                                $rates[] = $allShippingRate->getData();

                            }
                            $result = $rates;
                        }

                        else {
                            $shippingMethod = $_POST["shippingMethod"];
                            $quote->getShippingAddress()
                                  ->setShippingMethod($shippingMethod)
                                  ->setRecollect(true)
                                  ->addData($customerData->getDefaultShippingAddress()->getData())
                                  ->setCollectShippingRates(true)
                                  ->collectShippingRates();
                        }

                    }

                    if (isset($_POST['creatorder'])) {
                        $quote->collectTotals();
                        $quote->save();
                        $service = Mage::getModel('sales/service_quote', $quote);
                        $order   = $service->submit();
                    }


                }

            }

            return $this->getResponse()
                        ->setHeader('Content-type', 'application/json')//sends the http json header to the browser
                        ->setHeader('Access-Control-Allow-Origin', '*')// Allow other page to get data
                        ->setBody(json_encode($result));
        }
    }


}