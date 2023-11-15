<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ResetCurrencyConverterLimit extends Command
{
    protected $signature = 'currency-converter:reset-limit';
    protected $description = 'Reseta o limite de uso do conversor de moedas';

    public function handle()
    {
        $this->resetLimit();
        $this->info('Limite de uso do conversor de moedas redefinido com sucesso.');
    }

    private function resetLimit()
    {
        // Limpe o cache de limites para todos os IPs
        $keys = Cache::get('currency_converter_limit_*');
        Cache::forget($keys);
    }
}
