<?php


function solve_one($input) : string
{
    /**
     * acc increases or decreases a single global value called the accumulator by the value given in the argument. 
     * For example, 
     * - acc +7 would increase the accumulator by 7. The accumulator starts at 0. After an acc instruction, the instruction immediately below it is executed next.
     * - jmp jumps to a new instruction relative to itself. The next instruction to execute is found using the argument as an offset from the jmp instruction; 
     * for example, jmp +2 would skip the next instruction, jmp +1 would continue to the instruction immediately 
     * below it, and jmp -20 would cause the instruction 20 lines above to be executed next.
     * - nop stands for No OPeration - it does nothing. The instruction immediately below it is executed next.
     */

    [$accumulator, $loop] = loop_instructions(xplode_input($input));
    return sprintf("Last accumulator value: %d\n", $accumulator);
}


function solve_two($input) : string
{
    /**
     * Fix the program so that it terminates normally by changing exactly one jmp (to nop) or nop (to jmp). 
     * What is the value of the accumulator after the program terminates?
     */
    // basically, I need to test each modification of the original instruction until I get the correct one

    $instructions = xplode_input($input);
    for ($i = 0; $i < count($instructions); $i++) {
        [$command, $value] = get_instruction($instructions[$i]);

        if ($command == 'nop' || $command == 'jmp') {
            $newCommand = $command == 'nop' ? 'jmp '.$value : 'nop '.$value;
            $newInstructions = $instructions;
            $newInstructions[$i] = $newCommand;
            [$accumulator, $loop] = loop_instructions($newInstructions);
            if (!$loop) {
                // in case we went beyond the edge
                return sprintf("Last accumulator value: %d\n", $accumulator);
            }
        }

    }
}

/**
 *
 * @param array $instructions
 * @return array
 */
function loop_instructions(array $instructions) : array
{
    $visited = [];
    $accumulator = 0;
   
    $i = 0;
    while(true) 
    {
        if ($i == count($instructions)) {
            return [$accumulator, false];
        }
        if (in_array($i, $visited)) {
            return [$accumulator, true];
        }
        [$command, $value] = get_instruction($instructions[$i]);
        [$operation, $val] = get_number($value);

        
        array_push($visited, $i);        
        switch ($command) {
            case 'acc':
                // TODO a better way for this
                $accumulator = $operation == '+' ? ($accumulator+$val) : ($accumulator-$val);
                $i = $i+1;
                break;
            case 'jmp':
                $i = $operation == '+' ? ($i+$val) : ($i-$val);
                break;
            case 'nop':
                $i = $i+1;
                break;
        }      
    }
}

/**
 *
 * @param string $operation
 * @return array
 */
function get_number(string $operation) : array
{
    preg_match('/(\+|-)([0-9]+)/', $operation, $matches);    
    return [$matches[1], (int)$matches[2]];
}

/**
 *
 * @param string $line
 * @return array
 */
function get_instruction(string $line) : array
{
    return explode(" ", $line);
}