<?php
namespace Bricks\Business\YmMerchantReceipt;

use Bricks\Business\YmMerchantReceipt\Exception\InvalidArgumentException;

/**
 * Продукт.
 *
 * @author Artur Sh. Mamedbekov
 */
class Item implements JsonSerializableInterface{
  /**
   * Без НДС.
   */
  const TAX_NO = 1;

  /**
   * Ставка НДС 0%.
   */
  const TAX_0 = 2;

  /**
   * Ставка НДС 10%.
   */
  const TAX_10 = 3;

  /**
   * Ставка НДС 18%.
   */
  const TAX_18 = 4;

  /**
   * Ставка НДС 10/110.
   */
  const TAX_10_110 = 5;

  /**
   * Ставка НДС 18/118.
   */
  const TAX_18_118 = 6;

  /**
   * @var float Количество продукта в заказе (в том числе весового).
   */
  private $quantity;

  /**
   * @var Price Стоимость за единицу продукта.
   */
  private $price;

  /**
   * @var int Ставка НДС.
   *
   * @see self::TAX_*
   */
  private $tax;

  /**
   * @var string Название товара.
   */
  private $text;

  /**
   * @return int[] Допустимые значения параметра tax.
   */
  public static function getTaxValues(){
    return [
      self::TAX_NO,
      self::TAX_0,
      self::TAX_10,
      self::TAX_18,
      self::TAX_10_110,
      self::TAX_18_118,
    ];
  }

  /**
   * @param string $text Название товара.
   * @param float|int|string $quantity Количество продукта в заказе.
   * @param Price $price Стоимость за единицу продукта.
   * @param int $tax Ставка НДС.
   *
   * @throws InvalidArgumentException
   *
   * @see self::TAX_*
   */
  public function __construct($text, $quantity, Price $price, $tax){
    if(!is_string($text)){
      throw InvalidArgumentException::fromParam('text', 'string', $text);
    }
    $textLen = strlen($text);
    if($textLen < 1 || $textLen > 64){
      throw new InvalidArgumentException(sprintf(
        'Length the "text" should be "[0-64]" chars, "%s" given.',
        $textLen
      ));
    }
    $this->text = $text;
    if(!is_string($quantity) && !is_int($quantity) && !is_float($quantity)){
      throw InvalidArgumentException::fromParam('quantity', 'float|int|string', $quantity);
    }
    $this->quantity = (float) $quantity;
    $this->price = $price;
    if(!is_int($tax)){
      throw InvalidArgumentException::fromParam('tax', 'int', $tax);
    }
    if(!in_array($tax, self::getTaxValues())){
      throw new InvalidArgumentException(sprintf(
        'The tax should be "[1-6]", %s given',
        $tax
      ));
    }
    $this->tax = $tax;
  }

  /**
   * @return float Количество продукта в заказе.
   */
  public function getQuantity(){
    return $this->quantity;
  }

  /**
   * @return Price Стоимость единицы продукта.
   */
  public function getPrice(){
    return $this->price;
  }

  /**
   * @return int Ставка НДС.
   */
  public function getTax(){
    return $this->tax;
  }

  /**
   * @return string Название товара.
   */
  public function getText(){
    return $this->text;
  }

  /**
   * {@inheritdoc}
   */
  public function toJson(){
    return sprintf(
      '{"quantity":%01.3f,"price":%s,"tax":%s,"text":"%s"}',
      $this->quantity,
      $this->price->toJson(),
      $this->tax,
      addcslashes(addcslashes($this->text, '/'), '"')
    );
  }
}
