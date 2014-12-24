<?php

require_once __DIR__."/../../Mongo/MongoDocument.php";
class LyDocument extends MongoDocument
{
    protected $db = 'acquisition';
    protected $collection = 'scenic';
}

