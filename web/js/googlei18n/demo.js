goog.require('goog.dom');
goog.require('goog.json');
goog.require('goog.proto2.ObjectSerializer');
goog.require('goog.string.StringBuffer');
goog.require('i18n.phonenumbers.AsYouTypeFormatter');
goog.require('i18n.phonenumbers.PhoneNumberFormat');
goog.require('i18n.phonenumbers.PhoneNumberType');
goog.require('i18n.phonenumbers.PhoneNumberUtil');
goog.require('i18n.phonenumbers.PhoneNumberUtil.ValidationResult');




function verifNewInput(){

    var stockNewNum={};
    var $ = goog.dom.getElement;
    var region= 'ZZ';
    var phoneNumber=$('mobile-number').value;

    stockNewNum=phoneNumberParser(region,phoneNumber);

    $('NewNum').className=(stockNewNum.Number.isValid==true)?'valid':'unknow';
    return stockNewNum;

}

function verifNumX(regionP,phoneNumberP){

    var stock={};
    var region=regionP;
    var phoneNumber=phoneNumberP;
    stock=phoneNumberParser(region,phoneNumber);
    return stock;
}


function phoneNumberParser(region,phoneNumber) {
    var phoneNumber = phoneNumber;
    var regionCode = region;//value default 'ZZ'//$('defaultCountry').value;
    //var carrierCode = $('carrierCode').value;
    var output = new goog.string.StringBuffer();
    var test = {};
    test.Number={};
    test.Number.Format={};
    test.Number.Format.OutOfCountry={};

    try {
        var phoneUtil = i18n.phonenumbers.PhoneNumberUtil.getInstance();
        var number = phoneUtil.parseAndKeepRawInput(phoneNumber, regionCode);

        test.parseResult=goog.json.serialize(new goog.proto2.ObjectSerializer(goog.proto2.ObjectSerializer.KeyOption.NAME).serialize(number));

        var isPossible = phoneUtil.isPossibleNumber(number);

        test.Number.Possible=isPossible;

        if (!isPossible) {
            var PNV = i18n.phonenumbers.PhoneNumberUtil.ValidationResult;
            switch (phoneUtil.isPossibleNumberWithReason(number)) {
                case PNV.INVALID_COUNTRY_CODE:
                    test.Number.Possible='INVALID_COUNTRY_CODE';
                    break;
                case PNV.TOO_SHORT:
                    test.Number.Possible='TOO_SHORT';
                    break;
                case PNV.TOO_LONG:
                    test.Number.Possible='TOO_LONG';
                    break;
            }
            // IS_POSSIBLE shouldn't happen, since we only call this if _not_
            // possible.

        } else {

            var isNumberValid = phoneUtil.isValidNumber(number);

            test.Number.isValid=isNumberValid;

            if (isNumberValid && regionCode && regionCode != 'ZZ') {

                test.Number.isValidRegion=phoneUtil.isValidNumberForRegion(number, regionCode);

            }

            test.Country=phoneUtil.getRegionCodeForNumber(number);

            var PNT = i18n.phonenumbers.PhoneNumberType;
            switch (phoneUtil.getNumberType(number)) {
                case PNT.FIXED_LINE:

                    test.Number.Type='FIXED_LINE';
                    break;
                case PNT.MOBILE:

                    test.Number.Type='MOBILE';
                    break;
                case PNT.FIXED_LINE_OR_MOBILE:

                    test.Number.Type='FIXED_LINE_OR_MOBILE';
                    break;
                case PNT.TOLL_FREE:

                    test.Number.Type='TOLL_FREE';
                    break;
                case PNT.PREMIUM_RATE:

                    test.Number.Type='PREMIUM_RATE';
                    break;
                case PNT.SHARED_COST:

                    test.Number.Type='SHARED_COST';
                    break;
                case PNT.VOIP:

                    test.Number.Type='VOIP';
                    break;
                case PNT.PERSONAL_NUMBER:

                    test.Number.Type='PERSONAL_NUMBER';
                    break;
                case PNT.PAGER:

                    test.Number.Type='PAGER';
                    break;
                case PNT.UAN:

                    test.Number.Type='UAN';
                    break;
                case PNT.UNKNOWN:

                    test.Number.Type='UNKNOWN';
                    break;
            }
        }
        var PNF = i18n.phonenumbers.PhoneNumberFormat;

        test.Number.Format.E164=isNumberValid ?phoneUtil.format(number, PNF.E164) :'invalid';

        test.Number.Format.Original=phoneUtil.formatInOriginalFormat(number, regionCode);

        test.Number.Format.National=phoneUtil.format(number, PNF.NATIONAL);

        test.Number.Format.International=isNumberValid ?phoneUtil.format(number, PNF.INTERNATIONAL) :'invalid';

        test.Number.Format.OutOfCountry.US=isNumberValid ?phoneUtil.formatOutOfCountryCallingNumber(number, 'US') :'invalid';

        test.Number.Format.OutOfCountry.Switzerland=isNumberValid ?phoneUtil.formatOutOfCountryCallingNumber(number, 'CH') :'invalid';

        if (carrierCode.length > 0) {
            test.Number.Format.NationalCarrierCode=phoneUtil.formatNationalNumberWithCarrierCode(number,carrierCode);
        }
    } catch (e) {
        test.error=e;
    }
    return test;
}

goog.exportSymbol('phoneNumberParser', phoneNumberParser);