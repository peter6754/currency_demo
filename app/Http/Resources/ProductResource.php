<?php

// app/Http/Resources/ProductResource.php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        $currency = $request->query('currency');

        $convertedPrice = $this->convertPrice($this->price, $currency);
        $formattedPrice = $this->formatPrice($convertedPrice, $currency);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'price' => $formattedPrice,
        ];
    }

    protected function convertPrice($price, $currency)
    {
        $rates = [
            'RUB' => 1,
            'USD' => 90,
            'EUR' => 100,
        ];

        return $price / ($rates[$currency] ?? 1);
    }

    protected function formatPrice($price, $currency)
    {
        if ($currency === 'USD') {
            return '$'.number_format($price, 2);
        } elseif ($currency === 'EUR') {
            return '€'.number_format($price, 2);
        } else {
            return number_format($price, 0, '.', ' ').' ₽';
        }
    }
}
