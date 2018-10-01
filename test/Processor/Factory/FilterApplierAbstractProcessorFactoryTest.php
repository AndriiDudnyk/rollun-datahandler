<?php

namespace rollun\test\datahandler\Processor\Factory;

use rollun\datahandler\Processor\Factory\FilterApplierAbstractProcessorFactory;
use rollun\datahandler\Processor\FilterApplier;
use Zend\Filter\FilterPluginManager;
use Zend\Validator\Digits;

/**
 * Class FilterApplierAbstractFactoryTest
 * @package rollun\test\datahandler\Processor\Factory
 */
class FilterApplierAbstractProcessorFactoryTest extends AbstractProcessorAbstractFactoryTest
{
    protected function setUp()
    {
        $this->object = new FilterApplierAbstractProcessorFactory();
    }

    /**
     * @param $requestedName
     * @param $serviceConfig
     * @return \Zend\ServiceManager\ServiceManager
     */
    public function getContainer($requestedName, $serviceConfig = [])
    {
        $container = parent::getContainer($requestedName, $serviceConfig);
        $container->setService(FilterPluginManager::class, new FilterPluginManager($container));
        return $container;
    }

    public function testMainFunctionality()
    {
        $processorClassName = FilterApplier::class;
        $validatorClassName = Digits::class;

        $this->assertInvokeWithConfig($processorClassName, $validatorClassName);
        $this->assertInvokeWithOptions($processorClassName, $validatorClassName);
        $this->assertPositiveGetClass($processorClassName);
    }

    public function testPositiveGetClass()
    {
        $class = FilterApplier::class;
        $serviceConfig = [
            'class' => FilterApplier::class
        ];

        $this->assertEquals($this->object->getClass($serviceConfig), $class);
    }

    public function testValidOption()
    {
        $processorClassName = FilterApplier::class;
        $filters = [
            'filter1' => [
                'someFilterOptions'
            ]
        ];
        $argumentColumn = 1;
        $resultColumn = 2;
        $validatorClassName = Digits::class;

        /** @var FilterApplier $processor */
        $processor = $this->invoke([
            'class' => $processorClassName,
            'options' => [
                'filters' => $filters,
                'argumentColumn' => $argumentColumn,
                'resultColumn' => $resultColumn,
                'validator' => $validatorClassName,
            ]
        ]);

        $this->assertEquals($processor->getResultColumn(), $resultColumn);
        $this->assertEquals($processor->getArgumentColumn(), $argumentColumn);
        $this->assertEquals($processor->getFilters(), $filters);
        $this->assertTrue(is_a($processor->getValidator(), $validatorClassName, true));
    }
}
