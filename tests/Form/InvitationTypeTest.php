<?php

namespace App\tests\Form\Admin;

use Symfony\Component\Form\Test\TypeTestCase;
use App\Form\InvitationType;

class InvitationTypeTest extends TypeTestCase
{
    public function testBuildForm()
    {
        $mockFormBuilderInterface = $this->getMockBuilder('Symfony\Component\Form\FormBuilderInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $mockFormBuilderInterface->expects($this->any())
            ->method('add')
            ->will($this->returnValue($mockFormBuilderInterface));

        $formType = new InvitationType();
        $response = $formType->buildForm($mockFormBuilderInterface,  array('gc' => $this->createMock('App\Form\InvitationType')));
        $this->assertNull($response);
    }

    public function testConfigureOptions(){
        $formType = new InvitationType();
        $mockResolver = $this->getMockBuilder('Symfony\Component\OptionsResolver\OptionsResolver')
                ->disableOriginalConstructor()
                ->getMock();
        $response = $formType->configureOptions($mockResolver);
        $this->assertNull($response);
    }
}
