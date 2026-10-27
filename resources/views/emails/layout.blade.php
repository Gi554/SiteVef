<!DOCTYPE html>
<html lang="fr" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <title>{{ $emailTitle }} — Verre d’Eau Fraîche</title>
    <!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml><![endif]-->
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        table { border-collapse: collapse; }
        a:focus-visible { outline: 2px solid #2563eb; outline-offset: 3px; }
        @media screen and (max-width: 600px) {
            .email-outer { padding: 16px 8px !important; }
            .email-padding { padding-left: 22px !important; padding-right: 22px !important; }
            .email-column { display: block !important; width: 100% !important; padding-left: 0 !important; padding-right: 0 !important; }
            .email-title { font-size: 25px !important; line-height: 32px !important; }
        }
    </style>
</head>
<body style="margin:0;padding:0;width:100%;background-color:#f1f5f9;font-family:Arial,Helvetica,sans-serif;color:#172554;">
    <div style="display:none;font-size:1px;color:#f1f5f9;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;mso-hide:all;">{{ $preheader }}</div>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" bgcolor="#f1f5f9" style="width:100%;background-color:#f1f5f9;">
        <tr><td class="email-outer" align="center" style="padding:36px 16px;">
            <!--[if mso]><table role="presentation" width="620" align="center"><tr><td><![endif]-->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="width:100%;max-width:620px;background-color:#ffffff;border:1px solid #e2e8f0;">
                <tr><td bgcolor="#38bdf8" height="5" style="height:5px;line-height:5px;font-size:0;">&nbsp;</td></tr>
                <tr><td class="email-padding" bgcolor="#172554" style="padding:28px 36px;background-color:#172554;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0"><tr>
                        <td width="64" valign="middle" style="width:64px;padding-right:16px;">
                            <img src="{{ $message->embed(public_path('Style1/images/logo.png')) }}" width="52" height="52" alt="VEF" style="display:block;width:52px;height:52px;border:0;background-color:#ffffff;border-radius:10px;color:#172554;font-size:16px;">
                        </td>
                        <td valign="middle">
                            <p style="margin:0;color:#ffffff;font-size:19px;line-height:26px;font-weight:bold;">Verre d’Eau Fraîche</p>
                            <p style="margin:4px 0 0;color:#cbd5e1;font-size:12px;line-height:19px;">Agissons pour chacun, agissons pour tous.</p>
                        </td>
                    </tr></table>
                </td></tr>
                <tr><td class="email-padding" style="padding:32px 36px 0;">
                    <p style="margin:0 0 12px;color:#1d4ed8;font-size:11px;line-height:18px;font-weight:bold;letter-spacing:1.5px;text-transform:uppercase;">{{ $category }}</p>
                    <h1 class="email-title" style="margin:0;color:#172554;font-size:29px;line-height:37px;letter-spacing:-0.5px;">{{ $emailTitle }}</h1>
                    <p style="margin:12px 0 0;color:#64748b;font-size:13px;line-height:21px;">Reçu le {{ $receivedAt->format('d/m/Y à H:i') }}</p>
                    <p style="margin:20px 0 0;color:#475569;font-size:15px;line-height:25px;">{{ $intro }}</p>
                </td></tr>
                <tr><td class="email-padding" style="padding:28px 36px 8px;">
                    @yield('email_content')
                </td></tr>
                <tr><td class="email-padding" style="padding:16px 36px 32px;">
                    <table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:separate;border-spacing:0;"><tr>
                        <td align="center" bgcolor="#2563eb" style="background-color:#2563eb;border-radius:8px;mso-padding-alt:15px 22px;">
                            <a href="{{ $actionUrl }}" style="display:inline-block;padding:15px 22px;border:1px solid #2563eb;border-radius:8px;color:#ffffff;font-size:14px;line-height:20px;font-weight:bold;text-align:center;text-decoration:none;mso-padding-alt:0;">{{ $actionLabel }}</a>
                        </td>
                    </tr></table>
                    <p style="margin:18px 0 0;color:#64748b;font-size:12px;line-height:20px;">Vous pouvez répondre directement à cet email pour contacter {{ $senderLabel }}.</p>
                </td></tr>
                <tr><td class="email-padding" bgcolor="#f8fafc" style="padding:22px 36px;border-top:1px solid #e2e8f0;background-color:#f8fafc;">
                    <p style="margin:0;color:#475569;font-size:12px;line-height:20px;font-weight:bold;">Association humanitaire Verre d’Eau Fraîche</p>
                    <p style="margin:4px 0 0;color:#64748b;font-size:12px;line-height:20px;">Cotonou, Bénin · Notification du formulaire {{ $formLabel }}.</p>
                    <p style="margin:8px 0 0;font-size:12px;line-height:20px;"><a href="{{ url('/') }}" style="color:#1d4ed8;text-decoration:underline;">Visiter le site de l’association</a></p>
                </td></tr>
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
            <p style="margin:18px 0 0;color:#64748b;font-size:11px;line-height:18px;">Cette notification est destinée à l’équipe VEF.</p>
        </td></tr>
    </table>
</body>
</html>
