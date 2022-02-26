## Steps for deploy project

1. Clone project (link)
2. Write ```make local-deploy``` from root project
3. Check
   endpoint `https://127.0.0.1:8881/api/content/v1/rates/coins/{coin}/currensies/{currency}/start/{start}/end/{end}`
    - coin = {bitcoin}
    - currency = {usd|eur|gbp}
    - start = 1367193600000
    - end = 1370217600000

*`example : https://127.0.0.1:8881/api/content/v1/rates/coins/bitcoin/currensies/usd/start/1367193600000/end/1370217600000`*