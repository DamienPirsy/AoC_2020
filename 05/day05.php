<?php


function solve_one($input) : string
{
    /**
     * Instead of zones or groups, this airline uses binary space partitioning to seat people. A seat might be specified like FBFBBFFRLR, 
     * where F means "front", B means "back", L means "left", and R means "right".
     * The first 7 characters will either be F or B; these specify exactly one of the 128 rows on the plane (numbered 0 through 127). 
     * Each letter tells you which half of a region the given seat is in. Start with the whole list of rows; 
     * the first letter indicates whether the seat is in the front (0 through 63) or the back (64 through 127). 
     * The next letter indicates which half of that region the seat is in, and so on until you're left with exactly one row.
     * The last three characters will be either L or R; these specify exactly one of the 8 columns of seats on the plane (numbered 0 through 7). 
     * The same process as above proceeds again, this time with only three steps. 
     * L means to keep the lower half, while R means to keep the upper half.
     * 
     * Every seat also has a unique seat ID: multiply the row by 8, then add the column. 
     * In this example, the seat has ID 44 * 8 + 5 = 357.
     * 
     * As a sanity check, look through your list of boarding passes. 
     * What is the highest seat ID on a boarding pass?
     */
    $items = array_map('str_split', xplode_input($input));

    $ids = [];
    foreach ($items as $item) {
        $min = 0;
        $max = 127;
        for ($i = 0; $i < 7; $i++) {
            list($min, $max) = get_position($item[$i], $min, $max);
        }
        if ($min != $max) {
            die('Row error!');
        }
        $row = $min;

        $min = 0;
        $max = 7;
        for ($j=7; $j < 10; $j++) {
            list($min, $max) = get_position($item[$j], $min, $max);
        }
        if ($min != $max) {
            die('Column error!');
        }        
        $col = $min;
        array_push($ids, ($row*8) + $col);
    }
    rsort($ids);
    return sprintf("Highest id: %d\n", $ids[0]);
}


function solve_two($input) : string
{
    return "";
}

/**
 *
 * @param string $letter
 * @param integer $min
 * @param integer $max
 * @return array
 */
function get_position(string $letter, int $min, int $max) {
    if ($letter == 'F' || $letter == 'L') {
        return [$min, intval(($min+$max)/2)];
    } else {
        return [intval(($min+$max)/2)+1, $max];
    }
}