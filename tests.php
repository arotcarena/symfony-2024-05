<?php

function buildArray(): iterable
{
    yield 'a';
    yield 'b';
    yield 'c';
}


$object = buildArray();

foreach($object as $o)
{
    var_dump($object);
}