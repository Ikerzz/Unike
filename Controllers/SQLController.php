<?php

namespace App\Controllers;

class SQLController
{
    public function OpenConnection()
    {
        $serverName = "DESKTOP-7OFLMFB\SQLEXPRESS";
        $connectionOptions = array("Database"=>"Unike",
            "Uid"=>"sa", "PWD"=>"sa");
        $conn = sqlsrv_connect($serverName, $connectionOptions);
        if($conn == false)
            print_r(sqlsrv_errors());

        return $conn;
    }

}