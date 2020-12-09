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

    $trees = 0;
    // turn the map into a grid of arrays
    $rows = array_map('str_split', xplode_input($input));
    $previousRow = 0;
    $previousIndex = 0;

    // Movement is: current_row[previous_index+3], next_row[previous_index+3] and so on.
    // Items then are at next_row[previous_index+3]

    for ($i = 0; $i < count($rows); $i++) {
        $rowLen = count($rows[$i]);
        $x = $previousIndex+3;
        $y = $previousRow+1;
        
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
    return sprintf("Trees: %d\n", $trees);
}


function solve_two($input) : string
{
    return "";
}