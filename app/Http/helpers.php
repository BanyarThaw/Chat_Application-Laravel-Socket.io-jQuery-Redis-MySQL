<?php

use League\CommonMark\Delimiter\Delimiter;

function makeImageFromName($name) {
    $userImage = "";
    $shortName = "";

    $names = explode(' ', $name);

    $shortName = $names[0][0];

    if(isset($names[1])) {
        $shortName .= $names[1][0];
    }

    $userImage .= '<div class="name-image bg-primary">'.$shortName.'</div>';

    return $userImage;
}