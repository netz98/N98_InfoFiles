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

class N98_InfoFiles_Block_Adminhtml_Media_Uploader extends Mage_Adminhtml_Block_Media_Uploader
{

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('n98/infofiles/uploader.phtml');
        $url = Mage::getModel('adminhtml/url')->addSessionParam()->getUrl('*/files/upload');

//      the next line can be uncommeted to debug the uploading via xdebug (in netbeans)
//      $url.= "&XDEBUG_SESSION_START=netbeans-xdebug";

		// 20170419 fix for SUPEE-8788 and/or Magento 1.9.3
		if (class_exists("Mage_Uploader_Block_Abstract")) {
			$this->getUploaderConfig()->setTarget($url);
		} else {
			// old behaviour
			$this->getConfig()->setUrl($url);
		}
    }
}