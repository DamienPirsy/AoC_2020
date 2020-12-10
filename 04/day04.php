<?php


function solve_one($input) : string
{
    return sprintf("Valid passports: %d\n", count(valid_passports($input)));
}


function solve_two($input) : string
{
    /**
     * You can continue to ignore the cid field, but each other field has strict rules about what values are valid for automatic validation:
     * 
     * byr (Birth Year) - four digits; at least 1920 and at most 2002.
     * iyr (Issue Year) - four digits; at least 2010 and at most 2020.
     * eyr (Expiration Year) - four digits; at least 2020 and at most 2030.
     * hgt (Height) - a number followed by either cm or in:
     * If cm, the number must be at least 150 and at most 193.
     * If in, the number must be at least 59 and at most 76.
     * hcl (Hair Color) - a # followed by exactly six characters 0-9 or a-f.
     * ecl (Eye Color) - exactly one of: amb blu brn gry grn hzl oth.
     * pid (Passport ID) - a nine-digit number, including leading zeroes.
     * cid (Country ID) - ignored, missing or not.Your job is to count the passports where all required fields are both present and valid according to the above rules
     * 
     * Count the number of valid passports - those that have all required fields and valid values. Continue to treat cid as optional. 
     * In your batch file, how many passports are valid?
     */

    $valid = 0;

    foreach (valid_passports($input) as $passport) {       
        if (validate_year((int)$passport['byr'], 1920, 2002) &&
                 validate_year((int)$passport['iyr'], 2010, 2020) &&
                 validate_year((int)$passport['eyr'], 2020, 2030) &&
                 validate_height($passport['hgt']) &&
                 validate_color($passport['hcl']) &&
                 validate_eyes($passport['ecl']) &&
                 validate_pid($passport['pid'])
        ) {
            $valid++;
        }
    }
    return sprintf("Valid passports: %d\n", $valid);
}

/*  validation funcions */

/**
 * 
 * @param integer $year
 * @param integer $min
 * @param integer $max
 * @return boolean
 */
function validate_year(int $year, int $min, int $max) : bool {
    return ($year >= $min && $year <= $max);
}

/**
 *
 * @param string $height
 * @return boolean
 */
function validate_height( string $height): bool{
    if (preg_match('/([0-9]+)(in|cm)/', $height, $matches)) {
        if ($matches[2] == 'in') {
            return $matches[1] >= 59 && $matches[1] <= 76;
        } else {
            return $matches[1] >= 150 && $matches[1] <= 193;
        }
    }
    return false;
}

/**
 * 
 * @param string $color
 * @return boolean
 */
function validate_color(string $color) : bool {
    return preg_match('/\#([0-9a-f]){6}/', $color);
}

/**
 * 
 * @param string $color
 * @return boolean
 */
function validate_eyes(string $color) : bool {
    return in_array($color, ['amb', 'blu', 'brn', 'gry', 'grn', 'hzl', 'oth']);
}

/**
 *
 * @param string $pid
 * @return boolean
 */
function validate_pid(string $pid) : bool {
    if (strlen($pid) == 9) {
        return preg_match('/([0-9]+)/', $pid);
    }
    return false;
}

/**
 * Common methods for both puzzles; returns the passports passing the first validation
 *
 * @param  string $input
 * @return array
 */
function valid_passports(string $input) : array {

    $passports = group_lines($input);
    $validPassports = [];
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
            array_push($validPassports, $correctPassport);
        }
    }
    return $validPassports;
}