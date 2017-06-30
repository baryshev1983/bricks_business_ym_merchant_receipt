<?php
namespace Bricks\Business\YmMerchantReceipt;

use Bricks\Business\YmMerchantReceipt\Exception\InvalidArgumentException;

/**
 * Квитанция продавца.
 *
 * @author Artur Sh. Mamedbekov
 */
class MerchantReceipt implements JsonSerializableInterface{
  /**
   * Общая.
   */
  const TAX_SYSTEM_GENERAL = 1;

  /**
   * Упрощенная (доходы).
   */
  const TAX_SYSTEM_SIMPLE_INCOMES = 2;

  /**
   * Упрощенная (доходы минус расходы).
   */
  const TAX_SYSTEM_SIMPLE_PROFIT = 2;

  /**
   * Единый налог на вмененный доход.
   */
  const TAX_SYSTEM_IMPUTED = 4;

  /**
   * Единый сельскохозяйственный налог.
   */
  const TAX_SYSTEM_AGRICULTURAL = 5;

  /**
   * Патентная.
   */
  const TAX_SYSTEM_PATENT = 6;

  /**
   * @var string Контактный номер телефона или email покупателя.
   */
  private $customerContact;

  /**
   * @var int Система налогообложения магазина.
   *
   * @see self::TAX_SYSTEM_*
   */
  private $taxSystem;

  /**
   * @var ItemList Список товаров в заказе.
   */
  private $items;

  /**
   * @return int[] Допустимые значения параметра tax.
   */
  public static function getTaxSystemValues(){
    return [
      self::TAX_SYSTEM_GENERAL,
      self::TAX_SYSTEM_SIMPLE_INCOMES,
      self::TAX_SYSTEM_SIMPLE_PROFIT,
      self::TAX_SYSTEM_IMPUTED,
      self::TAX_SYSTEM_AGRICULTURAL,
      self::TAX_SYSTEM_PATENT,
    ];
  }

  /**
   * @param string $customerContact Контактный номер телефона или email 
   * покупателя.
   * @param int $taxSystem Система налогообложения магазина.
   * @param ItemList $items Список товаров в заказе.
   *
   * @see self::TAX_SYSTEM_*
   */
  public function __construct($customerContact, $taxSystem, ItemList $items){
    if(!is_string($customerContact)){
      throw InvalidArgumentException::fromParam('customerContact', 'string', $customerContact);
    }
    $this->customerContact = substr($customerContact, 0, 64);
    if(!is_int($taxSystem)){
      throw InvalidArgumentException::fromParam('taxSystem', 'int', $taxSystem);
    }
    if(!in_array($taxSystem, self::getTaxSystemValues())){
      throw new InvalidArgumentException(sprintf(
        'The tax system should be "[1-6]", %s given',
        $taxSystem
      ));
    }
    $this->taxSystem = $taxSystem;
    $this->items = $items;
  }

  /**
   * @return string Контактный номер телефона или email покупателя.
   */
  public function getCustomerContact(){
    return $this->customerContact;
  }

  /**
   * @return int Система налогообложения магазина.
   */
  public function getTaxSystem(){
    return $this->taxSystem;
  }

  /**
   * @return ItemList Список товаров в заказе.
   */
  public function getItems(){
    return $this->items;
  }

  /**
   * {@inheritdoc}
   */
  public function toJson(){
    return sprintf(
      '{"customerContact":"%s","taxSystem":%s,"items":%s}',
      $this->customerContact,
      $this->taxSystem,
      $this->items->toJson()
    );
  }
}
