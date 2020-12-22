<?php


/* $input = "939
7,13,x,x,59,x,31,19"; */

function solve_one(string $input) : string
{
    [$timestamp, $ids] = xplode_input($input);
    $buses = array_filter(explode(',', $ids), function($bus) {
        return $bus != 'x';
    });
    $departures = [];
    foreach ($buses as $bus) {
        $x = 0;
        while($x < $timestamp) {
            $departures[$bus] = $x;
            $x = $x+$bus;
        }
        $departures[$bus] += $bus; // add another route to get the closest departure
    }

    asort($departures); // sort array from closer to departure time
    $closest = array_slice($departures, 0, 1, true); //get first element
    $minutes = array_keys($closest)[0] * (array_values($closest)[0]- (int)$timestamp);
    return sprintf("Result: %d\n",$minutes);
}

function solve_two(string $input) : string
{
    [$_, $ids] = xplode_input($input); // first line is useless here
    $buses = array_filter(explode(',', $ids), function($bus) {
        return $bus != 'x';
    });

    $i = 0;
    $first = array_slice($buses, 0, 1, true); //get first element
    $step = array_values($first)[0];
    foreach ($buses as  $index => $bus) {
        while (true) {
            if ( ($i+$index) % $bus === 0) {
                $step *= $bus;
                break;
            }
            $i += $step;
        }
    }   
    return sprintf("Result: %d\n",$i);
}