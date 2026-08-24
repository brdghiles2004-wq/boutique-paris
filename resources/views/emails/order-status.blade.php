<!DOCTYPE html>
<html><head><meta charset="utf-8"></head>
<body style="margin:0;padding:0;background:#14151C;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0">
<tr><td align="center" style="padding:40px 20px;">
<table width="540" cellpadding="0" cellspacing="0" style="background:#1C1E27;border-radius:16px;overflow:hidden;border:1px solid #2A2C36;">

    <tr><td style="background:#C9A24B;padding:24px 32px;">
        <p style="color:#14151C;font-size:11px;letter-spacing:3px;text-transform:uppercase;margin:0;font-family:monospace;">Boutique Paris</p>
    </td></tr>

    <tr><td style="padding:32px;">
        <h1 style="color:#F6F3EC;font-size:22px;margin:0 0 8px;font-weight:normal;font-family:Georgia,serif;">
            📦 {{ $statusLabel }}
        </h1>
        <p style="color:#9C9788;font-size:13px;margin:0 0 24px;">
            Bonjour {{ $order->shipping_name }},
        </p>
        <p style="color:#F6F3EC;font-size:14px;line-height:1.7;margin:0 0 24px;">
            {{ $statusMessage }}
        </p>

        <table width="100%" cellpadding="0" cellspacing="0" style="background:#14151C;border-radius:12px;padding:20px;margin-bottom:24px;">
            <tr><td>
                <span style="color:#9C9788;font-size:10px;font-family:monospace;text-transform:uppercase;letter-spacing:2px;">Commande</span><br>
                <span style="color:#C9A24B;font-size:16px;font-family:monospace;font-weight:bold;">{{ $order->order_number }}</span>
            </td></tr>
        </table>

        <p style="color:#9C9788;font-size:12px;margin:0;">
            Pour toute question: <a href="{{ config('app.url') }}/support" style="color:#C9A24B;">contactez notre support</a>
        </p>
    </td></tr>

    <tr><td style="padding:16px 32px;border-top:1px solid #2A2C36;text-align:center;">
        <p style="color:#9C9788;font-size:10px;font-family:monospace;margin:0;">© {{ date('Y') }} Boutique Paris</p>
    </td></tr>
</table>
</td></tr>
</table>
</body></html>