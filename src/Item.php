<?php
namespace Bricks\Business\YmMerchantReceipt;

use Bricks\Business\YmMerchantReceipt\Exception\InvalidArgumentException;

/**
 * Продукт.
 *
 * @author Artur Sh. Mamedbekov
 */
class Item implements JsonSerializableInterface
{
    /**
     * Типы Оплаты "Предоплата 100%. Полная предварительная оплата до момента
     * передачипредмета расчета"
     */
    const PAYMENT_FULL_PREPAYMENT = 'full_prepayment';

    /**
     * Типы Оплаты "Предоплата. Частичная
     * предварительная оплата до момента передачипредмета расчета"
     */
    const PAYMENT_PREPAYMENT = 'prepayment';

    /**
     * Типы Оплаты "Аванс"
     */
    const PAYMENT_ADVANCE = 'advance';

    /**
     * Типы Оплаты "Полный расчет. Полная оплата, в том числе с учетом аванса
     * (предварительной оплаты) в момент передачи предмета расчета"
     */
    const PAYMENT_FULL_PAYMENT = 'full_payment';

    /**
     * Типы Оплаты "Частичный расчет и кредит. Частичная оплата предмета расчета в момент
     * его передачи с последующей оплатой в кредит"
     */
    const PAYMENT_PARTIAL_PAYMENT = 'partial_payment';

    /**
     * Типы Оплаты "Передача в кредит. Передача предмета расчета без его оплаты в момент его
     * передачи с последующей оплатой в кредит."
     */
    const PAYMENT_CREDIT = 'credit';

    /**
     * Типы Оплаты "Оплата кредита. Оплата предмета расчета после его передачи с оплатой
     * в кредит (оплата кредита)"
     */
    const PAYMENT_CREDIT_PAYMENT = 'credit_payment';

    /** товар */
    const PAYMENT_BY_COMMODITY = 'commodity';

    /** подакцизный товар */
    const PAYMENT_BY_EXCISE = 'excise';

    /** работа */
    const PAYMENT_BY_JOB = 'job';

    /** услуга */
    const PAYMENT_BY_SERVICE = 'service';

    /** ставка в азартной игре  */
    const PAYMENT_BY_GAMBLING_BET = 'gambling_bet';

    /** выигрыш в азартной игре */
    const PAYMENT_BY_GAMBLING_PRIZE = 'gambling_prize';

    /** лотерейный билет */
    const PAYMENT_BY_LOTTERY = 'lottery';

    /** выигрыш в лотерею */
    const PAYMENT_BY_LOTTERY_PRIZE = 'lottery_prize';

    /** результаты интеллектуальной деятельности */
    const PAYMENT_BY_INTELLECTUAL_ACTIVITY = 'intellectual_activity';

    /** платеж */
    const PAYMENT_BY_PAYMENT = 'payment';

    /** агентское вознаграждение */
    const PAYMENT_BY_AGENT_COMMISSION = 'agent_commission';

    /** несколько вариантов */
    const PAYMENT_BY_COMPOSITE = 'composite';

    /** другое */
    const PAYMENT_BY_ANOTHER = 'another';

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
    $textLen = mb_strlen($text);
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
     * @return string
     */
    public function toJson(): string
    {
        return sprintf(
            '{"quantity":%01.3f,"price":%s,"tax":%s,"text":"%s","paymentMethodType":"%s","paymentSubjectType":"%s"}',
            $this->quantity,
            $this->price->toJson(),
            $this->tax,
            addcslashes(addcslashes($this->text, '/'), '"'),
            self::PAYMENT_FULL_PREPAYMENT,
            self::PAYMENT_BY_ANOTHER
        );
    }
}
