# Необходимо разработать веб приложение, которое будет предоставлять API для получения курса криптовалюты Bitcoin: BTC/USD, BTC/EUR и т.п.

API должен позволять получать данные биржевого курса валюты по часам и иметь возможность изменить диапазон вывода.
Предполагайте, что целью использования API является построение графика курса валюты.

Приложение должно обладать функционалом периодического обновления курсов валют с реальной биржи. Формат хранения и
источник данных можно выбрать самим. Количество предоставляемых через API пар валют - не меньше 3-х.

Результат должен быть выложен на любой сервис в виде git репозитория, и должен иметь инструкцию по сборке и работе.

### Стэк технологий:

- Symfony 4.x или 5.х
- PHP 7.x
- База данных может быть выбрана любая, в том числе и nosql.

## Notes

Не понял для чего хранить rate данные в базе, я думаю что для такой таски подошёл бы proxy с кэшированием. Но раз надо
то сделал.