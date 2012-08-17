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
class N98_InfoFiles_Model_File extends Mage_Core_Model_Abstract
{

    /**
     * File type icons and their extensions
     */
    protected $fileTypes = array(
        'icon_audio' => array('wav', 'fla', 'mp3'),
        'icon_doc' => array('doc', 'docx', 'odt', 'wri', 'rtf'),
        'icon_film' => array('mpg', 'mpeg', 'avi', 'flv'),
        'icon_fla' => array('fla', 'flash'),
        'icon_html' => array('html', 'htm'),
        'icon_image' => array('png', 'jpg', 'jpeg', 'gif', 'tif', 'tiff'),
        'icon_mov' => array('mov'),
        'icon_pdf' => array('pdf'),
        'icon_photoshop' => array('psd'),
        'icon_ppt' => array('ppt', 'pptx', 'odp'),
        'icon_swf' => array('swf'),
        'icon_txt' => array('txt'),
        'icon_vector' => array('svg', 'svgz', 'cdr', 'ai'),
        'icon_xls' => array('xls', 'ods', 'xlsx'),
        'icon_xml' => array('xml'),
        'icon_zip' => array('zip', 'rar', 'jar', '7z', 'arj', 'cab'),
    );

    /**
     * Create model
     */
    protected function _construct()
    {
        $this->_init('n98infofiles/file');
        $this->setIdFieldName('id');
    }

    /**
     * Get the collection
     *
     * This function exists only to enable code completion.
     *
     * @return N98_InfoFiles_Model_Mysql4_File_Collection
     */
    public function getCollection()
    {
        return parent::getCollection();
    }

    /**
     * Retrieves the download link
     * 
     * @return string
     */
    public function getUrl()
    {
        $filename = $this->getFilename();
        $url = Mage::getSingleton('catalog/product_media_config')
                        ->getMediaUrl($filename);
        return $url;
    }

    /**
     * Retrieves the filename only
     *
     * @return string
     */
    public function getName()
    {
        $filename = $this->getFilename();
        return basename($filename);
    }

    /**
     * Get file size on disk
     *
     * @return int File size in byte
     */
    public function getSize()
    {
        $filename = Mage::getSingleton('catalog/product_media_config')
                        ->getMediaPath($this->getFilename());
        return filesize($filename);
    }

    /**
     * Format file size, for example 1024 = 1 KiB
     *
     * @param int $size Size in bytes
     * @return string
     */
    public static function format_bytes($size)
    {
        $units = array(' B', ' KiB', ' MiB', ' GiB', ' TiB');
        for ($i = 0; $size >= 1024 && $i < 4; $i++)
            $size /= 1024;
        return round($size, 2) . $units[$i];
    }

    /**
     * Get formated file size
     */
    public function getSizeFormated()
    {
        return self::format_bytes($this->getSize());
    }

    /**
     * Get the file icon based on the file extension
     * 
     * @return string
     */
    public function getIcon()
    {
        // get extension
        $filename = $this->getName();
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        // determine icon
        foreach ($this->fileTypes as $icon => $types) {
            if (in_array($extension, $types)) {
                return $icon . '.gif';
            }
        }

        // nothing found => use generic icon
        return 'icon_generic.gif';
    }

}
