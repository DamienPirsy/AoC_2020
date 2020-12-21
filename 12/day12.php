<?php


function solve_one(string $input) : string
{
    $directions = [
        'E' => [
            'opposite' => 'W',
            'L' => [
                90 => 'N',
                270 => 'S',
            ], 
            'R' => [
                90 => 'S',
                270 => 'N',
            ], 
        ],
        'W' => [
            'opposite' => 'E',
            'L' => [
                90 => 'S',
                270 => 'N',
            ], 
            'R' => [
                90 => 'N',
                270 => 'S',
            ], 
        ],
        'N' => [
            'opposite' => 'S',
            'L' => [
                90 => 'W',
                270 => 'E',
            ], 
            'R' => [
                90 => 'E',
                270 => 'W',
            ], 
        ],
        'S' => [
            'opposite' => 'N',
            'L' => [
                90 => 'E',
                270 => 'W',
            ], 
            'R' => [
                90 => 'W',
                270 => 'E',
            ], 
        ],
    ];
    $facingDirection = 'E';
    $instructions = xplode_input($input);
    //                             n
    // move on a Cartesian plane w-|-e
    //                             s 
    $x = 0;
    $y = 0;
    foreach ($instructions as $instruction) {
        [$direction, $amount] = get_movements($instruction);
        
        if ($direction == 'F') {
            $direction = $facingDirection;
        } elseif ($direction == 'L' or $direction == 'R') {            
            if ($amount == 180) {
                $direction = $directions[$facingDirection]['opposite'];
            } elseif($amount == 360) {
                $direction = $facingDirection;
            } else {
                $direction = $directions[$facingDirection][$direction][$amount];
            }
            $facingDirection = $direction;
            // no movement
            continue;
        }

        switch($direction) {
            case 'E' :
                $y += $amount;
                break;
            case 'W' :
                $y -= $amount;
                break;
            case 'N' :
                $x += $amount;
                break;
            case 'S' :
                $x -= $amount;
                break;
        }
    }
    return sprintf("Manhattan distance: %d", abs($x) + abs($y));
}

/**
 *
 * @param string $instruction
 * @return array
 */
function get_movements(string $instruction) : array
{
    preg_match('/(\w)(\d+)/i', $instruction, $match);
    return [$match[1], (int)$match[2]];
}

function solve_two(string $input) : string
{
    return "";
}