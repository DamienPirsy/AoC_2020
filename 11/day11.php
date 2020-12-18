<?php

use phpDocumentor\Reflection\Types\Callable_;

function solve_one(string $input) : string
{
    /**
     * If a seat is empty (L) and there are no occupied seats adjacent to it, the seat becomes occupied.
     * If a seat is occupied (#) and four or more seats adjacent to it are also occupied, the seat becomes empty.
     * Otherwise, the seat's state does not change.
     * Simulate your seating area by applying the seating rules repeatedly until no seats change state. 
     * How many seats end up occupied?
     */

    $seats = array_map('str_split', xplode_input($input));
    $limit = 4;
    while(true) {
        [$seats, $hasChanged, $occupied] = walk_matrix($seats, $limit, 'occupied_neighbors');
        if (!$hasChanged) {
            return sprintf("Occupied seats: %d\n", $occupied);
        }
    }
}

/**
 * Undocumented function
 *
 * @param array $seats
 * @param string $stringRep
 * @param int $limit
 * @param Callable $method
 * @return array
 */
function walk_matrix(array $seats, int $limit, Callable $method): array 
{
    $new = [];
    $hasChanged = false;
    $occupied = 0;

    $h = count($seats);
    $w = count($seats[0]);    
    foreach ($seats as $y => $row) {        
        foreach ($row as $x => $place) {
            if ($place != '.') {
                if ($place == '#') {
                    $occupied++;
                }
                $occupiedNeighbors = $method($seats, $x, $y, $h, $w);
                if ($place == 'L' && $occupiedNeighbors === 0) {
                    $new[$y][$x] = '#';
                    $hasChanged = true;
                    $occupied++;
                } elseif ($place == '#' && $occupiedNeighbors >= $limit) {
                    $new[$y][$x] = 'L';
                    $hasChanged = true;
                    $occupied--;
                } else {
                    $new[$y][$x] = $place;
                }
            } else {
                // leave as is
                $new[$y][$x] = $place;
            }

        }
    }
    return [$new, $hasChanged, $occupied];
}

/**
 * @param array $seats
 * @param integer $placeX
 * @param integer $placeY
 * @param integer $w
 * @param integer $h
 * @return int
 */
function occupied_neighbors(array $seats, int $placeX, int $placeY, int $w, int $h) : int 
{
    $n = [
        $seats[$placeY-1][$placeX-1] ?? '',
        $seats[$placeY-1][$placeX]   ?? '',
        $seats[$placeY-1][$placeX+1] ?? '',

        $seats[$placeY][$placeX-1] ?? '',
        $seats[$placeY][$placeX+1] ?? '',

        $seats[$placeY+1][$placeX-1] ?? '',
        $seats[$placeY+1][$placeX]   ?? '',
        $seats[$placeY+1][$placeX+1] ?? '',
    ];
    return array_count_values($n)['#'] ?? 0;
}

/**
 *
 * @param array $seats
 * @param integer $placeX
 * @param integer $placeY
 * @return integer
 */
function visible_neighbors(array $seats, int $placeX, int $placeY, int $w, int $h) : int
{
    $occupied = 0;

    $y = $placeY;
    $x = $placeX;
    while ($y >= 0 && $x >= 0) {
        $x = $x-1;
        $y = $y-1;
        if (isset($seats[$y][$x]) && $seats[$y][$x] != '.') {
            if ($seats[$y][$x] == '#') {
                $occupied++;
            }
            break;
        }
    }
    //top (y-i, x) untiil y=0
    $y = $placeY;
    $x = $placeX;    
    while ($y >= 0) {
        $y = $y-1;
        if (isset($seats[$y][$x]) && $seats[$y][$x] != '.') {
            if ($seats[$y][$x] == '#') {
                $occupied++;
            }
            break;
        }
    }
    //topright (y-, x+i) until y=0, x = width
    $y = $placeY;
    $x = $placeX;    
    while ($y >= 0 && $x<=$w) {
        $y = $y-1;
        $x = $x+1;
        if (isset($seats[$y][$x]) && $seats[$y][$x] != '.') {
            if ($seats[$y][$x] == '#') {
                $occupied++;
            }
            break;
        }
    }

    //left (y, x-i) until x = 0
    $y = $placeY;
    $x = $placeX;    
    while ($x >= 0) {
        $x = $x-1;
        if (isset($seats[$y][$x]) && $seats[$y][$x] != '.') {
            if ($seats[$y][$x] == '#') {
                $occupied++;
            }
            break;
        }
    }

    //right (y, x+i) untiil x = w
    $y = $placeY;
    $x = $placeX;    
    while ($x <= $w) {
        $x = $x+1;
        if (isset($seats[$y][$x]) && $seats[$y][$x] != '.') {
            if ($seats[$y][$x] == '#') {
                $occupied++;
            }
            break;
        }
    }

    // bottomleft (y+i, x-i) until y=h, x=0
    $y = $placeY;
    $x = $placeX;    
    while ($x >= 0 && $y <= $h) {
        $x = $x-1;
        $y = $y+1;
        if (isset($seats[$y][$x]) && $seats[$y][$x] != '.') {
            if ($seats[$y][$x] == '#') {
                $occupied++;
            }
            break;
        }
    }

    // bottom (y+i, x) until y = h
    $y = $placeY;
    $x = $placeX;    
    while ($y <= $h) {
        $y = $y+1;
        if (isset($seats[$y][$x]) && $seats[$y][$x] != '.') {
            if ($seats[$y][$x] == '#') {
                $occupied++;
            }
            break;
        }
    }
    
    //bottomright (y+1, x+1) until y = h, x = w
    $y = $placeY;
    $x = $placeX;    
    while ($y <= $h && $x <= $w) {
        $x = $x+1;
        $y = $y+1;
        if (isset($seats[$y][$x]) && $seats[$y][$x] != '.') {
            if ($seats[$y][$x] == '#') {
                $occupied++;
            }
            break;
        }
    }
    return $occupied;
}

function solve_two(string $input) : string
{
    /**
     * As soon as people start to arrive, you realize your mistake. People don't just care about adjacent seats - 
     * they care about the first seat they can see in each of those eight directions!
     * 
     * Also, people seem to be more tolerant than you expected: it now takes five or more visible occupied seats for 
     * an occupied seat to become empty (rather than four or more from the previous rules). 
     * The other rules still apply: empty seats that see no occupied seats become occupied, seats matching no rule don't change, 
     * and floor never changes.
     */

     $seats = array_map('str_split', xplode_input($input));
    $limit = 5;
    while(true) {
        [$seats, $hasChanged, $occupied] = walk_matrix($seats, $limit, 'visible_neighbors');
        if (!$hasChanged) {
            return sprintf("Occupied seats: %d\n", $occupied);
        }
    }
}

