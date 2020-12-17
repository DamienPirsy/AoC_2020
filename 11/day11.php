<?php


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
    while(true) {
        [$seats, $hasChanged, $occupied] = walk_matrix($seats);
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
 * @return array
 */
function walk_matrix(array $seats): array 
{
    $new = [];
    $hasChanged = false;
    $occupied = 0;
    foreach ($seats as $y => $row) {        
        foreach ($row as $x => $place) {
            if ($place == '#') {
                $occupied++;
            }
            $occupiedNeighbors = occupied_neighbors($seats, $x, $y);
            if ($place == 'L' && $occupiedNeighbors === 0) {
                $new[$y][$x] = '#';
                $hasChanged = true;
                $occupied++;
            } elseif ($place == '#' && $occupiedNeighbors >= 4) {
                $new[$y][$x] = 'L';
                $hasChanged = true;
                $occupied--;
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
 * @return int
 */
function occupied_neighbors(array $seats, int $placeX, int $placeY) : int 
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

function solve_two(string $input) : string
{
    return "";
}

