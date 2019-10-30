<?php
      ini_set("memory_limit","-1");
      ini_set('max_execution_time', 300); 
      error_reporting(E_ALL | E_STRICT);
      ini_set('display_errors', 1);
      use Magento\Framework\App\Bootstrap;// add bootstrap
      include("app/bootstrap.php");
      /*$bootstrap = Bootstrap::create(BP, $_SERVER);
      $objectManager = $bootstrap->getObjectManager();
      $objectManager = $bootstrap->getObjectManager();
      $state = $objectManager->get('Magento\Framework\App\State');
      $state->setAreaCode('frontend');*/
      define('DS', DIRECTORY_SEPARATOR); 
/*use \Magento\Framework\App\Bootstrap;
include('../app/bootstrap.php');*/
$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$app_state = $objectManager->get('\Magento\Framework\App\State');
$app_state->setAreaCode('frontend');

$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // instance of object manager
$product = $objectManager->create('\Magento\Catalog\Model\Product');
 

  $product->setData('sku', 'Test Simple Product 6');

  $product->setData('name', 'Test Simple Product 6');

  $product->setData('website_ids', array(1)); // product can be found in main website

  $product->setData('attribute_set_id', 4);

  $product->setData('status', \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);

  $product->setData('visibility', 4);

  $product->setData('price', 12);

  $product->setData('type_id', 'simple');

  $product->setData('stock_data', array(  // set product quantity

   'use_config_manage_stock' => 0,

   'manage_stock' => 1,

   'is_in_stock' => 1,

   'qty' => 100

  ));

  $product->save();

  $productId1 = $product->getId();  // get simple product id so that we can assign it to configurable product by id later
 
     
echo " <br>product id: ".$productId1."\n";
//die();




$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$configurableProduct = $objectManager->create('Magento\Catalog\Model\Product'); // Load Configurable Product

$configurableProduct->setData('sku', 'Configurable Product 2');

$configurableProduct->setData('name', 'Configurable Product 2');

$configurableProduct->setData('attribute_set_id', 4);

$configurableProduct->setData('status', \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);

$configurableProduct->setData('type_id', 'configurable');

$configurableProduct->setData('price', 0);

$configurableProduct->setData('website_ids', array(1));  // set website

$configurableProduct->setData('category_ids', array(2));// set category

$configurableProduct->setData('stock_data', array(

   'use_config_manage_stock' => 0, //'Use config settings' checkbox

   'manage_stock' => 1, //manage stock

   'is_in_stock' => 1, //Stock Availability

  )

);

$attributeModel = $objectManager->create('Magento\ConfigurableProduct\Model\Product\Type\Configurable\Attribute');
$position = 0;
$attributes = array(134, 135); // Super Attribute Ids Used To Create Configurable Product
$associatedProductIds = array($productId1); //Product Ids Of Associated Products
/*echo "<pre>";
print_r($associatedProductIds);
die();*/
foreach ($attributes as $attributeId) {
      $data = array('attribute_id' => $attributeId, 'product_id' => $productId1, 'position' => $position);
      $position++;
      $attributeModel->setData($data)->save();
}
$configurableProduct->setTypeId("configurable"); // Setting Product Type As Configurable
$configurableProduct->setAffectConfigurableProductAttributes(4);
$objectManager->create('Magento\ConfigurableProduct\Model\Product\Type\Configurable')->setUsedProductAttributeIds($attributes, $configurableProduct);
$configurableProduct->setNewVariationsAttributeSetId(4); // Setting Attribute Set Id
$configurableProduct->setAssociatedProductIds($associatedProductIds);// Setting Associated Products
echo "<pre>";
print_r($associatedProductIds);


$configurableProduct->setCanSaveConfigurableAttributes(true);
$configurableProduct->save();
