<?php 
class ComandosMysql
{
    private $cmdText;
    private $connection;
    public $result=array();
    public $numResults;public $error_message;

    public function __construct($cmdText,$connection)
    {
        $this->cmdText=$cmdText;
        $this->connection=$connection;
    }

    //El parámetro que recibe es para leer los datos sin importar si tiene o no caracteres especiales
    //Debe recibir true cuando se desea leer todos los caracteres
    public function ExecuteReader($htmlfilter)
    {
        if($htmlfilter==null)
            $htmlfilter = false;
        $query =mysqli_query($this->connection, $this->cmdText) or( mysqli_error($this->connection));
        if($query)
        {
            $this->numResults = mysqli_num_rows($query);
            for($i = 0; $i < $this->numResults; $i++)
            {

                $r = mysqli_fetch_array($query);
                $key = array_keys($r);
                for($x = 0; $x < count($key); $x++)
                {
                    if(!is_int($key[$x]))
                    {
                        if(mysqli_num_rows($query) >= 1)
                            $this->result[$i][$key[$x]] = ($r[$key[$x]]);
                        else
                            $this->result = null;
                    }
                }
            }
            if(is_resource($query))
                mysqli_free_result($query);
        }
            return $this->result;
    }
    public function ExecuteNonQuery()
    {
        $affected_rows=0;
        $error= "";
        try
        {
            $query =mysqli_query($this->connection, $this->cmdText) or( mysqli_error($this->connection));
            if($query)
            {
                $affected_rows = mysqli_affected_rows($this->connection);
                if(is_resource($query))
                {
                    mysqli_free_result($query);
                }
                if($affected_rows<=0)
                {
                    $this->error_message="Error: ".mysqli_error($this->connection);
                }
            }
            else
            {
                $error=$this->error_message="Error: ".mysqli_error($this->connection);
            }
        }
        catch(Exception $ex)
        {
            throw new Exception("Error en la sentencia sql");
        }

        return $affected_rows;
    }
}
?>