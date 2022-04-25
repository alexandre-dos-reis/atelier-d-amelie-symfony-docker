<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AmountExtension extends AbstractExtension
{

    public function getFilters()
    {
        return [
            new TwigFilter('amount', [$this, 'amount'])
        ];
    }

    public function amount(
        $value,
        string $currency = '€',
        string $decimalSep = ',',
        string $thounsandSep = ' '
    ): string {
        $price = $value / 100;
        //$numberOfDecimal = $this->hasDecimal($price) ? 2 : 0;
        return number_format($price, 2, $decimalSep, $thounsandSep) . ' ' . $currency;
    }

    // private function hasDecimal(float $value): bool
    // {
    //     return is_numeric($value) && floor($value) != $value;
    // }
}
