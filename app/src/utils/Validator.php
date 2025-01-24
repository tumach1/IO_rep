<?php

class Validator{

    // code made with regard to conclusions from following discussion
    // https://stackoverflow.com/questions/12026842/how-to-validate-an-email-address-in-php#12026873
    static function checkEmailAddress($address) : bool
    {
        if (empty($address) || !str_contains($address, '@')){
            return false;
        }
        $addressArray = explode('@', $address);
        if(sizeof($addressArray) !== 2){
            return false;
        }
        $username = $addressArray[0];
        $domain = $addressArray[1];
        // for testing purposes we disable checking domain in dns
        if(empty($username) || empty($domain) /*|| !checkdnsrr($domain)*/) {
            return false;
        }
        return true;
    }

    static function checkPostalCode($code) : bool
    {
        $regex = '#[0-9]{2}-[0-9]{3}#';
        if(strlen($code) === 6 && preg_match_all($regex, $code) === 1){
                return true;
        }
        return false;
    }

    // for now, just check has it 8 characters and check does passwords match
    static function checkPassword($password, $confirmedPassword) : bool
    {
        if ($password !== $confirmedPassword) {
            return false;
        }

        if (strlen($password) < 8) {
            return false;
        }

        return true;
    }

    static function checkPesel($pesel) : bool {
        return strlen($pesel) === 11 && ctype_digit($pesel);
    }

}