<?php

class N98_InfoFiles_Test_Controller_FilesController extends EcomDev_PHPUnit_Test_Case_Controller
{
    private function _prepareAdminLogin()
    {
        $user = Mage::getModel('admin/user');
        $user->loadByUsername("fixtureAddedUser");

        $adminSession = $this->getModelMock('admin/session', array('getUser'));
        $adminSession->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue($user));
        $this->replaceByMock('singleton', 'admin/session', $adminSession);
    }

    /**
     * @test
     * @loadFixture
     */
    public function uploadAction()
    {
        $this->markTestSkipped();
        $this->_prepareAdminLogin();
        $this->dispatch('adminhtml/catalog_product/edit/id/1');
        $this->assertRequestRoute('adminhtml/catalog_product/edit');
        $this->assertLayoutBlockCreated('infofiles');
    }

}
