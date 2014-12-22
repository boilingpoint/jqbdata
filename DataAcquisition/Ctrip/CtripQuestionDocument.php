<?php

require_once __DIR__."/../../Mongo/MongoDocument.php";
class CtripQuestionDocument extends MongoDocument
{
    protected $db = 'acquisition';
    protected $collection = 'question';
}
