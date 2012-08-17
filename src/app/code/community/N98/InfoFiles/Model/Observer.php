<?php

/**
 * netz98 InfoFiles magento module
 *
 * LICENSE
 *
 * Copyright © 2011.
 * netz98 new media GmbH. Alle Rechte vorbehalten.
 *
 * Die Nutzung und Weiterverbreitung dieser Software in kompilierter oder nichtkompilierter Form, mit oder ohne Veränderung, ist unter den folgenden Bedingungen zulässig:
 *
 * 1. Weiterverbreitete kompilierte oder nichtkompilierte Exemplare müssen das obere Copyright, die Liste der Bedingungen und den folgenden Verzicht im Sourcecode enthalten.
 * 2. Alle Werbematerialien, die sich auf die Eigenschaften oder die Benutzung der Software beziehen, müssen die folgende Bemerkung enthalten: "Dieses Produkt enthält Software, die von der netz98 new media GmbH entwickelt wurde."
 * 3. Der Name der netz98 new media GmbH darf nicht ohne vorherige ausdrückliche, schriftliche Genehmigung zur Kennzeichnung oder Bewerbung von Produkten, die von dieser Software abgeleitet wurden, verwendet werden.
 * 4. Es ist Lizenznehmern der netz98 new media GmbH nur dann erlaubt die veränderte Software zu verbreiten, wenn jene zu den Bedingungen einer Lizenz, die eine Copyleft-Klausel enthält, lizenziert wird.
 *
 * Diese Software wird von der netz98 new media GmbH ohne jegliche spezielle oder implizierte Garantien zur Verfügung gestellt. So übernimmt die netz98 new media GmbH keine Gewährleistung für die Verwendbarkeit der Software für einen speziellen Zweck oder die generelle Nutzbarkeit. Unter keinen Umständen ist netz98 haftbar für indirekte oder direkte Schäden, die aus der Verwendung der Software resultieren. Jegliche Schadensersatzansprüche sind ausgeschlossen.
 *
 *
 * Copyright © 2011
 * netz98 new media GmbH. All rights reserved.
 *
 * The use and redistribution of this software, either compiled or uncompiled, with or without modifications are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of compiled or uncompiled source must contain the above copyright notice, this list of the conditions and the following disclaimer:
 * 2. All advertising materials mentioning features or use of this software must display the following acknowledgement: “This product includes software developed by the netz98 new media GmbH, Mainz.”
 * 3. The name of the netz98 new media GmbH may not be used to endorse or promote products derived from this software without specific prior written permission.
 * 4. License holders of the netz98 new media GmbH are only permitted to redistribute altered software, if this is licensed under conditions that contain a copyleft-clause.
 * This software is provided by the netz98 new media GmbH without any express or implied warranties. netz98 is under no condition liable for the functional capability of this software for a certain purpose or the general usability. netz98 is under no condition liable for any direct or indirect damages resulting from the use of the software. Liability and Claims for damages of any kind are excluded.
 *
 * @copyright Copyright (c) 2011 netz98 new media GmbH (http://www.netz98.de)
 * @author netz98 new media GmbH <info@netz98.de>
 * @category N98
 * @package N98_InfoFiles
 */
class N98_InfoFiles_Model_Observer
{

    /**
     * Flag to stop observer executing more than once
     *
     * @var static bool
     */
    static protected $_singletonFlag = false;

    /**
     * This method will run when the product is saved from the Magento Admin
     * Use this function to update the product model, process the
     * data or anything you like
     *
     * @param Varien_Event_Observer $observer
     */
    public function saveProductTabData(Varien_Event_Observer $observer)
    {
        if (self::$_singletonFlag) {
            return;
        }
        self::$_singletonFlag = true;

        $product = $observer->getEvent()->getProduct();
        $storeId = $product->getStoreId();
        try {

            $files = $this->_getRequest()->getPost('infofile_file');
            $names = $this->_getRequest()->getPost('infofile_name');
            $labels = $this->_getRequest()->getPost('infofile_label');

            for ($i = 1; $i < count($files); $i++) { // Skip $i=0, because it contains the template!
                $fileName = $files[$i];
                $originalName = $names[$i];

                // move the file from the tmp media folder to the real folder
                $currentFile = Mage::getSingleton('catalog/product_media_config')->getTmpMediaPath($fileName);
                $dispretionPath = Varien_File_Uploader::getDispretionPath($originalName);
                $destinationFolder = Mage::getSingleton('catalog/product_media_config')->getMediaPath($dispretionPath);
                $destFile = Mage::getSingleton('catalog/product_media_config')->getMediaPath($dispretionPath . DS . $originalName);
                if (!(@is_dir($destinationFolder) || @mkdir($destinationFolder, 0777, true))) {
                    throw new Exception("Unable to create directory '{$destinationFolder}'.");
                }
                // adds a counter to the filename
                $destFilename = $dispretionPath . DS . Varien_File_Uploader::getNewFileName($destFile);
                $destFile = Mage::getSingleton('catalog/product_media_config')
                                ->getMediaPath($destFilename);
                rename($currentFile, $destFile);

                // add entry in the database
                $model = Mage::getModel('n98infofiles/file');
                $model->setFilename($destFilename);

                $label = trim($labels[$i]);
                if (empty($label)) {
                    $label = $originalName;
                }
                $model->setLabel($label);
                $model->setProductId($product->getId());
                $model->setStoreId($storeId);
                $model->save();
            }

            // process existing files
            $exLabels = $this->_getRequest()->getPost('infofile_existing_label');
            $exRemove = $this->_getRequest()->getPost('infofile_existing_remove');
            if (count($exRemove) > 0) {
                foreach ($exLabels as $id => $label) {
                    $fileModel = Mage::getModel('n98infofiles/file');
                    $fileModel->load($id);

                    if (isset($exRemove[$id])) {
                        // delete file from disk
                        $filename = Mage::getSingleton('catalog/product_media_config')->getMediaPath($fileModel->getFilename());
                        unlink($filename);
                        // delete record
                        $fileModel->delete();
                        continue;
                    }

                    $fileModel->setLabel($label);
                    $fileModel->save();
                }
            }
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
    }

    /**
     * Retrieve the product model
     *
     * @return Mage_Catalog_Model_Product $product
     */
    public function getProduct()
    {
        return Mage::registry('product');
    }

    /**
     * Shortcut to getRequest
     *
     */
    protected function _getRequest()
    {
        return Mage::app()->getRequest();
    }

}