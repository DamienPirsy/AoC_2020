<?php


function solve_one($input) : string
{
    /**
     * Each line gives the password policy and then the password. The password policy indicates the lowest and 
     * highest number of times a given letter must appear for the password to be valid. For example, 1-3 a means 
     * that the password must contain a at least 1 time and at most 3 times.
     * In the above example, 2 passwords are valid. The middle password, cdefg, is not; it contains no instances of b, but needs at least 1. 
     * The first and third passwords are valid: they contain one a or nine c, both within the limits of their respective policies.
     * How many passwords are valid according to their policies?
     */
    $items = xplode_input($input);
    $correctPasswords = 0;

    // cycle through each line and separate the validation rules from the password, then
    foreach ($items as $item) {
        list($rules, $password) = array_map('trim', explode(':', $item));
        // take the elements of the rules you need to match the password against
        preg_match("/(\d+)-(\d+)\s(\w)/", $rules, $matches);
        $letter = $matches[3];
        
        // get a lest of password letters with their occurrencies
        $letters = array_count_values(str_split($password));
        if (array_key_exists($letter, $letters) && $letters[$letter] >= (int)$matches[1] && $letters[$letter] <= (int)$matches[2]) {
            $correctPasswords++;
        }
    }
    return sprintf("Correct passwords: %d\n", $correctPasswords);
}


function solve_two($input) : string
{
    /**
     * Each policy actually describes two positions in the password, where 1 means the first character, 2 means the second character, and so on. 
     * (Be careful; Toboggan Corporate Policies have no concept of "index zero"!) Exactly one of these positions must contain the given letter. 
     * Other occurrences of the letter are irrelevant for the purposes of policy enforcement.
     * Given the same example list from above:
     * 1-3 a: abcde is valid: position 1 contains a and position 3 does not.
     * 1-3 b: cdefg is invalid: neither position 1 nor position 3 contains b.
     * 2-9 c: ccccccccc is invalid: both position 2 and position 9 contain c.
     * How many passwords are valid according to the new interpretation of the policies?
     */

    $items = xplode_input($input);
    $correctPasswords = 0;

    // cycle through each line and separate the validation rules from the password, then
    foreach ($items as $item) {
        list($rules, $password) = array_map('trim', explode(':', $item));
        // take the elements of the rules you need to match the password against
        preg_match("/(\d+)-(\d+)\s(\w)/", $rules, $matches);
        $index1 = (int)$matches[1]-1;
        $index2 = (int)$matches[2]-1;
        $letter = $matches[3];

        // get a lest of password letters with their occurrencies
        $letters = str_split($password);
        if (in_array($letter, $letters)) {
            
            if (!($letters[$index1] == $letter && $letters[$index2] == $letter)) { // if it's not in both positions
                if ($letters[$index1] == $letter || $letters[$index2] == $letter) {
                    $correctPasswords++;
                }
            }
        }
    }
    return sprintf("Correct passwords: %d\n", $correctPasswords);
}