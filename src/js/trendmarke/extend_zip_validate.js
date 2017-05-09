
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
        zip = document.getElementById("billing:postcode").value;
    } else if (typeof document.getElementById("opc-shipping") !== "undefined"
        && document.getElementById("opc-shipping").className.indexOf("active")>-1
        && typeof document.getElementById("shipping:country_id") !== "undefined"
        && typeof document.getElementById("shipping:postcode") !== "undefined") {
        country_id = document.getElementById("shipping:country_id").value;
        field =document.getElementById("shipping:postcode");
        zip = document.getElementById("billing:postcode").value;
    } else {
        return true;
    }

    // now validate
    field.classList.add("field-loading");
    if (typeof zip_validation_url !== "undefined" && country_id!==false && zip!==false) {
        if (zip==="" || country_id.length!==2) {
            field.classList.remove("field-loading");
            field.classList.add("not-valid");
            return false;
        }
        var url = zip_validation_url+"?country="+country_id+"&zip="+zip ;
		var httpRequest = new XMLHttpRequest();
		httpRequest.onreadystatechange = function() {
			if (httpRequest.readyState === 4) {
				if (httpRequest.status === 200) {
					var data = JSON.parse(httpRequest.responseText);
					if (callback) {
						var result = JSON.parse(data);
						field.classList.remove("field-loading");
						if (result.success) {
							field.classList.remove("not-valid");
							field.classList.add("validated");
						} else {
							field.classList.remove("validated");
							field.classList.add("not-valid");
						}
					}
				} else { // something went wrong ignore validation
					field.classList.remove("not-valid");
					field.classList.remove("field-loading");
				}
			}
		};
		httpRequest.open('GET', url);
		httpRequest.send(); 
	} else { // no valid input skip validation
        field.classList.remove("not-valid");
        field.classList.remove("field-loading");
    }
    return true;
}

Validation.add('validate-zip-international', 'Please enter a valid zip code.', function(v) {
    // check if validated by class name
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

// if post code or country changes revalidate
document.getElementById("billing:postcode").addEventListener(
     'change',
     function() { extendedZipValidation(); },
     false
);

document.getElementById("shipping:postcode").addEventListener(
     'change',
     function() { extendedZipValidation(); },
     false
);  

document.getElementById("billing:country_id").addEventListener(
     'change',
     function() { extendedZipValidation(); },
     false
);

document.getElementById("shipping:country_id").addEventListener(
     'change',
     function() { extendedZipValidation(); },
     false
);  