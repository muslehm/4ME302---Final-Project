<?php

class myDB
{
    private $mysqli;
    public function __construct()
    {
        $this->mysqli = new mysqli('sql205.epizy.com', 'epiz_24562454', 'Yef07FoFA2O', 'epiz_24562454_pd_db');

    }
    public function query($sql)
    {
        return $this->mysqli->query($sql);
    }


}
?>