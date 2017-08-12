<?php
/*
Frame rates
- 23.976216
- 23.976024
- 23.976
- 24
- 29.97
- 30
- 60

https://en.wikipedia.org/wiki/SubRip
*/

// get a base time upon which to base everything
date_default_timezone_set('UTC');
define( 'BASE_IN_SECONDS', strtotime( date( 'Y-m-d' ) ) );
define( 'FROMFPS', 24.47 );
define( 'TOFPS', 23.976216 );
define( 'INPUT_FILE', dirname(__FILE__) . '/This Is Us S01E14 copy.srt' );
define( 'OUTPUT_FILE', dirname(__FILE__) . '/This Is Us S01E14.srt' );
/*
    Test cases
    
    Given timecode 00:14:11,021
    And input frame rate is 25
    When desired frame rate is 23.976024
    Then new timecode is 00:14:47,367
    
    echo date('H:i:s', strtotime('00:14:11'));
    
    851.021 x 25 = 21275.525 frames
    n x 23.976024 = 21275.525 frames, n = 21275.525 / 23.976024, n = 887.367
*/

function shift_timecodes( $in ) {
    // 00:00:00,869 --> 00:00:02,844
    echo $in;
    $parts = preg_split( '/ +\-\-\> +/', $in );
    $start_in = $parts[0];
    $end_in = $parts[1];
    $start_in_parts = explode( ',', $start_in );
    $end_in_parts = explode( ',', $end_in );
    var_dump($start_in_parts);
    $start_in = $start_in_parts[0];
    $start_in_mil = $start_in_parts[1];
    $end_in = $end_in_parts[0];
    $end_in_mil = $end_in_parts[1];
    $start_in_seconds = strtotime( $start_in ) - BASE_IN_SECONDS;
    $end_in_seconds = strtotime( $end_in ) - BASE_IN_SECONDS;
    echo "start_in_seconds = $start_in_seconds\n";
    echo "end_in_seconds = $end_in_seconds\n";
    $start_in_frames = floatval($start_in_seconds . '.' . $start_in_mil) * FROMFPS;
    echo "start_in_frames = $start_in_frames\n";
    
    $start_out_seconds = $start_in_frames / TOFPS;
    echo "start_out_seconds = $start_out_seconds\n";
    $start_out = (int)$start_out_seconds;
    $start_out_mil = str_pad(round(fmod( $start_out_seconds, 1 ), 3), 3, '0' );
    echo "start_out = $start_out\n";
    echo "start_out_mil = $start_out_mil\n";
  
    $end_in_frames = floatval($end_in_seconds . '.' . $end_in_mil) * FROMFPS;
    $end_out_seconds = $end_in_frames / TOFPS;
    $end_out = (int)$end_out_seconds;
    $end_out_mil = str_pad(round(fmod( $end_out_seconds, 1 ), 3), 3, '0' );
    
    return date( 'H:i:s', $start_out ) . ',' . substr( $start_out_mil, 2 ) . ' --> ' 
        . date( 'H:i:s', $end_out ) . ',' . substr( $end_out_mil, 2 );
}

$input_handle = fopen( INPUT_FILE, 'r' );
$output_handle = fopen( OUTPUT_FILE, 'w' );
if( $input_handle ) {
    while( ( $line_in = fgets( $input_handle ) ) !== false ) {
        // process the line read.
        switch ( preg_match( '/^[0-9]{2}:[0-9]{2}:[0-9]{2},[0-9]{3} +\-\-\> +[0-9]{2}/', trim( $line_in ) ) ) {
            case 1:
                $line_out = shift_timecodes( $line_in ) . "\n";
                echo $line_out;
                break;
            default:
                $line_out = $line_in;
                break;
        }
        fwrite( $output_handle, $line_out );
    }
    fclose( $input_handle );
    fclose( $output_handle );
    echo 'Done.';
} else {
    // error opening the file.
    echo 'Error reading the input file.';
} 