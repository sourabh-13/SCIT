<!DOCTYPE html>
<html>
<head>
    <title>Scits</title>
</head>
<body style="margin: 0px; padding-bottom: 50px; padding-left: 0; padding-right: 0;font-family:sans-serif;background-color:#ebebeb;">

    <table style="width: 100%;" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td style="width: 100%;background-color:#333;padding:0 30px;" align="center">
                    <table>
                        <tr>
                            <td style="max-width:400px;padding:0;" >
                                <a href="" style="display:block;margin:10px 0;"><img width="90" src="{{ asset('public/images/scits-150.png') }}" style="max-width: 90px;display:inline-block;width: 100%;"></a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td valign="top" align="center" style="background-color:#333;">
                    <table style="text-align: left; background-color:#fff;border-radius:4px 4px 0 0;color: rgb(51, 51, 51); font-size:16px;width:100%;max-width:650px;"  cellspacing="0">
                        <tbody>
                            <tr>
                                <td style="padding-top: 20px; text-align: center;">
                                    <h2 style="color:#0877BD;margin:0;text-decoration:uppercase;">Welcome {{ ucfirst($name) }} !</h2>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td valign="top" align="center">
                    <table style="text-align: left; background-color:#fff;color: rgb(51, 51, 51); font-size:16px;width:100%;max-width:650px;"  cellspacing="0">
                        <tbody>
                            <tr>
                                <td style="background-color:#fff;text-align:left;padding:20px;">
                                    <a href="{{ $set_password_url }}" style="font-size:16px;box-shadow:0 1px 2px 1px rgba(0, 0, 0, 0.2);background-color: #0877BD;color: #fff;text-decoration: none;padding: 10px 50px;">Reset Password</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td valign="top" align="center">
                    <table style="text-align: left; background-color:#fff;color: rgb(51, 51, 51); font-size:16px;width:100%;max-width:650px;"  cellspacing="0">
                        <tbody>
                            <tr>
                                <td style="background-color:#fff;text-align:left;padding:0 20px;">
                                    <p style="margin-top:10px;margin-bottom:0;">if that doesn't work, Copy and Paste the following link in your browser.</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0 20px;">
                                    <p style="color:#0877BD">{{ $set_password_url }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0 20px;">
                                    <p style="margin-top:0px;margin-bottom:0;">If you have any questions, just reply to <span style="color:#0877bd;">{{ url('/') }}</span>. We're always happy to help you out.</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="background-color:#fff;text-align:left;padding:0 20px;">
                                    <p style="margin-top:10px;margin-bottom:0;">Your credentials are confidential, Please do not share it with anyone and keep it safe.
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0 20px;">
                                    <?php $security_file = SecurityPolicyFilePath.'/'.$home_security_policy;?>
                                    <p>You can click on this link to view our
                                        <a href="{{ $security_file }}"> Privacy policy</a>
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td valign="top" align="center">
                    <table style="text-align: left; background-color:#fff;color: rgb(51, 51, 51); font-size:16px;width:100%;max-width:650px;"  cellspacing="0">
                        <tbody>
                            <tr>
                                <td style="background-color:#fff;text-align:left;padding:0 20px;">
                                    <p style="margin-top:30px;margin-bottom:0;">Cheers,</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="background-color:#fff;text-align:left;padding:0 20px 20px;">
                                    <p style="margin-top:10px;margin-bottom:0;">The Scits Team</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td valign="top" align="center">
                    <table style="text-align: left;color: rgb(51, 51, 51); font-size:16px;width:100%;max-width:650px;"  cellspacing="0">
                        <tbody>
                            <tr>

                                <?php
                                    $url = "/";
                                    $logo_url = "/images/scits.png";
                                    $scits = "Scits";
                                    $facebook_slug="http://www.facebook.com/sharer.php?u=http://www.socialcareitsolutions.co.uk&pictures=".asset('public/images/scits.png')."&p[title]=Scits";
                    
                                    //$facebook_slug="http://www.facebook.com/sharer.php?u=".url($url)."&t=Scits&p[url]=".asset($logo_url)."&p[title]=".$scits;
                                /*    $twitter_slug="https://twitter.com/intent/tweet?url=".url('/');
                                    $google_slug="https://plus.google.com/share?url=".url('/');
                                */
                                    $twitter_slug="https://twitter.com/intent/tweet?url=http://www.socialcareitsolutions.co.uk&pictures";
                                    $google_slug="https://plus.google.com/share?url=http://www.socialcareitsolutions.co.uk&pictures";
                                
                                ?>

                                <td style="background-color:#333;color:#333;text-align:center;padding:0 20px;">
                                    <ul type="none" style="padding:0;">
                                        <li style="display:inline-block;margin-right:5px;"><a href="{{$facebook_slug}}"><img src="{{ asset('public/images/facebook.png') }}" style="max-width:30px;"></a></li>
                                        <li style="display:inline-block;margin-right:5px;"><a href="{{$twitter_slug}}"><img src="{{ asset('public/images/twitter.png') }}" style="max-width:30px;"></a></li>
                                        <li style="display:inline-block;margin-right:5px;"><a href="{{$google_slug}}"><img src="{{ asset('public/images/googleplus.png') }}" style="max-width:30px;"></a></li>
                                       <!--  <li style="display:inline-block;"><a href="javascript:void(0)"><img src="{{ asset('public/images/instagram.png') }}" style="max-width:30px;"></a></li> -->
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:center;border-top:1px solid #ddd;padding:10px 0;background-color:#333;">
                                    <p style="color:#fff;text-decoration:uppercase;margin:0;">{{ date('Y') }} &copy; Scits</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>


</body>
</html>