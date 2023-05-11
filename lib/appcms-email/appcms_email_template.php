<?php 
$body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="format-detection" content="telephone=no"> 
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;">
    <meta name="color-scheme" content="normal">
    <meta name="supported-color-schemes" content="light">
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
        <title>' . $copy['email_subject'] . '</title>
        <style type="text/css">
            :root {
                color-scheme: light;
                supported-color-schemes: light;
            }
            @import url(http://fonts.googleapis.com/css?family=Open+Sans:400,700);
            body{
                width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; 
                margin:0; padding:0;
            }
            /* Prevent Webkit and Windows Mobile platforms from changing default font sizes, while not breaking desktop design. */
            #backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
           
            table td {border-collapse: collapse;}
            table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;border-spacing:0; }
           
            .container {
                width: 640px;
            }
            @media screen and (max-width: 640px) {
                .container {
                    width: 100% !important;
                }
            }

            .bg-dark {
                background-color: #30353e;
            }
            
            .bg-light {
                background-color: #ffffff;
            }

            body, td, p, a {
                font-family:\'Open Sans\',Arial,sans-serif;
                font-size: 20px;
                line-height: 1.4em;
                font-weight: 400;
            }

            table, td, tr, a, p, img {
                border: none;
            }

            img {
                outline:none; 
                max-width:100%;
                text-decoration:none;border:none; 
                -ms-interpolation-mode: bicubic;
            }
                
            a img {
                border:none;
            }

            p {
                margin: 0px 0px 30px
            }

            #backgroundTable p, #backgroundTable p a, #backgroundTable p a:visited {
                color: #405465;
                font-family:\'Open Sans\',Arial,sans-serif;
            }
            
            #backgroundTable p.light, #backgroundTable p.light a, #backgroundTable p.light a:visited {
                color: #ffffff;
            }

            .pad {
                padding-left: 20px;
                padding-right: 20px;
            }

            .center {
                text-align: center;
            }

            @media (prefers-color-scheme: dark) {
                .bg-dark {
                    background-color: #30353e;
                }
                
                .bg-light {
                    background-color: #ffffff;
                }
                
                #backgroundTable p, #backgroundTable p a, #backgroundTable p a:visited {
                    color: #405465;
                }
                
                #backgroundTable p.light, #backgroundTable p.light a, #backgroundTable p.light a:visited {
                    color: #ffffff;
                }
            }
        </style>
    </head>
    <body>
        <table width="100%" class="bg-dark" cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
            <tbody>	
                <tr>
                    <td width="100%" class="bg-dark">
                        <table id="header" class="container" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr>
                                    <td style="text-align:center;padding-top:20px;padding-bottom:20px;" class="pad">

                                        <p class="light" style="text-align:center;font-size:14px;">If you’re having trouble viewing this email, <a style="font-size:14px;" href="' . $user_url . '">click here</a> to view it in your browser.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom:60px;" align="center" class="pad center">
                                        <a style="text-decoration:none;outline:none;border:none;" href="' . $user_url . '"><img alt="Rotary Mark of Excellence" style="border:none;outline:none;width:300px;margin-left:auto;margin-right:auto;" src="' . $copy['email_image_logo'] . '" /></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                <tr />
                <tr>
                    <td width="100%" class="bg-light" align="center">
                        <table id="body" class="container" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr>
                                    <td style="padding-top:50px;" class="pad">  

                                        <p>Dear ' . $user['first_name'] . ' ' . $user['last_name'] . ',</p>
                                    </td>
                                </tr>


                                        ' . $copy['email_header'] . '

                                <tr>
                                    <td style="color:' . $copy['pledge_color'] . ';line-height:1.2em;padding-bottom:0px;">

                                        <p style="text-align:center;color:' . $copy['pledge_color'] . ';font-weight:bold;font-size:38px;font-style:italic;line-height:1.2em;">"' . $copy['pledge_text'] . '"</p>
                                    </td>
                                </tr>

                                ' . $copy['pledge_email_body'] . '

                                ' . $copy['email_body_bottom'] . '

                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="100%" class="bg-dark" align="center">
                        <table id="footer" class="container" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tbody>                        
                                <tr>
                                    <td style="border:none;padding-top:50px;padding-bottom:20px;" align="center" class="pad center">
                                        <a style="text-decoration:none;outline:none;border:none;display:block;text-align:center;width:100%;" href="' . $user_url . '"><img width="220" alt="Share on Facebook" style="display:inline-block;margin-bottom:10px;padding-left:10px;padding-right:10px;width:220px;border:none;outline:none;max-width:100%;display:inline;" src="' . $copy['email_image_facebook'] . '" /> <img width="220" alt="Share on Twitter" style="display:inline-block;margin-bottom:10px;padding-left:10px;padding-right:10px;width:220px;border:none;outline:none;max-width:100%;display:inline;" src="' . $copy['email_image_twitter'] . '" /></a>                                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom:50px;" class="pad">
                                        <p style="text-align:center;margin-bottom:0;"><img width="300" style="border:none;outline:none;width:300px;margin-left:auto;margin-right:auto;" src="' . $copy['email_image_line_white'] . '" /></p>
                                    </td>
                                </tr>
                                ' . $copy['email_footer'] . '
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>';