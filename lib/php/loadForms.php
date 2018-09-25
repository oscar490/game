<?php

/*function enviar_mail($de,$para,$asunto,$body){
    $mail_de=$de;
    $mail_para=$para;
    $asunto_mail = $asunto;
	$body_mail =$body;

	$cabeceras_mail  = 'MIME-Version: 1.0' . "\r\n";
	$cabeceras_mail .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
	$cabeceras_mail .= 'From: ' . $mail_de . "\r\n";
    return mail($mail_para, $asunto_mail, $body_mail, $cabeceras_mail);
} */

//formulario de login
if(isset($_POST['form_login'])){
	if(isset($_POST['email'])){
		$res= Doctrine_Query::create()->from('User')->where('email = ?', $_POST['email'])->andWhere('password = ?', md5($_POST['password']))->andWhere('is_active=1')->limit(1)->execute();
	}else if(isset($_POST['username'])){
		$res= Doctrine_Query::create()->from('User')->where('user = ?', $_POST['username'])->andWhere('password = ?', md5($_POST['password']))->andWhere('is_active=1')->limit(1)->execute();
	}
	
	if($res && $res->Count() != null)
	{
		$user=$res->toArray();
		$_SESSION['user']=$user[0];
		header('Location: headers'/*.$_POST['http-refer']*/);
	}
	else
	{
		$_msg=new stdClass();
		$_msg->type='danger';
		$_msg->text='Usuario o contraseña incorrectos';
	}
}





//registro
if(isset($_POST['formulario_registro_usuario'])){
	$reg = new User();
	
    $reg->token = date('dmyHis');
    $reg->is_active = 1;
    $reg->user = $_POST['name'];
    $reg->password = md5($_POST['password']);
    $reg->name = $_POST['name'];
    $reg->email = $_POST['email'];
    $reg->lastname = $_POST['lastname'];
    $reg->permisocmc = 0;
    $reg->permisoec = 0;
    $reg->permisocmu = 0;
    $reg->permisoeu = 0;
    $reg->type = 1;
    $reg->phone = $_POST['phone'];
    $reg->pagado_hasta = gmdate("Y-m-d H:i:s",strtotime("now"));
    $reg->options = '0,0,1';
    $msg = $reg->trySave();

    if($msg){
        $mail = Doctrine_Query::create()->from('Emails')->where("title = 'Usuario registrado'")->execute()->getFirst();
        $de = "no-reply@goofix.es";
        $para = $reg->email;
        $asunto = $mail->asunto;
        $body = sustituir($mail->description,$reg);
        enviar_mail($de,$para,$asunto,$body);
    }



}


if(isset($_POST['accederAgencia']))
{
	$access=Doctrine_Query::create()->from('Agencia')->where('usuario = ?', $_POST['usuarioAgencia'])->andWhere('clave = ?', $_POST['claveAgencia'])->andWhere('isActive=1')->limit(1)->execute()->getFirst();
	if($access->id)
	{
		$logado=1;
		$_SESSION['agenciaWeb'] = array();
		$_SESSION['agenciaWeb']['id']=$access->id;
        $_SESSION['agenciaWeb']['nombre']=$access->title;
        $_SESSION['agenciaWeb']['email']=$access->email;
		$_SESSION['agenciaWeb']['usuario']=$access->usuario;
	}
	else
	{
		$logado=2;
	}
}

if(isset($_POST['formulario_registro_empresa'])){
        $reg = new User();

        $reg->token = date('dmyHis');
        $reg->is_active = 1;
        $reg->user = $_POST['fullname'];
        $reg->password = md5($_POST['password']);
        $reg->name = $_POST['fullname'];
        $reg->email = $_POST['email'];
        $reg->lastname = '';
        $reg->permisocmc = 0;
        $reg->permisoec = 0;
        $reg->permisocmu = 0;
        $reg->permisoeu = 0;
        $reg->type = 2;
        $reg->phone = $_POST['phone'];
        $reg->pagado_hasta = date("Y-m-d H:i:s",strtotime("+2 month"));
        $reg->options = '0,0,1';

        $msg = $reg->trySave();
}

