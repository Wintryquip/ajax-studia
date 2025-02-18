<?php
$tytul = "Galeria";
$zawartosc = "<h1> Galeria </h1><p>";
$i = 0;
while($i<8) {
    if($i%4==0) {
        $zawartosc .= "<br/>";
    }
    $nazwa = 'obraz'.$i;
    $zawartosc .= "<img src='../zdjecia/$nazwa.JPG' alt='$nazwa' style='width:24%;height:auto;margin-left:0.5%;margin-right:0.5%;'>";
    $i++;
}
echo $zawartosc."</p>";