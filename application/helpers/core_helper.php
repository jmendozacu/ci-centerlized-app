<?php

if(!function_exists('pr')){

    function pr($arr){
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
    }
}