<?php 
$body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="format-detection" content="telephone=no"> 
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;">
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
        <title>' . $copy['email_subject'] . '</title>
        <style type="text/css">
            @import url(http://fonts.googleapis.com/css?family=Open+Sans:400,700);
            body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0; font-family:\'Open Sans\',Arial,sans-serif;}
            /* Prevent Webkit and Windows Mobile platforms from changing default font sizes, while not breaking desktop design. */
            #backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
            img {outline:none; max-width:100%;text-decoration:none;border:none; -ms-interpolation-mode: bicubic;}
            a img {border:none;}
            p {margin: 0px 0px !important; color:#ffffff;}
            table td {border-collapse: collapse;}
            table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;border-spacing:0; }
            a {color: #ffffff;}
            .container {
                width: 640px;
            }
            @media screen and (max-width: 640px) {
                .container {
                    width: 100% !important;
                }
            }    
        </style>
    </head>
    <body>
        <table width="100%" bgcolor="#005daa" cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
            <tbody>	
                <tr>
                    <td width="100%" bgcolor="#005daa">
                        <table id="header" class="container" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr>
                                    <td style="text-align:center;padding-top:10px;padding-bottom:30px;padding-left:20px;padding-right:20px;">

                                        <p style="font-family:\'Open Sans\',Arial,sans-serif;text-align:center;font-size:14px;color:#2f2f2f;line-height:1.4em;padding-bottom:0">If you’re having trouble viewing this email, <a style="font-family:\'Open Sans\',Arial,sans-serif;color:#2f2f2f;" href="' . $user_url . '">click here</a> to view it in your browser.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom:40px;padding-left:20px;padding-right:20px;text-align:center;" align="center">
                                        <a style="text-decoration:none;outline:none;border:none;text-align:center;" href="' . $user_url . '"><img alt="Rotary Mark of Excellence" style="border:none;outline:none;width:240px;margin-left:auto;margin-right:auto;" src="' . $copy['email_image_logo'] . '" /></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                <tr />
                <tr>
                    <td width="100%" bgcolor="#eeeeee" align="center">
                        <table id="body" class="container" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr>
                                    <td style="color:#393939;font-family:\'Open Sans\',Arial,sans-serif;font-size:18px;line-height:1.4em;padding-top:50px;padding-bottom:25px;padding-left:20px;padding-right:20px;">  

                                        <p style="color:#393939;font-weight:400;font-family:\'Open Sans\',Arial,sans-serif;font-size:18px;line-height:1.4em;padding-bottom:0;">Dear ' . $user['first_name'] . ' ' . $user['last_name'] . ',</p>
                                    </td>
                                </tr>


                                        ' . $copy['email_header'] . '

                                <tr>
                                    <td style="color:#393939;font-family:\'Open Sans\',Arial,sans-serif;font-size:18px;line-height:1.4em;padding-left:40px;padding-right:40px;padding-bottom:25px;">

                                        <p style="text-align:center;color:#393939;font-weight:400;font-family:\'Open Sans\',Arial,sans-serif;font-size:22px;font-style:italic;line-height:1.4em;padding-bottom:0;">"' . $copy['pledge_text'] . '"</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color:#393939;font-family:\'Open Sans\',Arial,sans-serif;font-size:18px;line-height:1.4em;padding-bottom:40px;padding-left:20px;padding-right:20px;">

                                        <p style="text-align:center;padding-bottom:0;"><img style="border:none;outline:none;width:220px;margin-left:auto;margin-right:auto;" src="' . $copy['email_image_line'] . '" /></p>
                                    </td>
                                </tr>
                                ' . $copy['pledge_email_body'] . '
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="100%" bgcolor="#005daa" align="center">
                        <table id="footer" class="container" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tbody>                        
                                <tr>
                                    <td style="padding-top:60px;padding-bottom:30px;padding-left:20px;padding-right:20px;text-align:center;" align="center">
                                        <a style="text-decoration:none;outline:none;border:none;display:block;text-align:center;width:100%;" href="' . $user_url . '"><img alt="Share on Facebook" style="padding-bottom:10px;padding-left:10px;padding-right:10px;width:200px;border:none;outline:none;max-width:100%;display:inline;" src="' . $copy['email_image_facebook'] . '" /> <img alt="Share on Twitter" style="padding-bottom:10px;padding-left:10px;padding-right:10px;width:200px;border:none;outline:none;max-width:100%;display:inline;" src="' . $copy['email_image_twitter'] . '" /></a>                                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-left:20px;padding-right:20px;padding-bottom:50px;">
                                        <p style="text-align:center;padding-bottom:0;"><img style="border:none;outline:none;width:220px;margin-left:auto;margin-right:auto;" src="' . $copy['email_image_line_white'] . '" /></p>
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