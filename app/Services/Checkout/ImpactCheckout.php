<?php

namespace App\Services\Checkout;

use App\ValueObjects\Sku;
use Exception;

class ImpactCheckout implements CheckoutContract
{
    private array $scannedItems = [];

    // Array to store price rules for each item
    private array $pricingRules = [];

    // Method to add pricing rule for an item
    public function addPriceRule(string $sku, int $unitPrice, int $specialPrice = 0, int $specialQty = 0): void
    {
        $this->pricingRules[$sku] = new PriceRule($unitPrice, $specialPrice, $specialQty);
    }

    public function scan(Sku $sku): void
    {
        $skuString = $sku->sku;

        if (!isset($this->scannedItems[$skuString])) {
            $this->scannedItems[$skuString] = 0;
        }

        $this->scannedItems[$skuString]++;
    }

    public function total(): int
    {
        $totalPrice = 0;

        foreach ($this->scannedItems as $sku => $quantity) {
            if (isset($this->pricingRules[$sku])) {
                $priceRule = $this->pricingRules[$sku];
                $totalPrice += $priceRule->calculateTotalPriceOfItems($quantity);
            } else {
                // If no special pricig rule is defined throw an exception
                throw new Exception('Invalid price rule');
            }
        }

        return $totalPrice;
    }
}


$checkout = new ImpactCheckout();

// Define pricin rules for theitems
$checkout->addPriceRule('A', 50, 130, 3);
$checkout->addPriceRule('B', 30, 45, 2);
$checkout->addPriceRule('C', 20, 0, 0);
$checkout->addPriceRule('D', 15, 0, 0);
// More price rules can be added here.

// items aded by scanning
$checkout->scan(Sku::fromString('B'));
$checkout->scan(Sku::fromString('A'));
$checkout->scan(Sku::fromString('B'));

// Calculate total price
$totalPrice = $checkout->total();
echo "Total price: {$totalPrice}pence";
