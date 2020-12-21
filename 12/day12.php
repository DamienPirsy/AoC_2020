<?php


define('DIRECTIONS', [
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
]
);

function solve_one(string $input) : string
{    
    $facingDirection = 'E';
    $instructions = xplode_input($input);
    //                             n
    // move on a Cartesian plane w-|-e
    //
    $plane = ['x' => 0, 'y' => 0];
    foreach ($instructions as $instruction) {
        [$direction, $amount] = get_movements($instruction);
        
        if ($direction == 'F') {
            $direction = $facingDirection;
        } elseif ($direction == 'L' or $direction == 'R') {
            // change facing direction
            $facingDirection = get_rotated_dir($facingDirection, $direction, $amount);
            continue;
        }
        move_ship($direction, $amount, $plane);
    }
    return sprintf("Manhattan distance: %d\n", abs($plane['x']) + abs($plane['y']));
}

/**
 *
 * @param string $direction
 * @param integer $amount
 * @param array $plane
 * @return void
 */
function move_ship(string $direction, int $amount, array &$plane): void
{
    switch($direction) {
        case 'E' :
            $plane['y'] += $amount;
            break;
        case 'W' :
            $plane['y'] -= $amount;
            break;
        case 'N' :
            $plane['x'] += $amount;
            break;
        case 'S' :
            $plane['x'] -= $amount;
            break;
    }
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

/**
 *
 * @param string $currentDirection
 * @param string $rotationDir
 * @param integer $amount
 * @return string
 */
function get_rotated_dir(string $currentDirection, string $rotationDir, int $amount) : string
{
    if ($amount == 180) {
        $newDir = DIRECTIONS[$currentDirection]['opposite'];
    } elseif ($amount == 360) {
        $newDir = $currentDirection;
    } else {
        $newDir = DIRECTIONS[$currentDirection][$rotationDir][$amount];
    }
    return $newDir;
}


function solve_two(string $input) : string
{    
    $waypoint = [
        'E' => 10,
        'W' => 0,
        'N' => 1,
        'S' => 0
    ];

    // starting position
    $plane = ['x' => 0, 'y' => 0];

    $instructions = xplode_input($input);
    foreach ($instructions as $instruction) {
        [$direction, $amount] = get_movements($instruction);

        if ($direction == 'F') {
            foreach ($waypoint as $dir => $len) {
                move_ship($dir, $amount * $len, $plane);
            }
        } elseif ($direction == 'L' || $direction == 'R') {     

            $newWaypoint = [];           
            foreach ($waypoint as $d => $len) {
                $newWaypoint[ get_rotated_dir($d, $direction, $amount) ] = $len;
            }
            $waypoint = $newWaypoint;

        } else {
            $waypoint[$direction] += $amount;
        }

    }
    return sprintf("Manhattan distance: %d\n", abs($plane['x']) + abs($plane['y']));
}