//formulario de contacto
if (isset($_POST['form_contacto']))
{
	if($_POST['nombre']=="" || $_POST['email']=="" || $_POST['mensaje']=="")
	{
		$errorEnvio=1;
	}
	else
	{
		$mail_de=$_POST["email"];

		$mail_para="info@dominio.es";
		
		$asunto_mail = 'Contacto desde web';	
		$body_mail ='Usuario: '.$_POST['nombre'].'<br/>'.
					'Email: '.$_POST['email'].'<br/>'.
					'Asunto: '.$_POST['asunto'].'<br/>'.
					'Mensaje: '.'<br/>'.$_POST['mensaje'].'<br/><br/><br/><br/>';							
				
		$cabeceras_mail  = 'MIME-Version: 1.0' . "\r\n";
		$cabeceras_mail .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
		$cabeceras_mail .= 'From: ' . $mail_de . "\r\n";
		if(mail($mail_para, $asunto_mail, $body_mail, $cabeceras_mail))
		{
			$errorEnvio=2;
		}
		else
		{
			$errorEnvio=1;
		}

	}
	
}

//CUANDO EL USUARIO QUIERE RECORDAR LA CONTRASEÑA.
if(isset($_POST['form_recordar']))
{
	$rec = Doctrine_Query::create()->from('User')->where('email = ?', $_POST['email'])->limit(1)->execute();

	if($rec->Count() != null)
	{
		$r=$rec->getFirst();
		//genera una nueva contaseña para modificarla en la base de datos y enviarla al email del usuario.
		$nueva=generaPass();
		
		Doctrine_Query::create()
        ->update('User')
        ->set('pass', '?',md5($nueva))
        ->where('id = ?', $r->id)
        ->execute();
	
	
		//EMAIL DE BIENVENIDA
		$mail_de="dominio.com";
		$mail_para= $r->email;
		$asunto_mail = 'Recuerdo de contraseña';	
		$body_mail = '<p>A continuación se indica tu usuario y tu nueva contraseña.</p><br/<br />
					Usuario: ' . $r->usuario . '<br/>	
					Clave: ' . $nueva . '<br/><br/>'; 
													
		$cabeceras_mail  = 'MIME-Version: 1.0' . "\r\n";
		$cabeceras_mail .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
		$cabeceras_mail .= 'From: ' . $mail_de . "\r\n";
		mail($mail_para, $asunto_mail, $body_mail, $cabeceras_mail);
		
		$norecordado=1;
	}
	else
	{
		$norecordado=2;
	}
}






