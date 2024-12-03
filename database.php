<?php

function getDatabaseConnection(): PDO {
    $db = new PDO('sqlite:C:/OSPanel/domains/my_project/databases/ITBlab4db'); 
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
}

