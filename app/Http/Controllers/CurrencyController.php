<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use App\Models\CurrencyConversion;

class CurrencyController extends Controller
{
    public function exchangeCurrency(Request $request)
    {
        $ip = $request->ip();
        $limit = 5; // Limit usage
        $cacheKey = 'exchange_limit_' . $ip;

        if ($this->isLimitReached($cacheKey, $limit)) {
            return $this->limitReached();
        }

        $apikey = '67bb595fb136587a08b8e7bcc48c599c';
        $from_Currency = $request->input('from_Currency');
        $to_Currency = $request->input('to_Currency');
        $amount = $request->input('amount', 1);

        $response = Http::get("http://api.currencylayer.com/convert", [
            'access_key' => $apikey,
            'from' => $from_Currency,
            'to' => $to_Currency,
            'amount' => $amount,
        ]);

        $data = json_decode($response, true);
        $array = array($data);

        if (isset($array[0]['result'])) {
            $result = $array[0]['result'];
            $formatValue = number_format($result, 2, '.', '');
            $amounted = "$from_Currency $amount = $to_Currency $formatValue";

            CurrencyConversion::create([
                'from_currency' => $from_Currency,
                'to_currency' => $to_Currency,
                'amount' => $amount,
                'converted_amount' => $result,
            ]);

            return response()->json(['result' => $amounted]);
        } else {

            return response()->json(['error' => 'The key "result" is not present on recived JSON.']);
        }
    }

    private function isLimitReached($cacheKey, $limit)
    {
        if (Cache::has($cacheKey)) {
            $count = Cache::get($cacheKey);

            if ($count >= $limit) {
                return true;
            }

            Cache::increment($cacheKey);
        } else {
            Cache::put($cacheKey, 1, now()->addMinutes(60));
        }

        return false;
    }

    public function limitReached()
    {

        return response()->json(['error' => 'Limit reached.']);
    }
}
