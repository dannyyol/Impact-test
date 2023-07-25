<?php

namespace App\Services\Checkout;

class PriceRule
{
    private int $specialQty;
    private int $unitPrice;
    private int $specialPrice;
    public function __construct(int $unitPrice, int $specialPrice = 0, int $specialQty = 0)
    {
        $this->unitPrice = $unitPrice;
        $this->specialPrice = $specialPrice;
        $this->specialQty = $specialQty;
    }

    public function calculateTotalPriceOfItems(int $quantity): int
    {
        $groupSpecialItems = intval($quantity / $this->specialQty);
        $specialPriceItems = $groupSpecialItems * $this->specialPrice;
        $regularPriceItems = ($quantity % $this->specialQty) * $this->unitPrice;
        return $specialPriceItems + $regularPriceItems;
    }
}
