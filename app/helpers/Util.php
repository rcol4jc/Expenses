<?php

//Misc functions to help

//check amount for proper format (XXXXXXX.XX)

function checkMoneyAmount($amount){
    if (preg_match('/^\$?[0-9]+(\.[0-9][0-9])?$/',$amount)){
        return true;
    } else {
        return false;
    }
}