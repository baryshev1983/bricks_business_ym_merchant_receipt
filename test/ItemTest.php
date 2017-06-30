<?php
namespace Bricks\Business\YmMerchantReceipt\UnitTest;

use PHPUnit\Framework\TestCase;
use Bricks\Business\YmMerchantReceipt\Item;
use Bricks\Business\YmMerchantReceipt\Price;
use Bricks\Business\YmMerchantReceipt\Exception\InvalidArgumentException;

/**
 * @author Artur Sh. Mamedbekov
 */
class ItemTest extends TestCase{
  /**
   * @var Price
   */
  private $defaultPrice;

  /**
   * {@inheritdoc}
   */
  public function setUp(){
    $this->defaultPrice = new Price(1, 'RUB');
  }

  public function testConstruct_testInvalidText(){
    $this->expectException(InvalidArgumentException::class);
    new Item(1, 1, $this->defaultPrice, Item::TAX_NO);
  }

  public function testConstruct_testInvalidQuantity(){
    $this->expectException(InvalidArgumentException::class);
    new Item('text', [], $this->defaultPrice, Item::TAX_NO);
  }

  public function testConstruct_testInvalidTaxType(){
    $this->expectException(InvalidArgumentException::class);
    new Item('text', 1, $this->defaultPrice, '');
  }

  public function testConstruct_testInvalidTaxValue(){
    $this->expectException(InvalidArgumentException::class);
    new Item('text', 1, $this->defaultPrice, 0);
  }

  public function testToJson(){
    $this->assertEquals(
      '{"quantity":1.000,"price":{"amount":10.50,"currency":"RUB"},"tax":1,"text":"Зеленый чай \"Юн Ву\", кг"}',
      (new Item('Зеленый чай "Юн Ву", кг', 1, new Price(10.5), Item::TAX_NO))->toJson()
    );
  }
}
