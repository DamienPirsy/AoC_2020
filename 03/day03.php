<?php


function solve_one($input) : string
{
    /**
     * The toboggan can only follow a few specific slopes (you opted for a cheaper model that prefers rational numbers); start by counting all the 
     * trees you would encounter for the slope right 3, down 1:
     * From your starting position at the top-left, check the position that is right 3 and down 1. 
     * Then, check the position that is right 3 and down 1 from there, and so on until you go past the bottom of the map.
     * The locations you'd check in the above example are marked here with O where there was an open square and X where there was a tree:
     * 
     * ..##.........##.........##.........##.........##.........##.......  --->
     * #..O#...#..#...#...#..#...#...#..#...#...#..#...#...#..#...#...#..
     * .#....X..#..#....#..#..#....#..#..#....#..#..#....#..#..#....#..#.
     * ..#.#...#O#..#.#...#.#..#.#...#.#..#.#...#.#..#.#...#.#..#.#...#.#
     * .#...##..#..X...##..#..#...##..#..#...##..#..#...##..#..#...##..#.
     * ..#.##.......#.X#.......#.##.......#.##.......#.##.......#.##.....  --->
     * .#.#.#....#.#.#.#.O..#.#.#.#....#.#.#.#....#.#.#.#....#.#.#.#....#
     * .#........#.#........X.#........#.#........#.#........#.#........#
     * #.##...#...#.##...#...#.X#...#...#.##...#...#.##...#...#.##...#...
     * #...##....##...##....##...#X....##...##....##...##....##...##....#
     * .#..#...#.#.#..#...#.#.#..#...X.#.#..#...#.#.#..#...#.#.#..#...#.#  --->
     * In this example, traversing the map using this slope would cause you to encounter 7 trees.
     * Starting at the top-left corner of your map and following a slope of right 3 and down 1, how many trees would you encounter?
     */

    // Movement is: current_row[previous_index+3], next_row[previous_index+3] and so on.
    // Items then are at next_row[previous_index+3]

    $trees = solve_by($input, 3, 1);
    return sprintf("Trees: %d\n", $trees);
}


function solve_two($input) : string
{
    /**
     * Time to check the rest of the slopes - you need to minimize the probability of a sudden arboreal stop, after all.
     * Determine the number of trees you would encounter if, for each of the following slopes, you start at the top-left corner and traverse 
     * the map all the way to the bottom:
     * Right 1, down 1.
     * Right 3, down 1. (This is the slope you already checked.)
     * Right 5, down 1.
     * Right 7, down 1.
     * Right 1, down 2.
     * In the above example, these slopes would find 2, 7, 3, 4, and 2 tree(s) respectively; multiplied together, these produce the answer 336.
     * What do you get if you multiply together the number of trees encountered on each of the listed slopes?
     */

    $result = 1;
    foreach ( [[1,1], [3,1], [5, 1], [7,1], [1,2]] as $testRun ) {
        $result = $result * solve_by($input, $testRun[0], $testRun[1]);
    } 
    return sprintf("Result: %d\n", $result);
}


/**
 * Edit: since part one & two works turned out to be equal I made a common method for solving the puzzle
 *
 * @param  string $input
 * @param  int $xMovement
 * @param  int $yMovement
 * @return int
 */
function solve_by($input, $xMovement, $yMovement) 
{
    // turn the map into a grid of arrays
    $rows = array_map('str_split', xplode_input($input));

    $trees = 0;
    $previousRow = 0;
    $previousIndex = 0;

    for ($i = 0; $i < count($rows); $i++) {
        $rowLen = count($rows[$i]);
        $x = $previousIndex + $xMovement;
        $y = $previousRow + $yMovement;
        
        if (isset($rows[$y])) { // if we've not reached the end of the map

            if (!isset($rows[$y][$x])) { // since map repeats horizontally, when we go over the right edge it's like starting again from the left
                $x = $x - $rowLen;
            }
            if ($rows[$y][$x] == '#') {
                $trees++;
            }
            $previousIndex = $x;
            $previousRow = $y;
        }    
    }
    return $trees;
}