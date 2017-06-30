<?php
namespace Bricks\Business\YmMerchantReceipt;

use Bricks\Business\YmMerchantReceipt\Exception\InvalidArgumentException;

/**
 * Стоимость.
 *
 * @author Artur Sh. Mamedbekov
 */
class Price implements JsonSerializableInterface{
  /**
   * @var float Цена за единицу.
   */
  private $amount;

  /**
   * @var string Трехсимвольный код валюты.
   */
  private $currency;

  /**
   * @param float $amount Цена за единицу.
   * @param string $currency [optional] Трехсимвольный код валюты.
   */
  public function __construct($amount, $currency = 'RUB'){
    if(!is_string($amount) && !is_int($amount) && !is_float($amount)){
      throw InvalidArgumentException::fromParam('amount', 'float|int|string', $amount);
    }
    $this->amount = (float) $amount;
    if(!is_string($currency)){
      throw InvalidArgumentException::fromParam('currency', 'string', $currency);
    }
    if(strlen($currency) != 3){
      throw new InvalidArgumentException('The currency length must be 3 characters');
    }
    $this->currency = $currency;
  }

  /**
   * @return float Цена за единицу.
   */
  public function getAmount(){
    return $this->amount;
  }

  /**
   * @return string Трехсимвольный код валюты.
   */
  public function getCurrency(){
    return $this->currency;
  }

  /**
   * {@inheritdoc}
   */
  public function toJson(){
    return sprintf(
      '{"amount":%01.2f,"currency":"%s"}',
      $this->amount,
      $this->currency
    );
  }
}
