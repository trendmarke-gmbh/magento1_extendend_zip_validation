<?php
class Trendmarke_Validatezip_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * Validate with regex: faster but only format can be checked
     *
     * @param $zip
     * @param $country_id
     * @return bool|int
     */
    public function validateWithRegex($zip,$country_id) {
        switch (strtoupper($country_id)) {
            case 'DE':
                return preg_match('/^([0]{1}[1-9]{1}|[1-9]{1}[0-9]{1})[0-9]{3}$/',$zip);
                break;
            case 'AT':
                return preg_match('/^[0-9]{4}$/',$zip);
                break;
            default:
                return (boolean) Mage::getStoreConfig('validate_zip/general/restrictive'); // no rule for country return default
                break;
        }
    }

    /**
     * Validate with external API
     * To add new apis copy this helper to local area and add function + option in switch below
     *
     * @param $zip
     * @param $country_id
     * @return bool
     */
    public function validateWithApi($zip,$country_id,$secodary=false) {
        $_api_type = ($secodary) ? Mage::getStoreConfig('validate_zip/general/secondary_api') : Mage::getStoreConfig('validate_zip/general/primary_api');
        switch ($_api_type) {
            case 'google':
                return $this->validateWithGoogleMaps($zip,$country_id);
                break;
            case 'osm':
                return $this->validateWithOsm($zip,$country_id);
                break;
            case 'locationiq':
                return $this->validateWithLocationIq($zip,$country_id);
                break;
            case 'geonames':
                return $this->validateWithGeonames($zip,$country_id);
                break;
            default:
                return (boolean) Mage::getStoreConfig('validate_zip/general/restrictive'); // no rule for country return default
                break;
        }
    }

    /**
     * Validate with Google Maps API https://developers.google.com/maps
     * (Key requiered)
     *
     * @param $zip
     * @param $country_id
     * @return bool
     */
    public function validateWithGoogleMaps($zip,$country_id) {
        $_key = Mage::getStoreConfig('validate_zip/general/google_key');
        $_url = sprintf('https://maps.googleapis.com/maps/api/geocode/json?components=postal_code:%s|country:%s&key=%s',urlencode  ($zip),$country_id,$_key);
        $_json = @file_get_contents($_url);
        if ($_json !== false) {
            $_result = json_decode($_json, true);
            return (boolean) (isset($_result['results']) && count($_result['results'])>0);
        }
        return (boolean) Mage::getStoreConfig('validate_zip/general/restrictive');
    }

    /**
     * Validate with Open Street Maps http://wiki.openstreetmap.org/wiki/API
     * (No key required)
     *
     * @param $zip
     * @param $country_id
     * @return bool
     */
    public function validateWithOsm($zip,$country_id) {
        $_url = sprintf('http://nominatim.openstreetmap.org/search/?format=json&postalcode=%s&countrycodes=%s',urlencode  ($zip),$country_id);
        $_json = @file_get_contents($_url);
        if ($_json !== false) {
            $_result = json_decode($_json, true);
            return (boolean) (isset($_result) && count($_result)>0);
        }
        return (boolean) Mage::getStoreConfig('validate_zip/general/restrictive');
    }

    /**
     * Validate with Open Street Map derivate https://locationiq.org/
     * (Key required)
     *
     * @param $zip
     * @param $country_id
     * @return bool
     */
    public function validateWithLocationIq($zip,$country_id) {
        $_key = Mage::getStoreConfig('validate_zip/general/locationiq_key');
        $_url = sprintf('http://locationiq.org/v1/search.php?format=json&postalcode=%s&countrycodes=%s&key=%s',urlencode  ($zip),$country_id,$_key);
        $_json = @file_get_contents($_url);
        if ($_json !== false) {
            $_result = json_decode($_json, true);
            return (boolean) (isset($_result) && count($_result)>0);
        }
        return (boolean) Mage::getStoreConfig('validate_zip/general/restrictive');
    }

    /**
     * Validate with GeoNames Web Services http://www.geonames.org
     * (Username required)
     *
     * @param $zip
     * @param $country_id
     * @return bool
     */
    public function validateWithGeonames($zip,$country_id) {
        $_key = Mage::getStoreConfig('validate_zip/general/geoname_username');
        $_url = sprintf('http://api.geonames.org/postalCodeLookupJSON?postalcode=%s&country=%s&username=%s',urlencode ($zip),$country_id,$_key);
        $_json = @file_get_contents($_url);
        if ($_json !== false) {
            $_result = json_decode($_json, true);
            return (boolean) (isset($_result['postalcodes']) && count($_result['postalcodes'])>0);
        }
        return (boolean) Mage::getStoreConfig('validate_zip/general/restrictive');
    }

}
	 