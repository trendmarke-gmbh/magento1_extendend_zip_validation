
function extendedZipValidation() {

    var country_id= false;
    var zip= false;
    var field= false;

    // check if billing or shipping
    if (typeof document.getElementById("opc-billing") !== "undefined"
        && document.getElementById("opc-billing").className.indexOf("active")>-1
        && typeof document.getElementById("billing:country_id") !== "undefined"
        && typeof document.getElementById("billing:postcode") !== "undefined") {
        country_id = document.getElementById("billing:country_id").value;
        field =document.getElementById("billing:postcode");
        zip = $j(field).val();
    } else if (typeof document.getElementById("opc-shipping") !== "undefined"
        && document.getElementById("opc-shipping").className.indexOf("active")>-1
        && typeof document.getElementById("shipping:country_id") !== "undefined"
        && typeof document.getElementById("shipping:postcode") !== "undefined") {
        country_id = document.getElementById("shipping:country_id").value;
        field =document.getElementById("shipping:postcode");
        zip = $j(field).val();
    } else {
        return true;
    }

    // now validate
    $j(field).addClass("field-loading");
    if (typeof zip_validation_url !== "undefined" && country_id!==false && zip!==false) {
        if (zip==="" || country_id.length!==2) {
            $j(field).removeClass("field-loading");
            $j(field).addClass("not-valid");
            return false;
        }
        var url = zip_validation_url+"?country="+country_id+"&zip="+zip ;
        $j.getJSON( url).done(function(data) {
            $j(field).removeClass("field-loading");
            if (data.success) {
                $j(field).removeClass("not-valid");
                $j(field).addClass("validated");
            } else {
                $j(field).removeClass("validated");
                $j(field).addClass("not-valid");
            }
        }).fail(function() {
            $j(field).removeClass("not-valid");
            $j(field).removeClass("field-loading");
        });
    } else {
        $j(field).removeClass("not-valid");
        $j(field).removeClass("field-loading");
    }
    return true;
}

Validation.add('validate-zip-international', 'Please enter a valid zip code.', function(v) {
    // check if already validated
    if (typeof document.getElementById("opc-billing") !== "undefined"
        && document.getElementById("opc-billing").className.indexOf("active")>-1
        && typeof document.getElementById("billing:postcode") !== "undefined" ) {
        return !(document.getElementById("billing:postcode").className.indexOf("not-valid")>0);
    } else if (typeof document.getElementById("opc-shipping") !== "undefined"
        && document.getElementById("opc-shipping").className.indexOf("active")>-1
        && typeof document.getElementById("shipping:postcode") !== "undefined" ) {
        return !(document.getElementById("shipping:postcode").className.indexOf("not-valid")>0);
    }
    return true;
});

$j( document ).ready(function() {
   $j("#billing\\:postcode,#shipping\\:postcode,#billing\\:country_id,#shipping\\:country_id").change(function() {
       extendedZipValidation();
   });
});