<!DOCTYPE html>
<html><head><meta charset="utf-8"></head>
<body style="margin:0;padding:0;background:#14151C;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0">
<tr><td align="center" style="padding:40px 20px;">
<table width="540" cellpadding="0" cellspacing="0" style="background:#1C1E27;border-radius:16px;overflow:hidden;border:1px solid #2A2C36;">

    {{-- Header --}}
    <tr><td style="background:#C9A24B;padding:24px 32px;">
        <p style="color:#14151C;font-size:11px;letter-spacing:3px;text-transform:uppercase;margin:0;font-family:monospace;">
            Boutique Paris
        </p>
    </td></tr>

    {{-- Body --}}
    <tr><td style="padding:32px;">
        <h1 style="color:#F6F3EC;font-size:24px;margin:0 0 8px;font-weight:normal;font-family:Georgia,serif;">
            ✅ Commande confirmée !
        </h1>
        <p style="color:#9C9788;font-size:13px;margin:0 0 28px;">
            Bonjour {{ $order->shipping_name }}, votre commande a bien été reçue.
        </p>

        {{-- Recap --}}
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#14151C;border-radius:12px;overflow:hidden;margin-bottom:24px;">
            <tr><td style="padding:16px 20px;border-bottom:1px solid #2A2C36;">
                <span style="color:#9C9788;font-size:10px;font-family:monospace;text-transform:uppercase;letter-spacing:2px;">N° Commande</span><br>
                <span style="color:#C9A24B;font-size:16px;font-family:monospace;font-weight:bold;">{{ $order->order_number }}</span>
            </td></tr>
            <tr><td style="padding:16px 20px;border-bottom:1px solid #2A2C36;">
                <span style="color:#9C9788;font-size:10px;font-family:monospace;text-transform:uppercase;letter-spacing:2px;">Livraison</span><br>
                <span style="color:#F6F3EC;font-size:13px;">{{ $order->delivery_type === 'stop_desk' ? 'Stop Desk' : 'À Domicile' }} — {{ $order->shipping_wilaya }}</span>
            </td></tr>
            <tr><td style="padding:16px 20px;border-bottom:1px solid #2A2C36;">
                <span style="color:#9C9788;font-size:10px;font-family:monospace;text-transform:uppercase;letter-spacing:2px;">Adresse</span><br>
                <span style="color:#F6F3EC;font-size:13px;">{{ $order->shipping_address }}, {{ $order->shipping_commune }}</span>
            </td></tr>
            <tr><td style="padding:16px 20px;">
                <span style="color:#9C9788;font-size:10px;font-family:monospace;text-transform:uppercase;letter-spacing:2px;">Total</span><br>
                <span style="color:#C9A24B;font-size:20px;font-weight:bold;font-family:monospace;">{{ number_format($order->total, 0, ',', ' ') }} DA</span>
            </td></tr>
        </table>

        {{-- Articles --}}
        <p style="color:#9C9788;font-size:10px;letter-spacing:2px;text-transform:uppercase;margin:0 0 12px;font-family:monospace;">Articles commandés</p>
        @foreach ($order->items as $item)
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:8px;">
                <tr>
                    <td style="color:#F6F3EC;font-size:13px;">{{ $item->product_name }}</td>
                    <td style="color:#9C9788;font-size:12px;text-align:right;">× {{ $item->quantity }} — {{ number_format($item->total, 0, ',', ' ') }} DA</td>
                </tr>
            </table>
        @endforeach

        <p style="color:#9C9788;font-size:12px;margin:24px 0 0;line-height:1.6;">
            Vous recevrez une notification dès que votre commande sera expédiée. Pour toute question, contactez notre support.
        </p>
    </td></tr>

    {{-- Footer --}}
    <tr><td style="padding:16px 32px;border-top:1px solid #2A2C36;text-align:center;">
        <p style="color:#9C9788;font-size:10px;font-family:monospace;margin:0;">
            © {{ date('Y') }} Boutique Paris — <a href="{{ config('app.url') }}/support" style="color:#C9A24B;">Support</a>
        </p>
    </td></tr>
</table>
</td></tr>
</table>
</body></html>