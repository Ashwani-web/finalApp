<html>
                               <head>
                                   <style type="text/css">
                                           body {margin: 0; padding: 0; min-width: 100%!important;text-align:center}
                                           .header{text-align:center;}
                                           .content {max-width: 600px;margin:auto;text-align:justify;padding: 20px;border:75px solid;}
                                           .link{text-align:center;padding: 8px 8px;margin: 15px;}
                                           .link a{border: 1px solid;border-radius: 5px;padding: 8px 8px;background: #c47200;color: #fff;text-decoration: none;}  
                                           @media (max-width: 600px){
                                               .content {border:5px solid;}
                                           }

                                   </style>
                               </head>
                               <body>
                                   <div class="content">
                                   <div class="header"><a href="http://noctoapp.club"><img src="http://noctoapp.club/public/images/logo/nocto_logo.png"></a>
                                   </div>
                                   <div></div>
                                   </div>
                               </body>
                               </html>



                               $adminBodyContent = '<p>You have received a request to verify a venue from;</p>
                                       <p>Venue Name: <span> '. $attributeValues['clubName']['S'].'</span></p>
                                       <p>Email: <span> '.$email.'</span></p>
                                       <p>Phone Number:<span> '.$attributeValues['userContactNo']['S'].'</span></p>
                                       <p>Name:<span> '.(isset($attributeValues['firstName']['S']) && !empty($attributeValues['firstName']['S'])?$attributeValues['firstName']['S']:"").'<span></p>
                                       <p>Please call the venue in question within 24 hours from receiving this email to confirm that the applicant is a representative of the venue. </p>
                                       <p>If this is the case, accept their request in the console under the tab Requested Venues</p>';
               $adminBody = prepare_email_content($adminBodyContent);
               $adminSubject = "Request to Verify";
               $status = send_mail(ADMIN_EMAILID, ADMIN_EMAILID, $adminBody,$adminSubject);
'<html>
                               <head>
                                   <style type="text/css">
                                           body {margin: 0; padding: 0; min-width: 100%!important;text-align:center}
                                           .header{text-align:center;}
                                           .content {max-width: 600px;margin:auto;text-align:justify;padding: 20px;border:75px solid;}
                                           .link{text-align:center;padding: 8px 8px;margin: 15px;}
                                           .link a{border: 1px solid;border-radius: 5px;padding: 8px 8px;background: #c47200;color: #fff;text-decoration: none;}  
                                           @media (max-width: 600px){
                                               .content {border:5px solid;}
                                           }

                                   </style>
                               </head>
                               <body>
                                   <div class="content">
                                   <div class="header">' .
               '<a href="http://noctoapp.club"><img src="http://noctoapp.club/public/images/logo/nocto_logo.png"></a>
                                   </div>
                                   <div>' . $bodyContent . '</div>
                                   </div>
                               </body>
                               </html>';
