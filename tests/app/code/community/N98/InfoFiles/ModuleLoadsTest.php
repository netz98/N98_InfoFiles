<?php
/**
 * netz98 InfoFiles magento module
 *
 * LICENSE
 *
 * This source file is subject of netz98.
 * You may be not allowed to change the sources
 * without authorization of netz98 new media GmbH.
 *
 * @copyright Copyright (c) 2011 netz98 new media GmbH (http://www.netz98.de)
 * @author netz98 new media GmbH <info@netz98.de>
 * @category N98
 * @package N98_InfoFiles
 */

class N98_InfoFiles_ModuleLoadsTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tests if the block is correctly loaded
     */
    public function testLoadBlocks()
    {
        $block = Mage::app()->getLayout()->createBlock('n98infofiles/moreInfo');
        $this->assertInstanceOf('N98_InfoFiles_Block_MoreInfo', $block);

        $block = Mage::app()->getLayout()->createBlock('n98infofiles/adminhtml_media_uploader');
        $this->assertInstanceOf('N98_InfoFiles_Block_Adminhtml_Media_Uploader', $block);
    }

    /**
     * Tests if the helper is correctly loaded
     */
    public function testLoadHelper() {
        $helper = Mage::helper('adminhtml/media_js');
        $this->assertInstanceOf('N98_InfoFiles_Helper_Media_Js', $helper);
    }
}
