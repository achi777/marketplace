<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShippingService
{
    protected $upsConfig;
    protected $fedexConfig;

    public function __construct()
    {
        $this->upsConfig = [
            'access_key' => config('services.ups.access_key'),
            'user_id' => config('services.ups.user_id'),
            'password' => config('services.ups.password'),
            'shipper_number' => config('services.ups.shipper_number'),
            'api_url' => config('services.ups.api_url'),
        ];

        $this->fedexConfig = [
            'account_number' => config('services.fedex.account_number'),
            'meter_number' => config('services.fedex.meter_number'),
            'key' => config('services.fedex.key'),
            'password' => config('services.fedex.password'),
            'api_url' => config('services.fedex.api_url'),
        ];
    }

    public function calculateShippingRates(array $fromAddress, array $toAddress, array $packages): array
    {
        $rates = [];

        // Get UPS rates
        try {
            $upsRates = $this->getUPSRates($fromAddress, $toAddress, $packages);
            $rates = array_merge($rates, $upsRates);
        } catch (\Exception $e) {
            Log::error('UPS rate calculation failed: ' . $e->getMessage());
        }

        // Get FedEx rates
        try {
            $fedexRates = $this->getFedExRates($fromAddress, $toAddress, $packages);
            $rates = array_merge($rates, $fedexRates);
        } catch (\Exception $e) {
            Log::error('FedEx rate calculation failed: ' . $e->getMessage());
        }

        // Sort by price
        usort($rates, function ($a, $b) {
            return $a['cost'] <=> $b['cost'];
        });

        return $rates;
    }

    public function getUPSRates(array $fromAddress, array $toAddress, array $packages): array
    {
        if (!$this->isUPSConfigured()) {
            return [];
        }

        $requestData = [
            'UPSSecurity' => [
                'UsernameToken' => [
                    'Username' => $this->upsConfig['user_id'],
                    'Password' => $this->upsConfig['password']
                ],
                'ServiceAccessToken' => [
                    'AccessLicenseNumber' => $this->upsConfig['access_key']
                ]
            ],
            'RateRequest' => [
                'Request' => [
                    'RequestOption' => 'Rate',
                    'TransactionReference' => [
                        'CustomerContext' => 'Rating'
                    ]
                ],
                'Shipper' => [
                    'Name' => 'Marketplace Seller',
                    'ShipperNumber' => $this->upsConfig['shipper_number'],
                    'Address' => [
                        'AddressLine' => [$fromAddress['address']],
                        'City' => $fromAddress['city'],
                        'StateProvinceCode' => $fromAddress['state'],
                        'PostalCode' => $fromAddress['zip'],
                        'CountryCode' => $fromAddress['country']
                    ]
                ],
                'ShipTo' => [
                    'Address' => [
                        'AddressLine' => [$toAddress['address']],
                        'City' => $toAddress['city'],
                        'StateProvinceCode' => $toAddress['state'],
                        'PostalCode' => $toAddress['zip'],
                        'CountryCode' => $toAddress['country']
                    ]
                ],
                'ShipFrom' => [
                    'Address' => [
                        'AddressLine' => [$fromAddress['address']],
                        'City' => $fromAddress['city'],
                        'StateProvinceCode' => $fromAddress['state'],
                        'PostalCode' => $fromAddress['zip'],
                        'CountryCode' => $fromAddress['country']
                    ]
                ],
                'Package' => []
            ]
        ];

        // Add packages
        foreach ($packages as $package) {
            $requestData['RateRequest']['Package'][] = [
                'PackagingType' => [
                    'Code' => '02', // Customer Supplied Package
                    'Description' => 'Package'
                ],
                'Dimensions' => [
                    'UnitOfMeasurement' => [
                        'Code' => 'IN',
                        'Description' => 'Inches'
                    ],
                    'Length' => $package['length'] ?? 12,
                    'Width' => $package['width'] ?? 12,
                    'Height' => $package['height'] ?? 6
                ],
                'PackageWeight' => [
                    'UnitOfMeasurement' => [
                        'Code' => 'LBS',
                        'Description' => 'Pounds'
                    ],
                    'Weight' => $package['weight'] ?? 1
                ]
            ];
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'AccessLicenseNumber' => $this->upsConfig['access_key']
        ])->post($this->upsConfig['api_url'] . '/Rating/v1701/Rate', $requestData);

        if ($response->successful()) {
            return $this->parseUPSResponse($response->json());
        }

        throw new \Exception('UPS API request failed: ' . $response->body());
    }

    public function getFedExRates(array $fromAddress, array $toAddress, array $packages): array
    {
        if (!$this->isFedExConfigured()) {
            return [];
        }

        // For demo purposes, return mock FedEx rates
        // In production, implement actual FedEx API calls
        return [
            [
                'carrier' => 'FedEx',
                'service' => 'FedEx Ground',
                'cost' => 8.99,
                'estimated_days' => '3-5',
                'service_code' => 'FEDEX_GROUND'
            ],
            [
                'carrier' => 'FedEx',
                'service' => 'FedEx Express',
                'cost' => 24.99,
                'estimated_days' => '2',
                'service_code' => 'FEDEX_EXPRESS'
            ]
        ];
    }

    protected function parseUPSResponse(array $response): array
    {
        $rates = [];

        if (isset($response['RateResponse']['RatedShipment'])) {
            $shipments = $response['RateResponse']['RatedShipment'];
            
            // Handle single shipment response
            if (!isset($shipments[0])) {
                $shipments = [$shipments];
            }

            foreach ($shipments as $shipment) {
                $rates[] = [
                    'carrier' => 'UPS',
                    'service' => $shipment['Service']['Code'] ?? 'UPS Standard',
                    'cost' => (float) $shipment['TotalCharges']['MonetaryValue'],
                    'estimated_days' => $this->getUPSDeliveryDays($shipment['Service']['Code'] ?? '03'),
                    'service_code' => $shipment['Service']['Code'] ?? '03'
                ];
            }
        }

        return $rates;
    }

    protected function getUPSDeliveryDays(string $serviceCode): string
    {
        $deliveryTimes = [
            '01' => '1', // UPS Next Day Air
            '02' => '2', // UPS 2nd Day Air
            '03' => '3-5', // UPS Ground
            '12' => '1-3', // UPS 3 Day Select
            '13' => '1', // UPS Next Day Air Saver
            '14' => '1', // UPS Next Day Air Early A.M.
            '59' => '2', // UPS 2nd Day Air A.M.
        ];

        return $deliveryTimes[$serviceCode] ?? '3-5';
    }

    public function createShipment(Order $order, string $carrier, string $serviceCode): array
    {
        switch (strtolower($carrier)) {
            case 'ups':
                return $this->createUPSShipment($order, $serviceCode);
            case 'fedex':
                return $this->createFedExShipment($order, $serviceCode);
            default:
                throw new \Exception('Unsupported carrier: ' . $carrier);
        }
    }

    protected function createUPSShipment(Order $order, string $serviceCode): array
    {
        // Mock shipment creation for demo
        return [
            'tracking_number' => '1Z999AA1234567890',
            'label_url' => 'https://example.com/label.pdf',
            'cost' => 12.50,
            'carrier' => 'UPS',
            'service' => $serviceCode
        ];
    }

    protected function createFedExShipment(Order $order, string $serviceCode): array
    {
        // Mock shipment creation for demo
        return [
            'tracking_number' => '1234567890123456',
            'label_url' => 'https://example.com/fedex-label.pdf',
            'cost' => 15.75,
            'carrier' => 'FedEx',
            'service' => $serviceCode
        ];
    }

    public function trackShipment(string $trackingNumber, string $carrier): array
    {
        switch (strtolower($carrier)) {
            case 'ups':
                return $this->trackUPSShipment($trackingNumber);
            case 'fedex':
                return $this->trackFedExShipment($trackingNumber);
            default:
                throw new \Exception('Unsupported carrier: ' . $carrier);
        }
    }

    protected function trackUPSShipment(string $trackingNumber): array
    {
        // Mock tracking for demo
        return [
            'tracking_number' => $trackingNumber,
            'status' => 'In Transit',
            'estimated_delivery' => now()->addDays(2)->format('Y-m-d'),
            'tracking_events' => [
                [
                    'date' => now()->subDay()->format('Y-m-d H:i:s'),
                    'status' => 'Package shipped',
                    'location' => 'Origin facility'
                ],
                [
                    'date' => now()->format('Y-m-d H:i:s'),
                    'status' => 'In transit',
                    'location' => 'Sorting facility'
                ]
            ]
        ];
    }

    protected function trackFedExShipment(string $trackingNumber): array
    {
        // Mock tracking for demo
        return [
            'tracking_number' => $trackingNumber,
            'status' => 'In Transit',
            'estimated_delivery' => now()->addDays(1)->format('Y-m-d'),
            'tracking_events' => [
                [
                    'date' => now()->subDay()->format('Y-m-d H:i:s'),
                    'status' => 'Package picked up',
                    'location' => 'Pickup location'
                ],
                [
                    'date' => now()->format('Y-m-d H:i:s'),
                    'status' => 'At FedEx facility',
                    'location' => 'Transit hub'
                ]
            ]
        ];
    }

    public function getPackageDetailsFromOrder(Order $order): array
    {
        $packages = [];
        $currentWeight = 0;
        $maxWeight = 50; // 50 lbs per package

        foreach ($order->items as $item) {
            $product = $item->productVariation->product;
            $itemWeight = ($product->weight ?? 1) * $item->quantity;

            if ($currentWeight + $itemWeight > $maxWeight) {
                // Create new package
                if ($currentWeight > 0) {
                    $packages[] = [
                        'weight' => $currentWeight,
                        'length' => 12,
                        'width' => 12,
                        'height' => 6
                    ];
                }
                $currentWeight = $itemWeight;
            } else {
                $currentWeight += $itemWeight;
            }
        }

        // Add final package
        if ($currentWeight > 0) {
            $packages[] = [
                'weight' => $currentWeight,
                'length' => 12,
                'width' => 12,
                'height' => 6
            ];
        }

        return $packages ?: [[
            'weight' => 1,
            'length' => 12,
            'width' => 12,
            'height' => 6
        ]];
    }

    protected function isUPSConfigured(): bool
    {
        return !empty($this->upsConfig['access_key']) && 
               !empty($this->upsConfig['user_id']) && 
               !empty($this->upsConfig['password']);
    }

    protected function isFedExConfigured(): bool
    {
        return !empty($this->fedexConfig['account_number']) && 
               !empty($this->fedexConfig['key']) && 
               !empty($this->fedexConfig['password']);
    }
}