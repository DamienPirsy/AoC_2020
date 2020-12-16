<?php


function solve_one($input) : string
{
    /**
     * Find a chain that uses all of your adapters to connect the charging outlet to your device's built-in adapter and count the joltage
     *  differences between the charging outlet, the adapters, and your device. 
     * What is the number of 1-jolt differences multiplied by the number of 3-jolt differences?
     */
    $diffByOne = 0;
    $diffByThree = 1; // I need to take into account the device's built-in adapter!
    $numbers = xplode_input($input, true);
    
    sort($numbers);

    foreach ($numbers as $index => $val) {
        if ($index == 0) {
            if ($val-1 == 0) {
                $diffByOne++;
            } elseif($val-3 == 0) {
                $diffByThree++;
            }
        } else {
            if ($val - $numbers[$index-1] == 1) {
                $diffByOne++;
            } elseif ($val - $numbers[$index-1] == 3) {
                $diffByThree++;
            }
        }
    }
    return sprintf("Result: %d\n", $diffByOne*$diffByThree);
}


function solve_two($input) : string
{
    return "";
}