# Квинтанкция продавца

Класс _MerchantReceipt_ используется для представления всех параметров, необходимых для формирования чека с использованием [протокола Яндекс.Деньги][]. Он реализует интерфейс _JsonSerializableInterface_, что позволяет вызывать метод _toJson_ для формирования JSON-строки из его экземпляра.

    **Важно:**: стандартный интерфейс библиотеки PHP _JsonSerializable_ не применяется по причине отсутствия возможности генерировать JSON с указанным числом разрядов после запятой для дробных чисел.

Пример:

```php
$itemList = new ItemList;
$itemList->add(new Item('Зеленый чай "Юн Ву", кг', 1.154, new Price(300.23), Item::TAX_10));
$itemList->add(new Item('Кружка для чая, шт., скидка 10%', 2, new Price(200), Item::TAX_10));
$merchantReceipt = new MerchantReceipt('+79001231212', MerchantReceipt::TAX_SYSTEM_GENERAL, $itemList);

echo sprintf(
    "<input type='hidden' name='ym_merchant_receipt', value=''>",
    $merchantReceipt->toJson()
);
echo sprintf(
    "<input type='hidden' name='sum', value=''>",
    $merchantReceipt->getItems()->getSum()
);
```

[протокола Яндекс.Деньги]: https://tech.yandex.ru/money/doc/payment-solution/payment-form/payment-form-receipt-docpage/
