<?php

function setActive($page) {
    if (basename($_SERVER["SCRIPT_FILENAME"]) == $page) {
        echo " active";
    }
}