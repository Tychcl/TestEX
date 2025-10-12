<?php
#[Attribute]
class RoutePrefix {
    public string $prefix;

    public function __construct(string $prefix) {
        $this->prefix = $prefix;
    }
}
?>