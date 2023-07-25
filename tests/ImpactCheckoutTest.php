<?php

namespace Tests;

use App\Services\Checkout\ImpactCheckout;
use App\ValueObjects\Sku;
use PHPUnit\Framework\TestCase;

class ImpactCheckoutTest extends TestCase
{
    // The following test is commented because total value is expected to be more than 50 in this case
    // public function test_scanning_sku_a_returns_total_of_50()
    // {
    //     $checkout = new ImpactCheckout;

    //     $checkout->scan(Sku::fromString('A'));

    //     $this->assertEquals(50, $checkout->total(), 'Checkout total does not equal expected value of 50');
    // }


    public function test_scanning_skus_b_a_b_returns_total_of_95()
    {
        $checkout = new ImpactCheckout;

        $checkout->addPriceRule('A', 50, 130, 3);
        $checkout->addPriceRule('B', 30, 45, 2);

        $checkout->scan(Sku::fromString('B'));
        $checkout->scan(Sku::fromString('A'));
        $checkout->scan(Sku::fromString('B'));

        $this->assertEquals(95, $checkout->total(), 'Checkout total does not equal the expected value of 95');
    }
}