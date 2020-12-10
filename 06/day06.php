<?php


function solve_one($input) : string
{
    /**
     * For each group, count the number of questions to which anyone answered "yes". What is the sum of those counts?
     */

    $answers = group_lines($input);
    // I found the cool PHP function count_chars() https://www.php.net/manual/en/function.count-chars.php
    // By passing 3 it will return a string containing all unique characters is returned.
    // Now, I just need to transform the answers of each person into one string and I can run count_chars() on it.
    // (Alternatively, there was also the array index method, if this function didn't exist)

    $total = 0;
    foreach ($answers as $group) {
        $chars = '';
        foreach ($group as $person) {
            $chars .= $person;
        }
        $total += strlen(count_chars($chars, 3));
    }
    return sprintf("Result: %d\n", $total);
}


function solve_two($input) : string
{
    $answers = group_lines($input);
    $total = 0;
    foreach ($answers as $group) {
        // if the group has only 1 person, all answers qualify
        $groupLen = count($group);
        if ($groupLen == 1) {
            $total += strlen($group[0]);
        } else {
            // Here I'm turning again at the plethora of array functions in PHP. array_intersect() is promising,
            // so I apply it to every element in the $group array (after converting from string to a list)
            // The result is an array with the common elements in every single array; counting it returns the 
            // total I need for the puzzle.
            $intersect = call_user_func_array('array_intersect', array_map('str_split', $group));
            $total += count($intersect);
        }
        
    }
    return sprintf("Result: %d\n", $total);
}