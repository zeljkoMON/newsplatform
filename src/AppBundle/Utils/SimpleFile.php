<?php
// src/AppBundle/Utils/SimpleFile.php

namespace AppBundle\Utils;


class SimpleFile
{
    public function appendLine($file, $data)
    {
        $myfile = fopen($file, "a") or die("Unable to open file!");
        $text = $data . PHP_EOL;
        fwrite($myfile, $text);
        fclose($myfile);
        return true;

    }
}