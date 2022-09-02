<?php

function getContrastColor($hexColor) {
        // hexColor RGB
        $R1 = hexdec(substr($hexColor, 1, 2));
        $G1 = hexdec(substr($hexColor, 3, 2));
        $B1 = hexdec(substr($hexColor, 5, 2));

        // Black RGB
        $blackColor = "#000000";
        $R2BlackColor = hexdec(substr($blackColor, 1, 2));
        $G2BlackColor = hexdec(substr($blackColor, 3, 2));
        $B2BlackColor = hexdec(substr($blackColor, 5, 2));

         // Calc contrast ratio
         $L1 = 0.2126 * pow($R1 / 255, 2.2) +
               0.7152 * pow($G1 / 255, 2.2) +
               0.0722 * pow($B1 / 255, 2.2);

        $L2 = 0.2126 * pow($R2BlackColor / 255, 2.2) +
              0.7152 * pow($G2BlackColor / 255, 2.2) +
              0.0722 * pow($B2BlackColor / 255, 2.2);

        $contrastRatio = 0;
        if ($L1 > $L2) {
            $contrastRatio = (int)(($L1 + 0.05) / ($L2 + 0.05));
        } else {
            $contrastRatio = (int)(($L2 + 0.05) / ($L1 + 0.05));
        }

        // If contrast is more than 5, return black color
        if ($contrastRatio > 5) {
            return '#000000';
        } else { 
            // if not, return white color.
            return '#FFFFFF';
        }
}

function adjustBrightness($hexCode, $adjustPercent) {
    $hexCode = ltrim($hexCode, '#');

    if (strlen($hexCode) == 3) {
        $hexCode = $hexCode[0] . $hexCode[0] . $hexCode[1] . $hexCode[1] . $hexCode[2] . $hexCode[2];
    }

    $hexCode = array_map('hexdec', str_split($hexCode, 2));

    foreach ($hexCode as & $color) {
        $adjustableLimit = $adjustPercent < 0 ? $color : 255 - $color;
        $adjustAmount = ceil($adjustableLimit * $adjustPercent);

        $color = str_pad(dechex($color + $adjustAmount), 2, '0', STR_PAD_LEFT);
    }

    return '#' . implode($hexCode);
}

function hex2rgba($color, $opacity = false) {

    $default = 'rgb(0,0,0)';
    
    // Return default if no color provided
    if( empty( $color ) ) {
        return $default;
    }

    // Sanitize $color if "#" is provided 
    if( $color[0] == '#' ) {
        $color = substr( $color, 1 );
    }

    // Check if color has 6 or 3 characters and get values
    if( strlen( $color ) == 6 ) {
        $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
    } elseif( strlen( $color ) == 3 ) {
        $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
    } else {
        return $default;
    }

    // Convert hexadec to rgb
    $rgb = array_map( 'hexdec', $hex );

    // Check if opacity is set(rgba or rgb)
    if( $opacity ) {
        if( abs( $opacity ) > 1 ) {
            $opacity = 1.0;	
        }
        if( preg_match("/^[0-9,]+$/", $opacity) ) {
            $opacity = str_replace(',', '.', $opacity);				
        }
        $output = 'rgba(' . implode( ",", $rgb ) . ',' . $opacity . ')';
    } else {
        $output = 'rgb(' . implode( ",", $rgb ) . ')';
    }

    // Return rgb(a) color string
    return $output;
    
}

function getCSS($hex, $name) {

    return 
".alert-".$name." {
    color: ". adjustBrightness($hex, -0.5) ." !important;
    background-color: ". adjustBrightness($hex, 0.8) ." !important;
    border-color: ". adjustBrightness($hex, 0.7) ." !important;
}

.alert-".$name." hr {
    border-top-color: ". adjustBrightness($hex, 0.6) ." !important;
}

.alert-".$name." .alert-link {
    color: ". adjustBrightness($hex, -0.7) ." !important;
}

.badge-".$name." {
    color: ". getContrastColor($hex) ." !important;
    background-color: ".$hex." !important;
}

.badge-".$name."[href]:hover, .badge-".$name."[href]:focus {
    color: ". getContrastColor($hex) ." !important;
    background-color: ". adjustBrightness($hex, -0.2) ." !important;
}

.bg-".$name." {
    color: ". getContrastColor($hex) ." !important;
    background-color: ".$hex." !important;
}

a.bg-".$name.":hover, a.bg-".$name.":focus,
button.bg-".$name.":hover,
button.bg-".$name.":focus {
    background-color: ". adjustBrightness($hex, -0.2) ." !important;
}

