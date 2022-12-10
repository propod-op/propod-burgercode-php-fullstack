<?php
// On utilise pas d'instance de la classe mais la classe

// ---------- CLASS DATABASE --------
class database{

    private static $dbHost="propodburgercode.mysql.db";
    private static $dbName="propodburgercode";
    private static $dbUser="propodburgercode";
    private static $dbPassword="mira7ipa8GIO";
    public static $connection =null;

    public static function connect(){
        try{
             self::$connection = new PDO("mysql:host=".self::$dbHost.";dbname=".self::$dbName.";",self::$dbUser,self::$dbPassword);
         }catch(Exception $e){
             die('ERROR : ' . $e->getMmesage());
         }
        //echo "Connection wokrs fine...";
        return self::$connection;

    }

    public static function disconnect(){
        try{
             self::$connection=null;
         }catch(Exception $e){
             die('ERROR : ' . $e->getMmesage());
         }

    }

}
// -------- END CLASS DATABASE -------
//database::connect();


?>
