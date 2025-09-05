<?php

namespace Classes;

class Element
{
    public int $id;
    public string $name;

    function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}

class AnyList
{
    public ?array $List = null;

    function __construct($json)
    {
        $data = json_decode($json, true);
        $this->List = array_map(function($item) {
            return new Element(intval($item['Id']), strval($item['Name']));}, 
            $data);
    }

}

?>