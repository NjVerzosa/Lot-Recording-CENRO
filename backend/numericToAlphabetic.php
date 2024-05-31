<?php
function numericToAlphabetic($number) {
    $alphabets = range('A', 'Z');
    $result = '';

    while ($number > 0) {
        $remainder = ($number - 1) % 26; // Get the remainder after dividing by 26
        $result = $alphabets[$remainder] . $result; // Append the corresponding alphabet
        $number = floor(($number - 1) / 26); // Update the number for the next iteration
    }

    return $result;
}



?>