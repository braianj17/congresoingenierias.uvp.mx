<?php
class ConexionMysql
{
    private $h;
    private $user_db;
    private $passwd_db;
    private $initial_catalog;
    private $cnx=false;
    public function __construct($host,$user,$passwd,$initial_catalog)
    {
        $this->h=$host;
        $this->user_db=$user;
        $this->passwd_db=$passwd;		
        $this->initial_catalog=$initial_catalog;
    }
    public function Open()
    {
        $this->cnx=mysqli_connect($this->h,$this->user_db,$this->passwd_db,$this->initial_catalog) or die("Imposible conectar al servidor MySQL ".mysqli_connect_error());
        return $this->cnx;
    }
    public function Close()
    {
        mysqli_close($this->cnx);
    }
}
?>