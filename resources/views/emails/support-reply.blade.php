<!DOCTYPE html>
<html><head><meta charset="utf-8"></head>
<body style="margin:0;padding:0;background:#14151C;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0">
<tr><td align="center" style="padding:40px 20px;">
<table width="540" cellpadding="0" cellspacing="0"
       style="background:#1C1E27;border-radius:16px;overflow:hidden;border:1px solid #2A2C36;">

    {{-- Header --}}
    <tr><td style="background:#C9A24B;padding:20px 32px;">
        <p style="color:#14151C;font-size:11px;letter-spacing:3px;text-transform:uppercase;margin:0;font-family:monospace;">
            Boutique Paris — Support
        </p>
    </td></tr>

    {{-- Body --}}
    <tr><td style="padding:32px;">
        <p style="color:#9C9788;font-size:13px;margin:0 0 6px;">Bonjour {{ $clientName }},</p>
        <p style="color:#9C9788;font-size:12px;margin:0 0 24px;">
            En réponse à votre message: <strong style="color:#F6F3EC;">{{ $clientSubject }}</strong>
        </p>

        {{-- Réponse --}}
        <div style="background:#14151C;border-left:3px solid #C9A24B;padding:20px 24px;border-radius:0 12px 12px 0;margin-bottom:24px;">
            <p style="color:#F6F3EC;font-size:14px;line-height:1.7;margin:0;white-space:pre-line;">{{ $replyContent }}</p>
        </div>

        <p style="color:#9C9788;font-size:12px;margin:0 0 4px;">Cordialement,</p>
        <p style="color:#C9A24B;font-size:14px;font-weight:bold;margin:0;">Équipe Boutique Paris</p>

        {{-- Message original --}}
        <div style="margin-top:24px;padding-top:20px;border-top:1px solid #2A2C36;">
            <p style="color:#9C9788;font-size:10px;letter-spacing:2px;text-transform:uppercase;margin:0 0 12px;font-family:monospace;">
                Message original
            </p>
            <p style="color:#9C9788;font-size:12px;line-height:1.6;margin:0;font-style:italic;">
                {{ $originalMessage }}
            </p>
        </div>
    </td></tr>

    {{-- Footer --}}
    <tr><td style="padding:16px 32px;border-top:1px solid #2A2C36;text-align:center;">
        <p style="color:#9C9788;font-size:10px;font-family:monospace;margin:0;">
            © {{ date('Y') }} Boutique Paris —
            <a href="{{ config('app.url') }}/support" style="color:#C9A24B;text-decoration:none;">Support</a>
        </p>
    </td></tr>
</table>
</td></tr>
</table>
</body></html>