# currency-converter

Api available on http://127.0.0.1:8000/api/exchange/6000/ZAR-GBP

``/api/exchange/AMOUNT/FROM-TO``

Set up bank in services.yaml

```
bank.current_bank:
          class: App\Service\ECBBank
          public: true
```

available classes:
```
 App\Service\ECBBank
 App\Service\CBRBank
```

Console commands:
```
./bin/console currency:fetch ECB - fetch ECB Bank data
./bin/console currency:fetch CBR - fetch CBR Bank data
./bin/console currency:fetch - fetch both banks data
```

Set up 

Run tests

``.\vendor\bin\simple-phpunit.bat`` 

on Windows 

``./vendor/bin/simple-phpunit.bat``

on Linux 


