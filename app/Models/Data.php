<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Data extends Model
{
    use HasFactory;

    private const API_URL = 'https://u0362146.plsk.regruhosting.ru/api';

    public function getData()
    {
        $client = new Client();

        try {
            $response = $client->get(self::API_URL);
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            return response()->json(['error' => "can't get data from API. Error $statusCode "]);
        }

        if ($response->getStatusCode() != 200) {
            return response()->json(['error' => "can't get data from API"], 400);
        }

        $data = json_decode($response->getBody(), true);
        $priceSum = 0;
        $uniqueWarehouse = array();

        foreach ($data as $item) {
            $priceSum = $item['Price'];
            if (!in_array($item['warehouseName'], $uniqueWarehouse)) {
                $uniqueWarehouse[] = $item['warehouseName'];
            }
        }

        return response()->json(['priceSum' => $priceSum, 'uniqueWarehouse' => $uniqueWarehouse], 200, [], JSON_UNESCAPED_UNICODE);
    }

}
