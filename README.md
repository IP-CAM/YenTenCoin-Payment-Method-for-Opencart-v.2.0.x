YenTenCoin Payment Method for OpenCart

Decentralized BitCoin payment method for OpenCart v2.0+ that works with bitcoind via RPC and with YenTen.me wallet.

FEATURES  

✯  Real standalone YenTenCoin module: receive your coins directly without any third-parties  
✯  Clean and crisp module, no file modifications  
✯  Compatible with every theme  
✯  Automatic currency conversion when the YenTen payment was changed  
✯  Minimum Order Total amount settings  
✯  Custom Geo-Zone settings  
✯  QR Code support  
✯  100% OpenSource under the GNU GPL v3 License  

REQUIREMENTS  

1. yentend (this step requires the root access to install it)  
2. 50Gb of free disk space (os + block chain + website data)  
3. cURL module  

INSTALL  

1. Copy all files from the upload directory into the root of your store  
2. Create a new system currency and change its code to YTN (Go through localization->currency). Don't forget to set the exchange rate!
3. Install, configure and activate the YenTenCoin module  

CENTRALIZED USAGE

This module can be used with https://yenten.me/ . No need to setup yentend on another server.  
Simply open a bitcoin wallet at yenten.me and enter the following details into the module setup:  

RPC Host: yenten.me
RPC Port: 443  
RPC Path: paymentGateway/
RPC User: <walletuser>+<supportpin> 
RPC Password: anything - not used
YenTenCoin Conversion Currency: Select Euro, Pound or USD  
QR Code: Google API  
Order Total: 1  
Order Status: Processing  
Geo Zone: All Zones  
Status: Enabled  
Sort Order: 0  

