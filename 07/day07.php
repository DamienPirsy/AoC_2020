<?php


function solve_one($input) : string
{
    /**
     * You land at the regional airport in time for your next flight. In fact, it looks like you'll even have time to grab some food: all flights are currently delayed due to issues in luggage processing.

    Due to recent aviation regulations, many rules (your puzzle input) are being enforced about bags and their contents; bags must be color-coded and must contain specific quantities of other color-coded bags. Apparently, nobody responsible for these regulations considered how long they would take to enforce!

    For example, consider the following rules:

    light red bags contain 1 bright white bag, 2 muted yellow bags.
    dark orange bags contain 3 bright white bags, 4 muted yellow bags.
    bright white bags contain 1 shiny gold bag.
    muted yellow bags contain 2 shiny gold bags, 9 faded blue bags.
    shiny gold bags contain 1 dark olive bag, 2 vibrant plum bags.
    dark olive bags contain 3 faded blue bags, 4 dotted black bags.
    vibrant plum bags contain 5 faded blue bags, 6 dotted black bags.
    faded blue bags contain no other bags.
    dotted black bags contain no other bags.
    These rules specify the required contents for 9 bag types. In this example, every faded blue bag is empty, every vibrant plum bag contains 11 bags (5 faded blue and 6 dotted black), and so on.

    You have a shiny gold bag. If you wanted to carry it in at least one other bag, how many different bag colors would be valid for the outermost bag? (In other words: how many colors can, eventually, contain at least one shiny gold bag?)

    In the above rules, the following options would be available to you:

    A bright white bag, which can hold your shiny gold bag directly.
    A muted yellow bag, which can hold your shiny gold bag directly, plus some other bags.
    A dark orange bag, which can hold bright white and muted yellow bags, either of which could then hold your shiny gold bag.
    A light red bag, which can hold bright white and muted yellow bags, either of which could then hold your shiny gold bag.
    So, in this example, the number of bag colors that can eventually contain at least one shiny gold bag is 4.

    How many bag colors can eventually contain at least one shiny gold bag?
     */

    // This is quite tough. Bags look all in the form "[adjective] [color] bag", a regex to isolate them seems easy enough.
    // Then, I might find all the shiny gold bags which are contained into another bag, and then look for that bag color in the other rules.
    // I might need to create a map, something like ["bright white" => ["shiny gold"]], ["dark orange" => ["bright white", "muted yellow"]] and so on
    // Bags number seems irrelevant now

    $bagRules = xplode_input($input);
    $bagsList = [];
    foreach ($bagRules as $index => $rule) {

        $parts = explode(',', $rule);
        foreach ($parts as $part) {
            if (preg_match('/(?P<container_bag>(\w+\s\w+)) bags contain \d+ (?<bag>(\w+ \w+)) bag(s)?/', $part, $matches)) {
                $containerBag = $matches['container_bag'];
                $bagsList[$containerBag] = [$matches['bag']];
            } else {
                if (preg_match('/\d+ (?P<bag>(\w+ \w+))/', $part, $matches)) {
                    array_push($bagsList[$containerBag], $matches['bag']);
                }
            }
        }
    }

    $bags = ['shiny gold'];
    $totalBags = [];
    // No cycle until there's no container bag left
    while (!empty($bags)) {
        $bags = reduce_bags($bags, $bagsList, $totalBags);
    }
    return sprintf("Total bags: %d\n", count(array_unique($totalBags)));
}



function solve_two($input) : string
{
    return "";
}

function reduce_bags($bags, $bagsList, &$totalBags) {
    $outerBags = [];
    foreach ($bags as $bag) {
        foreach ($bagsList as $containerBag => $containedBags) {
            if (in_array($bag, $containedBags)) {
                array_push($outerBags, $containerBag);
                array_push($totalBags, $containerBag);
            }
        }
    }
    return $outerBags;
}