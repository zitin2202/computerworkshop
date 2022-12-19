<?php

namespace app\controllers;


function generateOrderNumber(){
    $random = rand(1, 10000000000000);
    return hash('ripemd160', $random);
}
