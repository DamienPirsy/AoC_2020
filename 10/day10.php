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


/**
 * Thanks to @https://github.com/pixelomer/AdventOfCode/blob/main/2020/Day%2010%20-%20Adapter%20Array/solution.js
 * for the solution, and the Reddit Solution thread https://www.reddit.com/r/adventofcode/comments/ka8z8x/2020_day_10_solutions/
 * for all the illuminating insights to dynamic programming
 *
 * @param string $input
 * @return string
 */
function solve_two(string $input) : string
{
    $numbers = xplode_input($input, true);
    sort($numbers);
    $highest = $numbers[count($numbers)-1];
    array_push($numbers, $highest+3); // append the device adapter to the list
    
    $cache = []; // damn memoization approach!
    return sprintf("Result: %d\n", find_combinations(0, $numbers, $highest, $cache));
}


/**
 *
 * @param integer $currentEnd
 * @param array $adapters
 * @param integer $highest
 * @param array $cache
 * @return integer
 */
function find_combinations(int $currentEnd, array $adapters, int $highest, array &$cache) : int{
    if ($currentEnd == $highest) {
        return 1;
    }
    $count = 0;
    for ($i=1; $i<=3; $i++) {
        if (in_array($currentEnd+$i, $adapters)) {
            $remaining = array_filter($adapters, function($val) use ($currentEnd, $i) {
                return $val > $currentEnd+$i;
            });            
            if (!isset($cache[$currentEnd+$i])) {
                $cache[$currentEnd+$i] = find_combinations($currentEnd+$i, $remaining, $highest, $cache);
            }
            $count += $cache[$currentEnd+$i];
        }
    }
    return $count;
}