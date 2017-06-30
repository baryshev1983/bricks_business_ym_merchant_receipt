<?php
namespace Bricks\Business\YmMerchantReceipt\UnitTest;

use PHPUnit\Framework\TestCase;
use Bricks\Business\YmMerchantReceipt\ItemList;
use Bricks\Business\YmMerchantReceipt\Item;
use Bricks\Business\YmMerchantReceipt\Price;

/**
 * @author Artur Sh. Mamedbekov
 */
class ItemListTest extends TestCase{
  public function testToJson(){
    $itemList = new ItemList;
    $itemList->add(new Item('nameA', 1, new Price(10.5), Item::TAX_NO));
    $itemList->add(new Item('nameB', 2, new Price(10.5), Item::TAX_NO));

    $this->assertEquals(
      '[{"quantity":1.000,"price":{"amount":10.50,"currency":"RUB"},"tax":1,"text":"nameA"},{"quantity":2.000,"price":{"amount":10.50,"currency":"RUB"},"tax":1,"text":"nameB"}]',
      $itemList->toJson()
    );
  }

  public function testGetSum(){
    $itemList = new ItemList;
    $itemList->add(new Item('nameA', 1, new Price(1), Item::TAX_NO));
    $itemList->add(new Item('nameB', 2, new Price(1.5), Item::TAX_NO));

    $this->assertEquals(4.0, $itemList->getSum());
  }
}
