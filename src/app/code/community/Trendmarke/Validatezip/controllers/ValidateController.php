<?php
class Trendmarke_Validatezip_ValidateController extends Mage_Core_Controller_Front_Action
{
    public function checkAction()
    {
        $msg = '';
        $valid = true;
        if ((boolean) Mage::getStoreConfig('validate_zip/general/active')) { // check is active
            $params = $this->getRequest()->getParams();
            $valid = (boolean) !Mage::getStoreConfig('validate_zip/general/restrictive'); // if restrictive return false in doubt
            if (!isset($params['zip']) || strlen($params['zip'])<2 || !isset($params['country']) || strlen($params['country'])!=2) {
                $msg.= $this->__('invalid request');
            } else { // request is valid
                $zip = $params['zip'];
                $country = $params['country'];
                // validate regex countries
                $_regex_countries = explode(',',Mage::getStoreConfig('validate_zip/general/countries_regex'));
                if (in_array($country,$_regex_countries)) {
                    $valid = (boolean) Mage::helper('validatezip')->validateWithRegex($zip,$country);
                    $msg.='regex,';
                }
                // validate primary api countries
                $_api_countries = explode(',',Mage::getStoreConfig('validate_zip/general/countries_primary_api'));
                if (in_array($country,$_api_countries)) {
                    $valid = (boolean) Mage::helper('validatezip')->validateWithApi($zip,$country);
                    $msg.='api,';
                }
                // validate secundary api countries
                $_api_countries = explode(',',Mage::getStoreConfig('validate_zip/general/countries_secondary_api'));
                if (in_array($country,$_api_countries)) {
                    $valid = (boolean) Mage::helper('validatezip')->validateWithApi($zip,$country,true);
                    $msg.='api,';
                }
            }
        }
        // validate
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $result =  json_encode(['success'=>$valid,'message'=>$msg]);
        $this->getResponse()->setBody($result);
    }
}
