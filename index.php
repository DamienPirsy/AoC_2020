<?php

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Prophecy\Exception\Doubler\ClassNotFoundException;



/**
 * Transforms the input string into a simple list
 *
 * @param  string $string
 * @return array
 */
function xplode_input($string)
{
    return array_map('trim', explode("\n", $string));
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