.border-".$name." {
    border-color: ".$hex." !important;
}

.btn-".$name." {
    color: ". getContrastColor($hex) ." !important;
    background-color: ".$hex." !important;
    border-color: ".$hex." !important;
}

.btn-".$name.":hover {
    color: ". getContrastColor($hex) ." !important;
    background-color: ". adjustBrightness($hex, -0.1) ." !important;
    border-color: ".$hex." !important;
}

.btn-".$name.":focus, .btn-".$name.".focus {
    box-shadow: 0 0 0 0.2rem ". hex2rgba($hex, 0.5 ) ." !important;
}

.btn-".$name.".disabled, .btn-".$name.":disabled {
    color: ". getContrastColor($hex) ." !important;
    background-color: ".$hex." !important;
    border-color: ".$hex." !important;
}

.btn-".$name.":not(:disabled):not(.disabled):active, .btn-".$name.":not(:disabled):not(.disabled).active, .show > .btn-".$name.".dropdown-toggle {
    color: ". getContrastColor($hex) ." !important;
    background-color: ". adjustBrightness($hex, -0.2) ." !important;
    border-color: ". adjustBrightness($hex, -0.3) ." !important;
}

.btn-".$name.":not(:disabled):not(.disabled):active:focus, .btn-".$name.":not(:disabled):not(.disabled).active:focus, .show > .btn-".$name.".dropdown-toggle:focus {
    box-shadow: 0 0 0 0.2rem ". hex2rgba($hex, 0.5 ) ." !important;
}

.btn-outline-".$name." {
    color: ".$hex." !important;
    background-color: transparent !important;
    border-color: ".$hex." !important;
}

.btn-outline-".$name.":hover {
    color: ". getContrastColor($hex) ." !important;
    background-color: ".$hex." !important;
    border-color: ".$hex." !important;
}

.btn-outline-".$name.":focus, .btn-outline-".$name.".focus {
    box-shadow: 0 0 0 0.2rem ". hex2rgba($hex, 0.5 ) ." !important;
}

.btn-outline-".$name.".disabled, .btn-outline-".$name.":disabled {
    color: ".$hex." !important;
    background-color: transparent !important;
}

.btn-outline-".$name.":not(:disabled):not(.disabled):active, .btn-outline-".$name.":not(:disabled):not(.disabled).active, .show > .btn-outline-".$name.".dropdown-toggle {
    color: ". getContrastColor($hex) ." !important;
    background-color: ".$hex." !important;
    border-color: ".$hex." !important;
}

.btn-outline-".$name.":not(:disabled):not(.disabled):active:focus, .btn-outline-".$name.":not(:disabled):not(.disabled).active:focus, .show > .btn-outline-".$name.".dropdown-toggle:focus {
    box-shadow: 0 0 0 0.2rem ". hex2rgba($hex, 0.5 ) ." !important;
}

.list-group-item-".$name." {
    color: ". adjustBrightness($hex, -0.5) ." !important;
    background-color: ". adjustBrightness($hex, 0.7) ." !important;
}

.list-group-item-".$name.".list-group-item-action:hover, .list-group-item-".$name.".list-group-item-action:focus {
    color: ". adjustBrightness($hex, -0.5) ." !important;
    background-color: ". adjustBrightness($hex, 0.6) ." !important;
}

.list-group-item-".$name.".list-group-item-action.active {
    color: ". getContrastColor($hex) ." !important;
    background-color: ". adjustBrightness($hex, -0.5) ." !important;
    border-color: ". adjustBrightness($hex, -0.5) ." !important;
}

.table-".$name." {
    --bs-table-bg: ". adjustBrightness($hex, 0.5) ." !important;
    --bs-table-striped-bg: ". adjustBrightness($hex, 0.7) ." !important;
    --bs-table-active-bg: ". adjustBrightness($hex, 0.3) ." !important;
    --bs-table-hover-bg: ". adjustBrightness($hex, 0.4) ." !important;
    border-color: ". adjustBrightness($hex, 0.2) ." !important;
}

.text-".$name." {
    color: ".$hex." !important;
}

a.text-".$name.":hover, a.text-".$name.":focus {
    color: ". adjustBrightness($hex, -0.2) ." !important;
}

.link-".$name." {
    color: ".$hex." !important;
}

a.link-".$name.":hover, a.link-".$name.":focus {
    color: ". adjustBrightness($hex, -0.2) ." !important;
}";
}

?>