<?php

/*$input = "mask = XXXXXXXXXXXXXXXXXXXXXXXXXXXXX1XXXX0X
mem[8] = 11
mem[7] = 101
mem[8] = 0";*/
/*$input = "mask = 000000000000000000000000000000X1001X
mem[42] = 100
mask = 00000000000000000000000000000000X0XX
mem[26] = 1";*/


/**
 *
 * @param string $input
 * @return string
 */
function solve_one(string $input) : string
{
    $operations = get_operations(xplode_input($input));
    $memory = [];
    foreach ($operations as $operation) {
        apply_mask($operation['mask'], $operation['ops'], $memory);
    }
    return sprintf("Sum: %d\n", array_sum($memory));
}

/**
 *
 * @param string $mask
 * @param array $values
 * @param array $memory
 * @return void
 */
function apply_mask(string $mask, array $values, array &$memory) : void
{
    $positions = [];
    foreach (str_split($mask) as $i => $char) {
        if ($char === '0' or $char === '1') {
            $positions[$i] = $char;
        }
    }
    foreach ($values as $address => &$value) {
        foreach($positions as $index => $bit) {
            $value[$index] = $bit;
        }
        $memory[$address] = bindec($value);
    }
}

/**
 *
 * @param string $mask
 * @param int $val
 * @return array
 */
function apply_mask_to_address(string $mask, int $val) : array
{
    $addresses = [''];
    $memoryAddress = sprintf("%036b", $val);
    for ($i = 0; $i < 36; $i++) {
        switch($mask[$i]) {
            case '0':
                $addresses = array_map(fn ($addr) => $addr .= $memoryAddress[$i], $addresses);
                break;
            case '1':
                $addresses = array_map(fn ($addr) => $addr .= '1', $addresses);
                break;
            case 'X':
                $new_addresses = [];
                foreach ($addresses as $address) {
                    $new_addresses[] = $address . '0';
                    $new_addresses[] = $address . '1';
                }
                $addresses = $new_addresses;
                break;
        }
    }
    return array_map('bindec', $addresses);
}

/**
 *
 * @param string $input
 * @return string
 */
function solve_two(string $input) : string
{
    $operations = get_operations(xplode_input($input));
    
    $memory = [];
    foreach ($operations as $operation) {
        foreach ($operation['ops'] as $memAddr => $val) {
            foreach (apply_mask_to_address($operation['mask'], $memAddr) as $address) {
                $memory[$address] = bindec($val);
            }

        }
    }
    return sprintf("Sum: %d\n", array_sum($memory));
}

/**
 *
 * @param array $commands
 * @return array
 */
function get_operations(array $commands) : array
{
    $temp = [];
    $i = -1;
    foreach ($commands as $command) {
        if (preg_match('/(mask) = ([0-1X]{36})/', $command, $matches)) {
            $i++;
            $temp[$i]['mask'] = $matches[2];
        } elseif(preg_match('/mem\[(\d+)\] = (\d+)/', $command, $matches)) {
            $temp[$i]['ops'][$matches[1]] = sprintf("%036b", $matches[2]); // padded to 36bit
        }
    }
    return $temp;
}