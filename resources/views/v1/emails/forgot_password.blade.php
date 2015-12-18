<html><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style type="text/css">
        a{
            text-decoration:none;
            color:#2a62fc;
        }

    </style></head>
<body>
<table style="width:85%;padding:0;margin:0 auto;text-align:center;background: #eaeff5;" width="80%" border="0" align="center" cellpadding="0" cellspacing="0">

    <tbody>


    <tr style="height:200px;">

        <!-- Text -->
        <td style="font-family: Tahoma;direction: ltr;vertical-align: top;text-align: left;padding: 30px 70px;font-size: 14px;color: #898989;line-height: 25px;">
            Hi, Dear <b>{{ $toName }}</b>
            </br>


            <a href="{{ config('tezol.client_url').'auth/recoverPassword/'.$token }}">
                {{ config('tezol.client_url').'auth/recoverPassword/'.$token }}
            </a>


<span style="color:#2bb673">
Tezol team
</span>
        </td>
        <!-- Text -->

    </tr>

    <!-- Footer -->
    <tr>
        <td style="height: 30px;background: #DFDFDF;font-family: Tahoma;font-size: 11px;color: #797979;direction: ltr;text-align: left;padding: 8px 15px">© 2014 - 2015 Tezol .</td>
    </tr>
    <!-- End Footer -->

    </tbody></table>

</body></html>