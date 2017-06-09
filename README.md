# btc-realtime-ticker

This is simple web app framework which in realtime shows Exchange Rate for BTC\USD, EUR\USD, BTC\EUR.
Framework alows to add any Exchange Providers and calculate Exchange Rates only for actual data.

#How install
run **php composer install**

#How to run this app

launch webSocket server
run **php ./bin/ws_server.php**

open index.html page
**http://localhost/btc-realtime-ticker/**

run feed collector
**php ./bin/collect-data.php**

#Provider configuration

All configuration for Exchange source feed is in **./conf.php** file

Default **expiration_interval** equal 5 seconds.
It means that Exchange Rate by particular provider is actual and participate in calculations to overall Exchange Rates only 5 seconds since it was received.


