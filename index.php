<?php

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Prophecy\Exception\Doubler\ClassNotFoundException;



/**
 * Transforms the input string into a simple list
 *
 * @param  string $string
 * @param  bool  $asIntegers
 * @return array
 */
function xplode_input(string $inputString, bool $asIntegers = false) : array
{
    $items = array_map('trim', explode("\n", trim($inputString)));
    
    // No need actually to cast to integer in PHP, but I'll do it anyway
    if ($asIntegers) {
        $items = array_map(function($el) {
            return (int)$el;
        }, $items);
    }
    return $items;
}

/**
 * Groups lines when separated by a blank line
 *
 * @param string $inputString
 * @return array
 */
function group_lines (string $inputString) : array {
    $items = xplode_input($inputString);
    $group = [];
    $currentLine = [];

    foreach ($items as $item) {

        if (!empty($item)) {
            // if it's not a new line append data to the current passport
            array_push($currentLine, $item);
        } else {
            // the current passport is complete, add it to the global list and start a new one
            array_push($group, $currentLine);
            $currentLine = [];
        }
    }
    return $group;
}

/**
 * load_input
 *
 * @param  string $day
 * @return string
 */
function load_input(string $day) : string
{
    try {
        return file_get_contents(__DIR__.'/'.$day.'/input.txt');
    } catch (FileNotFoundException $e) {
        die($e->getMessage());
    }
}


/**
 * load_challenge
 *
 * @param  string $day
 * @param  string $input
 * @return string
 */
function load_challenge(string $day, string $input, int $part): string
{
    try {
        $challengeFile = __DIR__.'/'.$day.'/day'.$day.'.php';
        if (file_exists($challengeFile)) {
            include_once($challengeFile);
            if ($part == 1) {
                $result = solve_one($input);
            } else {
                $result = solve_two($input);
            }
            return $result;
        }
        throw new \Exception("No challenge file found");
        
    } catch (ClassNotFoundException $e) {
        die ($e->getMessage());
    }
}


try {
    echo load_challenge($argv[1], load_input($argv[1]), (isset($argv[2]) ? $argv[2] : 1));
} catch (\Exception $e) {
    die($e->getMessage());
}