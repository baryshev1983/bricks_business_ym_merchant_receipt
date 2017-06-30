<?php
namespace Bricks\Business\YmMerchantReceipt\UnitTest;

use PHPUnit\Framework\TestCase;
use Bricks\Business\YmMerchantReceipt\Price;
use Bricks\Business\YmMerchantReceipt\Exception\InvalidArgumentException;

/**
 * @author Artur Sh. Mamedbekov
 */
class PriceTest extends TestCase{
  public function testConstruct_testInvalidAmount(){
    $this->expectException(InvalidArgumentException::class);
    new Price([]);
  }

  public function testConstruct_testInvalidCurrency(){
    $this->expectException(InvalidArgumentException::class);
    new Price(10, 1);
  }

  public function testConstruct_testInvalidLengthCurrency(){
    $this->expectException(InvalidArgumentException::class);
    new Price(10, 'INVALID');
  }

  public function testToJson(){
    $this->assertEquals('{"amount":10.50,"currency":"RUB"}', (new Price(10.5))->toJson());
    $this->assertEquals('{"amount":10.00,"currency":"USD"}', (new Price(10, 'USD'))->toJson());
  }
}
