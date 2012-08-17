<?php

class N98_InfoFiles_Test_Config_Install extends EcomDev_PHPUnit_Test_Case_Config
{
    /**
     * @test
     */
    public function moduleConfig()
    {
        $this->assertModuleCodePool('community');
        $this->assertModuleVersion('0.1.0');
    }
}
