<?php
class Trendmarke_Validatezip_Block_Config extends Mage_Core_Block_Template
{
    /**
     * Returns validation url
     * @return string
     */
    public function getValidationUrl() {
        return Mage::getUrl('tr_validatezip/validate/check',array('_secure'=>true));
    }

}
