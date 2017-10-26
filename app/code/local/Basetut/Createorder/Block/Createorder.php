<?php

class Basetut_Createorder_Block_Createorder extends Mage_Core_Block_Template {


    protected function _getOrderCreateModel() {
        return Mage::getSingleton('adminhtml/sales_order_create');
    }

    public function methodOrder() {


    }

}