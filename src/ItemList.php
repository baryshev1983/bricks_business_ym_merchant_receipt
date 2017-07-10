<?php
namespace Bricks\Business\YmMerchantReceipt;

use IteratorAggregate;
use ArrayIterator;
use Bricks\Business\YmMerchantReceipt\Exception\OverflowException;

/**
 * Список товаров в заказе.
 *
 * @author Artur Sh. Mamedbekov
 */
class ItemList implements JsonSerializableInterface, IteratorAggregate{
  /**
   * @var array Список товаров.
   */
  private $list;

  public function __construct(){
    $this->list = [];
  }

  /**
   * Добавляет товар в список.
   *
   * @throws OverflowException
   */
  public function add(Item $item){
    if(count($this->list) == 100){
      throw new OverflowException('This list is filled');
    }
    $this->list[] = $item;
  }

  /**
   * @return float Сумма заказа.
   */
  public function getSum(){
    return (float) array_reduce($this->list, function($carry, Item $item){
      return $carry + $item->getQuantity() * $item->getPrice()->getAmount();
    }, 0);
  }

  /**
   * {@inheritdoc}
   */
  public function toJson(){
    return sprintf('[%s]',
      implode(',', array_map(function(Item $item){
        return $item->toJson();
      }, $this->list))
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getIterator(){
    return new ArrayIterator($this->list);
  }
}
