<?php


$dataKeys = ['byr','iyr','eyr','hgt ','hcl','ecl','pid','cid'];

function solve_one($input) : string
{
    $items = xplode_input($input);
    $valids = 0 ;
    $passports = [];
    $currentPasswport = [];

    foreach ($items as $item) {

        if (!empty($item)) {
            // if it's not a new line append data to the current passport
            array_push($currentPasswport, $item);
        } else {
            // the current passport is complete, add it to the global list and start a new one
            array_push($passports, $currentPasswport);
            $currentPasswport = [];
        }
    }

    // step 1 done, now we have all passports with the complete data. Cycle trough each one
    // and put the info together
    foreach ($passports as $passport) {

        $correctPassport = [];
        foreach ($passport as $pp) {
            foreach (explode(" ", $pp) as $tuple) {                
                list($item, $value) = explode(':', $tuple);
                $correctPassport[$item] = $value;
            }
        }
        $numItems = count($correctPassport);
        // if it contains all 8 item the pp is valid
        // if it's 7 but only "cid" is missing passport is still fine
        if ($numItems == 8 || ($numItems == 7 && !isset($correctPassport['cid']))) {
            $valids++;
        }
    }
    return sprintf("Valid passports: %d\n", $valids);
}


function solve_two($input) : string
{
    return "";
}