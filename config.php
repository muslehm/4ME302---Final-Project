<?php

class myDB
{
    private $mysqli;
    public function __construct()
    {
        $this->mysqli = new mysqli('', '', '', '');

    }
    public function query($sql)
    {
        return $this->mysqli->query($sql);
    }


}
?>
