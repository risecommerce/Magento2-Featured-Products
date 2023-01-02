##Featured Products Extension

This extension lets store owners display featured products on their website effectively using blocks, templates, layouts, and widgets thereby boosting the sales.

##Support: 
version - 2.3.x, 2.4.x

##How to install Extension

1. Download the archive file.
2. Unzip the file
3. Create a folder [Magento_Root]/app/code/Risecommerce/FeaturedProducts
4. Drop/move the unzipped files to directory '[Magento_Root]/app/code/Risecommerce/FeaturedProducts'

#Enable Extension:
- php bin/magento module:enable Risecommerce_FeaturedProducts
- php bin/magento setup:upgrade
- php bin/magento setup:di:compile
- php bin/magento setup:static-content:deploy
- php bin/magento cache:flush

#Disable Extension:
- php bin/magento module:disable Risecommerce_FeaturedProducts
- php bin/magento setup:upgrade
- php bin/magento setup:di:compile
- php bin/magento setup:static-content:deploy
- php bin/magento cache:flush
