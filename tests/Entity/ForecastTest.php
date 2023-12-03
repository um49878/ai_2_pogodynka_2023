<?php

namespace App\Tests\Entity;

use App\Entity\Forecast;
use PHPUnit\Framework\TestCase;


class ForecastTest extends TestCase
{

    /**
     * @dataProvider dataGetFahrenheit
     */
    public function testGetFahrenheit($celsius, $expectedFahrenheit): void
    {
        $forecast = new Forecast();

        $forecast->setTemperature($celsius);
        $fahrenheit = $forecast->getFahrenheit();
        $this->assertEquals($expectedFahrenheit, $fahrenheit, "$celsius °C = $expectedFahrenheit °F");

    }

    public function dataGetFahrenheit(): array
    {
        return [
            ['0', 32],
            ['100', 212],
            ['36.6', 97.88],
            ['-67', -88.6],
            ['42', 107.6],
            ['18', 64.4],
            ['-89', -128.2],
            ['75', 167],
            ['10', 50],
            ['-32', -25.6]
        ];
    }
}
