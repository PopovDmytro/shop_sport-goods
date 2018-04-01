<?php
class CityManager extends \RexFramework\DBManager
{
    public static $assemble = 'volley.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\DBManager:standart:1.0',
    );
    
    function __construct()
    {
        parent::__construct('city', 'id');
    }
} 