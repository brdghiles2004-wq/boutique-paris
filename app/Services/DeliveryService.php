<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeliveryService
{
    public function createShipment(Order $order, string $company, float $weight = 0.5): array
    {
        return match($company) {
            'yalidine' => $this->yalidine($order, $weight),
            'zr'       => $this->zrExpress($order, $weight),
            'maystro'  => $this->maystro($order, $weight),
            'ecotrack' => $this->ecotrack($order, $weight),
            default    => throw new \Exception("Société inconnue: {$company}"),
        };
    }

    // ========== YALIDINE ==========
    private function yalidine(Order $order, float $weight): array
    {
        $apiId    = Setting::get('yalidine_api_id');
        $apiToken = Setting::get('yalidine_api_token');
        $sandbox  = Setting::get('yalidine_mode', 'sandbox') === 'sandbox';

        if (!$apiId || !$apiToken) {
            throw new \Exception('Yalidine non configuré');
        }

        $baseUrl = $sandbox
            ? 'https://api.yalidine.app/v1'
            : 'https://api.yalidine.app/v1';

        $response = Http::withHeaders([
            'X-API-ID'    => $apiId,
            'X-API-TOKEN' => $apiToken,
            'Content-Type'=> 'application/json',
        ])->post("{$baseUrl}/parcels/", [
            'order_id'          => $order->order_number,
            'firstname'         => $this->getFirstName($order->shipping_name),
            'familyname'        => $this->getLastName($order->shipping_name),
            'contact_phone'     => $order->shipping_phone,
            'address'           => $order->shipping_address,
            'to_wilaya_id'      => $this->getWilayaId($order->shipping_wilaya),
            'to_commune_name'   => $order->shipping_commune ?? '',
            'product_list'      => $this->getProductList($order),
            'price'             => $order->total,
            'do_insurance'      => false,
            'declared_value'    => $order->total,
            'height'            => 10,
            'width'             => 20,
            'length'            => 30,
            'weight'            => $weight,
            'freeshipping'      => false,
            'is_stopdesk'       => $order->delivery_type === 'stop_desk',
        ]);

        if ($response->failed()) {
            throw new \Exception('Yalidine error: ' . $response->body());
        }

        $data = $response->json();
        return [
            'tracking' => $data['tracking'] ?? $data['id'] ?? 'YALI-' . rand(100000, 999999),
            'label_url'=> $data['label_url'] ?? null,
            'company'  => 'yalidine',
        ];
    }

    // ========== ZR EXPRESS ==========
    private function zrExpress(Order $order, float $weight): array
    {
        $apiKey = Setting::get('zr_express_key');
        $secret = Setting::get('zr_express_secret');

        if (!$apiKey) throw new \Exception('ZR Express non configuré');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'X-Secret'      => $secret,
        ])->post('https://api.zrexpress.dz/api/parcel/add', [
            'order_number'  => $order->order_number,
            'client_name'   => $order->shipping_name,
            'client_phone'  => $order->shipping_phone,
            'client_phone2' => '',
            'adress'        => $order->shipping_address,
            'wilaya'        => $order->shipping_wilaya,
            'commune'       => $order->shipping_commune ?? '',
            'product_name'  => $this->getProductList($order),
            'price'         => $order->total,
            'weight'        => $weight,
            'can_open'      => true,
            'is_stopdesk'   => $order->delivery_type === 'stop_desk',
        ]);

        if ($response->failed()) {
            throw new \Exception('ZR Express error: ' . $response->body());
        }

        $data = $response->json();
        return [
            'tracking' => $data['tracking'] ?? $data['barcode'] ?? 'ZR-' . rand(100000, 999999),
            'label_url'=> $data['label'] ?? null,
            'company'  => 'zr',
        ];
    }

    // ========== MAYSTRO ==========
    private function maystro(Order $order, float $weight): array
    {
        $apiKey = Setting::get('maystro_api_key');
        $token  = Setting::get('maystro_token');

        if (!$apiKey) throw new \Exception('Maystro non configuré');

        $response = Http::withHeaders([
            'Authorization' => 'Token ' . $apiKey,
        ])->post('https://app.maystro-delivery.com/api/v2/orders/', [
            'reference'       => $order->order_number,
            'customer_name'   => $order->shipping_name,
            'customer_phone'  => $order->shipping_phone,
            'address'         => $order->shipping_address,
            'wilaya'          => $order->shipping_wilaya,
            'commune'         => $order->shipping_commune ?? '',
            'product_name'    => $this->getProductList($order),
            'price'           => $order->total,
            'weight'          => $weight,
            'pickup'          => false,
            'is_stopdesk'     => $order->delivery_type === 'stop_desk',
        ]);

        if ($response->failed()) {
            throw new \Exception('Maystro error: ' . $response->body());
        }

        $data = $response->json();
        return [
            'tracking' => $data['tracking_code'] ?? $data['id'] ?? 'MAY-' . rand(100000, 999999),
            'label_url'=> null,
            'company'  => 'maystro',
        ];
    }

    // ========== ECOTRACK ==========
    private function ecotrack(Order $order, float $weight): array
    {
        $username = Setting::get('ecotrack_username');
        $password = Setting::get('ecotrack_password');
        $apiKey   = Setting::get('ecotrack_api_key');

        if (!$username) throw new \Exception('Ecotrack non configuré');

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode("{$username}:{$password}"),
            'api-key'       => $apiKey,
        ])->post('https://ecotrack.dz/api/v2/parcels', [
            'reference'    => $order->order_number,
            'name'         => $order->shipping_name,
            'phone'        => $order->shipping_phone,
            'address'      => $order->shipping_address,
            'wilaya'       => $order->shipping_wilaya,
            'commune'      => $order->shipping_commune ?? '',
            'products'     => $this->getProductList($order),
            'cod'          => $order->total,
            'weight'       => $weight,
            'stop_desk'    => $order->delivery_type === 'stop_desk',
        ]);

        if ($response->failed()) {
            throw new \Exception('Ecotrack error: ' . $response->body());
        }

        $data = $response->json();
        return [
            'tracking' => $data['tracking_number'] ?? $data['id'] ?? 'ECO-' . rand(100000, 999999),
            'label_url'=> $data['label_url'] ?? null,
            'company'  => 'ecotrack',
        ];
    }

    // ========== HELPERS ==========
    private function getProductList(Order $order): string
    {
        return $order->items->map(fn($i) =>
            "{$i->product_name} x{$i->quantity}"
        )->join(', ');
    }

    private function getFirstName(string $fullName): string
    {
        return explode(' ', $fullName)[0] ?? $fullName;
    }

    private function getLastName(string $fullName): string
    {
        $parts = explode(' ', $fullName);
        return count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : '';
    }

    private function getWilayaId(string $wilayaName): int
    {
        $wilayas = [
            'Adrar'=>1,'Chlef'=>2,'Laghouat'=>3,'Oum El Bouaghi'=>4,
            'Batna'=>5,'Béjaïa'=>6,'Biskra'=>7,'Béchar'=>8,'Blida'=>9,
            'Bouira'=>10,'Tamanrasset'=>11,'Tébessa'=>12,'Tlemcen'=>13,
            'Tiaret'=>14,'Tizi Ouzou'=>15,'Alger'=>16,'Djelfa'=>17,
            'Jijel'=>18,'Sétif'=>19,'Saïda'=>20,'Skikda'=>21,
            'Sidi Bel Abbès'=>22,'Annaba'=>23,'Guelma'=>24,'Constantine'=>25,
            'Médéa'=>26,'Mostaganem'=>27,'M\'Sila'=>28,'Mascara'=>29,
            'Ouargla'=>30,'Oran'=>31,'El Bayadh'=>32,'Illizi'=>33,
            'Bordj Bou Arréridj'=>34,'Boumerdès'=>35,'El Tarf'=>36,
            'Tindouf'=>37,'Tissemsilt'=>38,'El Oued'=>39,'Khenchela'=>40,
            'Souk Ahras'=>41,'Tipaza'=>42,'Mila'=>43,'Aïn Defla'=>44,
            'Naâma'=>45,'Aïn Témouchent'=>46,'Ghardaïa'=>47,'Relizane'=>48,
            'Timimoun'=>49,'Bordj Badji Mokhtar'=>50,'Ouled Djellal'=>51,
            'Béni Abbès'=>52,'In Salah'=>53,'In Guezzam'=>54,
            'Touggourt'=>55,'Djanet'=>56,'El M\'Ghair'=>57,'El Meniaa'=>58,
        ];
        return $wilayas[$wilayaName] ?? 16;
    }
}