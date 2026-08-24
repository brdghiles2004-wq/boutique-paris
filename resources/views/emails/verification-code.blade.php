<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="margin:0; padding:0; background-color:#14151C; font-family: Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table width="480" cellpadding="0" cellspacing="0" style="background-color:#1C1E27; border:1px solid #2A2C36;">
                    <tr>
                        <td style="padding: 40px; text-align:center;">
                            <p style="color:#C9A24B; font-size:11px; letter-spacing:3px; text-transform:uppercase; margin:0 0 16px;">
                                Boutique Paris
                            </p>
                            <h1 style="color:#F6F3EC; font-size:24px; margin:0 0 24px; font-weight:normal;">
                                Bonjour {{ $name }},
                            </h1>
                            <p style="color:#9C9788; font-size:14px; line-height:1.6; margin:0 0 32px;">
                                Voici votre code de vérification pour confirmer votre compte sur Boutique Paris :
                            </p>
                            <p style="color:#C9A24B; font-size:36px; letter-spacing:10px; font-weight:bold; margin:0 0 32px;">
                                {{ $code }}
                            </p>
                            <p style="color:#9C9788; font-size:12px; margin:0;">
                                Ce code expire dans 10 minutes. Si vous n'êtes pas à l'origine de cette demande, ignorez cet e-mail.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>