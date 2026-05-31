<?php

if (! function_exists('formatDecimal')) {

    function formatDecimal(
        $value,
        int $precision = 4
    ): string {

        return rtrim(
            rtrim(
                number_format(
                    (float) $value,
                    $precision,
                    ',',
                    '.'
                ),
                '0'
            ),
            ','
        );
    }
}