if(isset($_POST['btnPagarTransferencia']))
{
	$reg=new Reserva();
	$token = date('dmYHis');
	$reg->token=$token;
	$reg->fecha=$_POST["fecha"];
	$reg->plazas=$_POST["plazas"];
	$reg->confirmado=1;
	$reg->tipoUsuario=1;
	$reg->nombre=$_POST["nombre"];
	$reg->email=$_POST["email"];
	$reg->trySave();
	
	
	$mail_de="reservas@sherrybeer.com";
	$para      = $_POST["email"];
	$titulo    = 'Reserva SherryBeer';
	$mensaje = '<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Skyline Confirm Email</title>
  <style type="text/css">
    @import url(http://fonts.googleapis.com/css?family=Lato:400);

    /* Take care of image borders and formatting */

    img {
      max-width: 600px;
      outline: none;
      text-decoration: none;
      -ms-interpolation-mode: bicubic;
    }



    a {
      text-decoration: none;
      border: 0;
      outline: none;
    }

    a img {
      border: none;
    }

    /* General styling */

    td, h1, h2, h3  {
      font-family: Helvetica, Arial, sans-serif;
      font-weight: 400;
    }

    body {
      -webkit-font-smoothing:antialiased;
      -webkit-text-size-adjust:none;
      width: 100%;
      height: 100%;
      color: #37302d;
      background: #ffffff;
    }

     table {
      border-collapse: collapse !important;
    }


    h1, h2, h3 {
      padding: 0;
      margin: 0;
      color: #727272;
      font-weight: 400;
    }

    h3 {
      color: #21c5ba;
      font-size: 24px;
    }

    .important-font {
      color: #603c18;
      font-weight: bold;
    }

    .hide {
      display: none !important;
    }

    .force-full-width {
      width: 100% !important;
    }
  </style>

  <style type="text/css" media="screen">
    @media screen {
       /* Thanks Outlook 2013! http://goo.gl/XLxpyl*/
      td, h1, h2, h3 {
        font-family: "Lato", "Helvetica Neue", "Arial", "sans-serif" !important;
      }
    }
  </style>

  <style type="text/css" media="only screen and (max-width: 480px)">
    /* Mobile styles */
    @media only screen and (max-width: 480px) {
      table[class="w320"] {
        width: 320px !important;
      }

      table[class="w300"] {
        width: 300px !important;
      }

      table[class="w290"] {
        width: 290px !important;
      }

      td[class="w320"] {
        width: 320px !important;
      }

      td[class="mobile-center"] {
        text-align: center !important;
      }

      td[class*="mobile-padding"] {
        padding-left: 20px !important;
        padding-right: 20px !important;
        padding-bottom: 20px !important;
      }

      td[class*="mobile-block"] {
        display: block !important;
        width: 100% !important;
        text-align: left !important;
        padding-bottom: 20px !important;
      }

      td[class*="mobile-border"] {
        border: 0 !important;
      }

      td[class*="reveal"] {
        display: block !important;
      }
    }
  </style>

</head>
<body class="body" style="padding:0; margin:0; display:block; background:#ffffff; -webkit-text-size-adjust:none" bgcolor="#ffffff">
<table align="center" cellpadding="0" cellspacing="0" width="100%" height="100%">
  <tr>
    <td align="center" valign="top" bgcolor="#ffffff"  width="100%">

    <table cellspacing="0" cellpadding="0" width="100%">
      <tr>
        <td style="border-bottom: 3px solid #603c18;" width="100%">
          <center>
            <table cellspacing="0" cellpadding="0" width="500" class="w320">
              <tr>
                <td valign="top" style="padding:10px 0; text-align:left;" class="mobile-center" style="margin-left: 37%;">
                  <img src="http://www.sherrybeer.com/img/logo.png" width="30%";>
                </td>
              </tr>
            </table>
          </center>
        </td>
      </tr>
      <tr>
        <td background=http://www.sherrybeer.com/devxerintel/img/index_banner.jpg" bgcolor="#8b8284" valign="top" style="background: url(http://www.sherrybeer.com/devxerintel/img/index_banner.jpg) no-repeat center; background-color: #8b8284; background-position: center;">
          <!--[if gte mso 9]>
          <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="mso-width-percent:1000;height:303px;">
            <v:fill type="tile" src="https://www.filepicker.io/api/file/kmlo6MonRpWsVuuM47EG" color="#8b8284" />
            <v:textbox inset="0,0,0,0">
          <![endif]-->
          <div>
            <center>
              <table cellspacing="0" cellpadding="0" width="530" height="303" class="w320">
                <tr>
                  <td valign="middle" style="vertical-align:middle; padding-right: 15px; padding-left: 15px; text-align:left;" height="303">

                    <table cellspacing="0" cellpadding="0" width="100%">
                      <tr>
                        <td>
                          <h1 style="color: white;">Has hecho una nueva reserva, debe realizar la transferencia a la cuenta facilitada en el email para formalizar la reserva</h1><br>
                          <h2 style="color: white;">Gracias por utilizar nuestro servicio. sherrybeer</h2>
                          <br>
                        </td>
                      </tr>
                    </table>

                    <table cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                    <td class="hide reveal">
                      &nbsp;
                    </td>

                      <td>
                        &nbsp;
                      </td>
                    </tr>
                  </table>
                  </td>
                </tr>
              </table>
            </center>
          </div>
          <!--[if gte mso 9]>
            </v:textbox>
          </v:rect>
          <![endif]-->
        </td>
      </tr>
      <tr class="force-full-width">
        <td valign="top" class="force-full-width">
          <center>
            <table cellspacing="0" cellpadding="0" width="500" class="w320">
              <tr>
                <td valign="top" style="border-bottom:1px solid #a1a1a1;">

                <table cellspacing="0" cellpadding="0" class="force-full-width">
                  <tr>
                    <td style="padding: 30px 0;" class="mobile-padding">

                    <table class="force-full-width" cellspacing="0" cellpadding="0">
                      <tr>
                        <td style="text-align: left;">
                          <span class="important-font">

                          <br>
                          </span>

                        </td>
                        <td style="text-align: right; vertical-align:top;">


                        </td>
                      </tr>
                    </table>

                    </td>
                  </tr>
                  <tr>
                    <td style="padding-bottom: 30px;" class="mobile-padding">

                      <table class="force-full-width" cellspacing="0" cellpadding="0">
                        <tr>

                          <td class="mobile-block">
                            <table cellspacing="0" cellpadding="0" class="force-full-width">
                              <tr>
                                <td class="mobile-border" style="background-color: #603c18; color: #ffffff; padding: 5px; border-right: 3px solid #ffffff; text-align:left;">
                                  Contenido
                                </td>
                              </tr>
                              <tr>
                                <td  style="background-color: #ebebeb; padding: 8px; border-top: 3px solid #ffffff; text-align:left;">
                                   <span class="important-font">
                            Fecha: '.$reg->fecha.' <br>
                            Plazas: '.$reg->plazas.' <br>
                            Nombre: '.$reg->nombre.' <br>
                              Email: '.$reg->email.' <br>
                              Precio: '.$_POST['precio'].' Euros <br>
                               * Debe realizar la transferencia del importe indicado al siguiente número de cuenta : 2038 4133 3160 0003 4255
                          </span>
                                </td>
                              </tr>
                            </table>
                          </td>

                        </tr>
                      </table>

                    </td>
                  </tr>
                </table>


                </td>
              </tr>
              <tr>
                <td>

                  <table cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                      <td class="mobile-padding" style="text-align:left;">
                      <br>
                        Gracias por usar SherryBeer.
                      <br>
                      <br>

                      <br>
                      <br>
                      <br>

                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </center>
        </td>
      </tr>
      <tr>
        <td style="background-color:#603c18; color: white;">
          <center>
            <table cellspacing="0" cellpadding="0" width="500" class="w320">

              <tr>
                <td>
                  <center>
                    <table style="margin:0 auto;" cellspacing="0" cellpadding="5" width="100%">

                      <tr>
                        <td style="text-align:center; margin:0 auto;" width="100%">
                           <a href="#" style="text-align:center;">
                             <img style="margin:0 auto;"  src="http://www.sherrybeer.com/img/logo.png" width="25%;" alt="logo link" />
                           </a>
                        </td>
                      </tr>
                       <tr>

                <td style="text-align: center;">© 2017 Desarrollado por Xerintel</td>
              </tr>
                    </table>
                  </center>
                </td>
              </tr>
            </table>
          </center>
        </td>
      </tr>
    </table>
    </td>
  </tr>
</table>
</body>
</html>';
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    // More headers
    $headers .= 'From: <info@sherrybeer.com>' . "\r\n";
    $headers .= "To: <$email>\r\n";
    $header .= "Reply-To: info@sherrybeer.com\r\n";
    
    if(mail($para, $titulo, $mensaje, $headers))
    {
    
    
    
    
	    $mail_de="reservas@sherrybeer.com";
		$para      = "reservas@sherrybeer.com";
		$titulo    = 'Nueva reserva SherryBeer';
		$mensaje = '<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	  <meta name="viewport" content="width=device-width, initial-scale=1" />
	  <title>Skyline Confirm Email</title>
	  <style type="text/css">
	    @import url(http://fonts.googleapis.com/css?family=Lato:400);
	
	    /* Take care of image borders and formatting */
	
	    img {
	      max-width: 600px;
	      outline: none;
	      text-decoration: none;
	      -ms-interpolation-mode: bicubic;
	    }
	
	
	
	    a {
	      text-decoration: none;
	      border: 0;
	      outline: none;
	    }
	
	    a img {
	      border: none;
	    }
	
	    /* General styling */
	
	    td, h1, h2, h3  {
	      font-family: Helvetica, Arial, sans-serif;
	      font-weight: 400;
	    }
	
	    body {
	      -webkit-font-smoothing:antialiased;
	      -webkit-text-size-adjust:none;
	      width: 100%;
	      height: 100%;
	      color: #37302d;
	      background: #ffffff;
	    }
	
	     table {
	      border-collapse: collapse !important;
	    }
	
	
	    h1, h2, h3 {
	      padding: 0;
	      margin: 0;
	      color: #727272;
	      font-weight: 400;
	    }
	
	    h3 {
	      color: #21c5ba;
	      font-size: 24px;
	    }
	
	    .important-font {
	      color: #603c18;
	      font-weight: bold;
	    }
	
	    .hide {
	      display: none !important;
	    }
	
	    .force-full-width {
	      width: 100% !important;
	    }
	  </style>
	
	  <style type="text/css" media="screen">
	    @media screen {
	       /* Thanks Outlook 2013! http://goo.gl/XLxpyl*/
	      td, h1, h2, h3 {
	        font-family: "Lato", "Helvetica Neue", "Arial", "sans-serif" !important;
	      }
	    }
	  </style>
	
	  <style type="text/css" media="only screen and (max-width: 480px)">
	    /* Mobile styles */
	    @media only screen and (max-width: 480px) {
	      table[class="w320"] {
	        width: 320px !important;
	      }
	
	      table[class="w300"] {
	        width: 300px !important;
	      }
	
	      table[class="w290"] {
	        width: 290px !important;
	      }
	
	      td[class="w320"] {
	        width: 320px !important;
	      }
	
	      td[class="mobile-center"] {
	        text-align: center !important;
	      }
	
	      td[class*="mobile-padding"] {
	        padding-left: 20px !important;
	        padding-right: 20px !important;
	        padding-bottom: 20px !important;
	      }
	
	      td[class*="mobile-block"] {
	        display: block !important;
	        width: 100% !important;
	        text-align: left !important;
	        padding-bottom: 20px !important;
	      }
	
	      td[class*="mobile-border"] {
	        border: 0 !important;
	      }
	
	      td[class*="reveal"] {
	        display: block !important;
	      }
	    }
	  </style>
	
	</head>
	<body class="body" style="padding:0; margin:0; display:block; background:#ffffff; -webkit-text-size-adjust:none" bgcolor="#ffffff">
	<table align="center" cellpadding="0" cellspacing="0" width="100%" height="100%">
	  <tr>
	    <td align="center" valign="top" bgcolor="#ffffff"  width="100%">
	
	    <table cellspacing="0" cellpadding="0" width="100%">
	      <tr>
	        <td style="border-bottom: 3px solid #603c18;" width="100%">
	          <center>
	            <table cellspacing="0" cellpadding="0" width="500" class="w320">
	              <tr>
	                <td valign="top" style="padding:10px 0; text-align:left;" class="mobile-center" style="margin-left: 37%;">
	                  <img src="http://www.sherrybeer.com/img/logo.png" width="30%";>
	                </td>
	              </tr>
	            </table>
	          </center>
	        </td>
	      </tr>
	      <tr>
	        <td background=http://www.sherrybeer.com/devxerintel/img/index_banner.jpg" bgcolor="#8b8284" valign="top" style="background: url(http://www.sherrybeer.com/devxerintel/img/index_banner.jpg) no-repeat center; background-color: #8b8284; background-position: center;">
	          <!--[if gte mso 9]>
	          <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="mso-width-percent:1000;height:303px;">
	            <v:fill type="tile" src="https://www.filepicker.io/api/file/kmlo6MonRpWsVuuM47EG" color="#8b8284" />
	            <v:textbox inset="0,0,0,0">
	          <![endif]-->
	          <div>
	            <center>
	              <table cellspacing="0" cellpadding="0" width="530" height="303" class="w320">
	                <tr>
	                  <td valign="middle" style="vertical-align:middle; padding-right: 15px; padding-left: 15px; text-align:left;" height="303">
	
	                    <table cellspacing="0" cellpadding="0" width="100%">
	                      <tr>
	                        <td>
	                          <h1 style="color: white;">Se ha realizado una nueva reserva.</h1><br>
	                          <h2 style="color: white;">Gracias por utilizar nuestro servicio. sherrybeer</h2>
	                          <br>
	                        </td>
	                      </tr>
	                    </table>
	
	                    <table cellspacing="0" cellpadding="0" width="100%">
	                    <tr>
	                    <td class="hide reveal">
	                      &nbsp;
	                    </td>
	
	                      <td>
	                        &nbsp;
	                      </td>
	                    </tr>
	                  </table>
	                  </td>
	                </tr>
	              </table>
	            </center>
	          </div>
	          <!--[if gte mso 9]>
	            </v:textbox>
	          </v:rect>
	          <![endif]-->
	        </td>
	      </tr>
	      <tr class="force-full-width">
	        <td valign="top" class="force-full-width">
	          <center>
	            <table cellspacing="0" cellpadding="0" width="500" class="w320">
	              <tr>
	                <td valign="top" style="border-bottom:1px solid #a1a1a1;">
	
	                <table cellspacing="0" cellpadding="0" class="force-full-width">
	                  <tr>
	                    <td style="padding: 30px 0;" class="mobile-padding">
	
	                    <table class="force-full-width" cellspacing="0" cellpadding="0">
	                      <tr>
	                        <td style="text-align: left;">
	                          <span class="important-font">
	
	                          <br>
	                          </span>
	
	                        </td>
	                        <td style="text-align: right; vertical-align:top;">
	
	
	                        </td>
	                      </tr>
	                    </table>
	
	                    </td>
	                  </tr>
	                  <tr>
	                    <td style="padding-bottom: 30px;" class="mobile-padding">
	
	                      <table class="force-full-width" cellspacing="0" cellpadding="0">
	                        <tr>
	
	                          <td class="mobile-block">
	                            <table cellspacing="0" cellpadding="0" class="force-full-width">
	                              <tr>
	                                <td class="mobile-border" style="background-color: #603c18; color: #ffffff; padding: 5px; border-right: 3px solid #ffffff; text-align:left;">
	                                  Contenido
	                                </td>
	                              </tr>
	                              <tr>
	                                <td  style="background-color: #ebebeb; padding: 8px; border-top: 3px solid #ffffff; text-align:left;">
	                                   <span class="important-font">
	                            Fecha: '.$reg->fecha.' <br>
	                            Plazas: '.$reg->plazas.' <br>
	                            Nombre: '.$reg->nombre.' <br>
	                              Email: '.$reg->email.' Euros <br>
	                              Precio: '.$_POST['precio'].'E <br>
	                               * Al cliente se le ha facilitado el siguiente numero de cuenta : 2038 4133 3160 0003 4255 , para que realice la transferencia.
	                          </span>
	                                </td>
	                              </tr>
	                            </table>
	                          </td>
	
	                        </tr>
	                      </table>
	
	                    </td>
	                  </tr>
	                </table>
	
	
	                </td>
	              </tr>
	              <tr>
	                <td>
	
	                  <table cellspacing="0" cellpadding="0" width="100%">
	                    <tr>
	                      <td class="mobile-padding" style="text-align:left;">
	                      <br>
	                        Gracias por usar SherryBeer.
	                      <br>
	                      <br>
	
	                      <br>
	                      <br>
	                      <br>
	
	                      </td>
	                    </tr>
	                  </table>
	                </td>
	              </tr>
	            </table>
	          </center>
	        </td>
	      </tr>
	      <tr>
	        <td style="background-color:#603c18; color: white;">
	          <center>
	            <table cellspacing="0" cellpadding="0" width="500" class="w320">
	
	              <tr>
	                <td>
	                  <center>
	                    <table style="margin:0 auto;" cellspacing="0" cellpadding="5" width="100%">
	
	                      <tr>
	                        <td style="text-align:center; margin:0 auto;" width="100%">
	                           <a href="#" style="text-align:center;">
	                             <img style="margin:0 auto;"  src="http://www.sherrybeer.com/img/logo.png" width="25%;" alt="logo link" />
	                           </a>
	                        </td>
	                      </tr>
	                       <tr>
	
	                <td style="text-align: center;">© 2017 Desarrollado por Xerintel</td>
	              </tr>
	                    </table>
	                  </center>
	                </td>
	              </tr>
	            </table>
	          </center>
	        </td>
	      </tr>
	    </table>
	    </td>
	  </tr>
	</table>
	</body>
	</html>';
	    $headers = "MIME-Version: 1.0" . "\r\n";
	    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	
	    // More headers
	    $headers .= 'From: <info@sherrybeer.com>' . "\r\n";
	    $headers .= "To: <$email>\r\n";
	    $header .= "Reply-To: info@sherrybeer.com\r\n";
	
	    if(mail($para, $titulo, $mensaje, $headers))
	    {
		   $reservaConfirmada==1;
	    }
	    else
	    {
		    $reservaConfirmada==2;
	    }
	}
	else
	{
		$reservaConfirmada==2;
	}
}









?>