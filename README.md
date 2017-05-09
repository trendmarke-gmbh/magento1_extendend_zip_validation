# Magento (v1): Extended zip validation
Adds an extended zip validation via regex or various extenal apis to your Magento 1 shop.

## Installation

### Modman
You can easily clone this repo with modman. Learn more in the modman wiki at https://github.com/colinmollenhour/modman/wiki/Tutorial

```
$ modman clone https://github.com/trendmarke-gmbh/magento1_extendend_zip_validation
```

### Manual installation
Alternatively you can download the repo and transfer the content of the src directory into your Magento root directory. After the installation clear the cache and that's it.

## Configuration
You find the configuration as a new tab at the checkout configurations. You can set:
* Restrictive mode: If active and something went wrong or a country could not be checked it marks the zip as invalid (If not sure turn this off)
* Primary/secondary api: Choose what API you want to use and add the belonging key/usernames. You can use up to two APIs and select what countries you want to use which API, e.g. some APIs does not cover all countries.

## Validation methods

### How this extension works
When typing your zip code in the checkout a little script makes an asynchronous call to validate the entered zip code. After the validation the extension adds an css class to the field that is then checked when clicking on "next". With this method there is a better UX than a synchronous call when clicken next button.

### Regex
Simple validation via regex expressions. At the moment there are only regex expressions for Germany (length + structure) and Austria (only length). Feel free to add new expressions as a regex expression is often good enough and way faster then an API call.

### External APIs
Validation with external geocoding APIs. Currently there are the following APIs included:

- Google Maps (API key requiered): https://developers.google.com/maps/documentation/geocoding

- Open Street Maps (No API key requiered): http://wiki.openstreetmap.org/wiki/API

- LocationIQ (Open Street Maps derivate) (API key requiered): https://locationiq.org/

- GeoNames (Username requiered): http://www.geonames.org/

## Customization

### Add new validaton methods
Simply copy the helper from `app/code/community/Trendmarke/Validatezip/Helper/Data.php` to `app/code/local/Trendmarke/Validatezip/Helper/Data.php` and add your regex or API method (see comments in code for more information)

### Javascript
You find the validation script in `js/trendmarke`. There is a vanilla js and a jquery version. Feel free to change logic here.

### Stylesheeets
We recommend to add some css styles to indicate wheter valdiation is loading, succeeded or faild.

E.g.:
```
input.field-loading {
    background-color: #ffffff;
    background-image: url("../images/loading.gif");
    background-position:right center;
    background-repeat: no-repeat;
}
input.not-valid {
    border: 1px dashed #eb340a !important;
    background: #faebe7 !important;
}
input.validated {
    background-image: url("../images/success.png") !important;
    border-color:green;
    border-style: solid;
    background-position:right center;
    background-repeat: no-repeat;
    background-color: #DFF2BF;
}
```

## Notes and Credits
- This extension was tested with Magento 1.9.x but it should also work with older versions (probably till 1.4.x).