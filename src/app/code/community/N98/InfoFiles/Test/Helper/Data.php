<?php

class N98_InfoFiles_Test_Helper_Data extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @test
     * @loadExpectation
     * @loadFixture
     */
    public function getTest()
    {
        $this->setCurrentStore('de');

        $product = Mage::getModel('catalog/product')->load($this->expected('1-1')->getEntityId());
        $this->assertEquals($this->expected('1-1')->getSku(), $product->getSku());

        $model = Mage::getModel('n98infofiles/file');
        /** @var $model N98_InfoFiles_Model_File */
        $collection = $model->getCollection();
        $collection->addProductFilter($product);
        $collection->addStoreFilter($product->getStoreId());
        $collection->load();

        $this->assertCount(1, $collection);
    }
}
