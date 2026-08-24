<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="margin:0;padding:0;background:#14151C;font-family:Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding:40px 20px;">
                <table width="520" cellpadding="0" cellspacing="0"
                       style="background:#1C1E27;border:1px solid #2A2C36;border-radius:16px;overflow:hidden;">

                    {{-- Header --}}
                    <tr>
                        <td style="background:#C9A24B;padding:20px 32px;">
                            <p style="color:#14151C;font-size:11px;letter-spacing:3px;text-transform:uppercase;margin:0;font-family:monospace;">
                                Boutique Paris — Support
                            </p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:32px;">

                            <h2 style="color:#F6F3EC;font-size:20px;margin:0 0 20px;font-weight:normal;">
                                {{ $subject }}
                            </h2>

                            <table width="100%" cellpadding="0" cellspacing="0"
                                   style="background:#14151C;border-radius:12px;overflow:hidden;margin-bottom:24px;">
                                <tr>
                                    <td style="padding:16px 20px;border-bottom:1px solid #2A2C36;">
                                        <span style="color:#9C9788;font-size:10px;font-family:monospace;text-transform:uppercase;letter-spacing:2px;">De</span><br>
                                        <span style="color:#F6F3EC;font-size:14px;">{{ $name }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:16px 20px;border-bottom:1px solid #2A2C36;">
                                        <span style="color:#9C9788;font-size:10px;font-family:monospace;text-transform:uppercase;letter-spacing:2px;">E-mail</span><br>
                                        <span style="color:#C9A24B;font-size:14px;">{{ $email }}</span>
                                    </td>
                                </tr>
                                @if (!empty($order_number))
                                <tr>
                                    <td style="padding:16px 20px;border-bottom:1px solid #2A2C36;">
                                        <span style="color:#9C9788;font-size:10px;font-family:monospace;text-transform:uppercase;letter-spacing:2px;">Commande</span><br>
                                        <span style="color:#F6F3EC;font-size:14px;font-family:monospace;">{{ $order_number }}</span>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="padding:16px 20px;">
                                        <span style="color:#9C9788;font-size:10px;font-family:monospace;text-transform:uppercase;letter-spacing:2px;">Message</span><br>
                                        <p style="color:#F6F3EC;font-size:14px;line-height:1.7;margin:8px 0 0;">{{ $content }}</p>
                                    </td>
                                </tr>
                            </table>

                            {{-- Bouton Répondre --}}
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <a href="mailto:{{ $email }}?subject=Re: {{ $subject }}&body=Bonjour {{ $name }},%0A%0A"
                                           style="display:inline-block;background:#C9A24B;color:#14151C;font-family:monospace;font-size:11px;font-weight:bold;letter-spacing:3px;text-transform:uppercase;text-decoration:none;padding:14px 32px;border-radius:8px;">
                                            ← Répondre au client
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="color:#9C9788;font-size:10px;text-align:center;margin-top:20px;font-family:monospace;">
                                En répondant à cet e-mail, votre réponse sera envoyée directement à {{ $email }}
                            </p>

                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:16px 32px;border-top:1px solid #2A2C36;text-align:center;">
                            <p style="color:#9C9788;font-size:10px;font-family:monospace;margin:0;">
                                © {{ date('Y') }} Boutique Paris
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>