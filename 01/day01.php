<?php


function solve_one($input) : string
{
    /**
     * Find the two entries that sum to 2020; what do you get if you multiply them together?
     */

    // No need actually to cast to integer in PHP, but I'll do it anyway
    $items = array_map(function($item) {
        return (int)$item;
    }, xplode_input($input));

    // My idea: take a number, find its complement to 2020 and see if it's already in the list; if it is, that's the
    // required elements for the challenge; if it's not, test the next item in the array and so on.

    foreach ($items as $item) {
        $companion = 2020 -$item;
        if (in_array($companion, $items)) {
            echo sprintf("Tuple found: %d and %d \n", $item, $companion);
            return sprintf("%d\n", $companion * $item);
        }
    }
}

function solve_two($input) : string
{
    return "";
}