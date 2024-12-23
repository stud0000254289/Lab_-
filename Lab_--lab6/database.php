<?php

function getDatabaseConnection(): PDO {
    $db = new PDO('sqlite:databases/ITBlab4db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec('PRAGMA foreign_keys = ON');
    
    return new PDO('sqlite:databases/ITBlab4db');
}




