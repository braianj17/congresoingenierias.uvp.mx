<?php
class correo
    {
        public $result;
        public function Enviar($correo,$n,$i,$m)
            {
                require("class.phpmailer.php");
                require("class.smtp.php");
                $mail = new PHPMailer();
                $mail->IsSMTP();
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = "ssl";
                $mail->Host = "smtp.gmail.com";
                $mail->Port = 465;
                $mail->Username = "programacion@uvp.edu.mx";
                $mail->Password = "Des.Sof.13";
                $mail->From = "programacion@uvp.edu.mx";
                $mail->FromName = utf8_decode("Programación de sistemas");
                $mail->Subject = utf8_decode("9° Congreso de negocios UVP");
                $mail->MsgHTML("Para imprimir su gafete favor de hacer clic en el siguiente link: <a href='https://congresonegocios.uvp.mx/registro/gafeteqr.php?a1=".$n."&a2=".$i."&a3=".$m."'>Gafete Congreso</a>");
                
                $mail->AddAddress("$correo", "$correo");
                $mail->IsHTML(true);
                if (!$mail->send())
                    {
                        //echo "Fatal: " . $mail->ErrorInfo;
                        $this->result ="Hubo un error al enviar el correo, por favor inténtelo más tarde.";
                    } 
                else 
                    {
                        $this->result ="En un momento se enviará un correo con el link para imprimir tu gafete";
                    }
                //Retorna resultado.
                return $this->result;
            }
    }
?>