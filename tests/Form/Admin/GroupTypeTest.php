<?php

namespace App\tests\Form\Admin;

use Symfony\Component\Form\Test\TypeTestCase;
use App\Form\Admin\GroupType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupTypeTest extends TypeTestCase
{
    public function testBuildForm()
    {
        $mockFormBuilderInterface = $this->getMockBuilder(FormBuilderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockFormBuilderInterface->expects($this->any())
            ->method('add')
            ->will($this->returnValue($mockFormBuilderInterface));

        $formType = new GroupType();
        $response = $formType->buildForm($mockFormBuilderInterface,  array('gc' => $this->createMock(GroupType::class)));
        $this->assertNull($response);
    }

    public function testConfigureOptions(){
        $formType = new GroupType();
        $mockResolver = $this->getMockBuilder(OptionsResolver::class)
                ->disableOriginalConstructor()
                ->getMock();
        $response = $formType->configureOptions($mockResolver);
        $this->assertNull($response);
    }
}
