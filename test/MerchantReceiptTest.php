<?php
namespace Bricks\Business\YmMerchantReceipt\UnitTest;

use PHPUnit\Framework\TestCase;
use Bricks\Business\YmMerchantReceipt\MerchantReceipt;
use Bricks\Business\YmMerchantReceipt\ItemList;
use Bricks\Business\YmMerchantReceipt\Item;
use Bricks\Business\YmMerchantReceipt\Price;
use Bricks\Business\YmMerchantReceipt\Exception\InvalidArgumentException;

/**
 * @author Artur Sh. Mamedbekov
 */
class MerchantReceiptTest extends TestCase{
  public function testConstruct_testInvalidCustomerContact(){
    $this->expectException(InvalidArgumentException::class);
    new MerchantReceipt(1, 1, new ItemList);
  }

  public function testConstruct_testInvalidTaxSystemType(){
    $this->expectException(InvalidArgumentException::class);
    new MerchantReceipt('foo', [], new ItemList);
  }

  public function testConstruct_testInvalidTaxSystemValue(){
    $this->expectException(InvalidArgumentException::class);
    new MerchantReceipt('foo', 0, new ItemList);
  }

  public function testToJson(){
    $itemList = new ItemList;
    $itemList->add(new Item('nameA', 1, new Price(10.5), Item::TAX_NO));
    $itemList->add(new Item('nameB', 2, new Price(10.5), Item::TAX_NO));
    $merchantReceipt = new MerchantReceipt('client@mail.com', MerchantReceipt::TAX_SYSTEM_GENERAL, $itemList);

    $this->assertEquals(
      '{"customerContact":"client@mail.com","taxSystem":1,"items":[{"quantity":1.000,"price":{"amount":10.50,"currency":"RUB"},"tax":1,"text":"nameA"},{"quantity":2.000,"price":{"amount":10.50,"currency":"RUB"},"tax":1,"text":"nameB"}]}',
      $merchantReceipt->toJson()
    );
  }
}
