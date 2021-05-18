<?php
function Smilify(&$subject)
{
    $smilies = array(
        '>:-[' => 'angry',
        ":'(" => 'crying',
        ':)D' => 'happy',
        ']:->' => 'happy_devil',
        ':-*'  => 'kiss',
        '<:-('  => 'sad',
        ':)'  => 'smile',
        '8-)' => 'sunny',
        ':P'  => 'tongue',
        ';)'  => 'wink',
    );

    $sizes = array(
        'angry' => 22,
        'crying' => 22,
        'happy' => 22,
        'happy_devil' => 22,
        'kiss' => 22,
        'sad' => 22,
        'smile' => 22,
        'sunny' => 22,
        'tongue' => 22,
        'wink' => 22,
    );

    $replace = array();
    foreach ($smilies as $smiley => $imgName)
    {
        $size = $sizes[$imgName];
        array_push($replace, '<img src="emojis/'.$imgName.'.png" alt="'.$smiley.'" width="'.$size.'" height="'.$size.'" />');
    }
    $subject = str_replace(array_keys($smilies), $replace, $subject);
}