<?php


function solve_one($input) : string
{
    /**
     * The first step of attacking the weakness in the XMAS data is to find the first number in the list (after the preamble) 
     * which is not the sum of two of the 25 numbers before it. What is the first number that does not have this property?
     */
    $numbers = xplode_input($input, true);
    $index = get_number_index($numbers);
    return "No sum before: {$numbers[$index]}\n";
}


function solve_two($input) : string
{
    /**
     * add together the smallest and largest number in this contiguous range; in this example, these are 15 and 47, producing 62.
     */
    $numbers = xplode_input($input, true);
    $index = get_number_index($numbers);

    for ($i = 0; $i < count($numbers); $i++) {
        if ($i < $index) { // of course
            $check = calc_sum(array_slice($numbers, $i), $numbers[$index]);
            if (!empty($check)) {
                return sprintf("Result: %d\n", $check[0] + $check[1]);
            }
        }        
    }
}

/**
 *
 * @param array $numbers
 * @param integer $compare
 * @return array
 */
function calc_sum(array $numbers, int $compare) : array
{
    $smallest = 0;
    $highest = 0;
    $sum = 0;
    for ($i = 0; $i<count($numbers); $i++) {
        if ($i == 0) {
            $smallest = $numbers[$i];
            $highest = $numbers[$i];
        } else {
            if ($numbers[$i] < $smallest) {
                $smallest = $numbers[$i];
            }
            if ($numbers[$i] > $highest) {
                $highest = $numbers[$i];
            }
        }
        $sum += $numbers[$i];
        if ($sum == $compare) {
            // stop if we reached the target sum
            return [$smallest, $highest];
        } elseif ($sum > $compare) {
            // if the sum is higher try with another starting point
            return [];
        }
    }
    
}

/**
 *
 * @param array $numbers
 * @return integer
 */
function get_number_index(array $numbers) : int 
{
    for ($i = 0; $i<count($numbers); $i++) {
        // start checking after the preamble
        if ($i > 24) {
            if (!check_number(array_slice($numbers, $i-25, 25), $numbers[$i])) {
                return $i;
            }
        }
    }
}

/**
 *
 * @param array $numbers
 * @param integer $current
 * @return boolean
 */
function check_number(array $numbers, int $current): bool {
    for($i = 0; $i < 25; $i++) {
        $test = $current-$numbers[$i];
        if (in_array($test, $numbers)) {
            return true;
        }
    }
    return false;
}