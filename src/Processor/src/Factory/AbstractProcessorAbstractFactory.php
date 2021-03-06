<?php

namespace rollun\datahandler\Processor\Factory;

use Interop\Container\ContainerInterface;
use rollun\datahandler\Factory\PluginAbstractFactoryAbstract;
use Zend\Validator\ValidatorInterface;
use Zend\Validator\ValidatorPluginManager;

/**
 * Class AbstractProcessorAbstractFactory
 * @package rollun\datahandler\Processor\Factory
 */
abstract class AbstractProcessorAbstractFactory extends PluginAbstractFactoryAbstract
{
    /**
     *  Config key for all processors config
     */
    const KEY = 'processors';

    /**
     *  Validator service that implement ValidatorInterface::class
     */
    const VALIDATOR_KEY = 'validator';

    /**
     * Options for validator
     */
    const VALIDATOR_OPTION_KEY = 'validatorOptions';

    /**
     * Create validator
     *
     * @param ContainerInterface $container
     * @param array $processorOptions
     * @return ValidatorInterface|null
     */
    protected function createValidator(ContainerInterface $container, array $processorOptions)
    {
        $validator = null;

        if (!isset($processorOptions[self::VALIDATOR_KEY])) {
            return $validator;
        }

        $validatorRequestedName = $processorOptions[self::VALIDATOR_KEY];
        $validatorPluginManager = $container->get(ValidatorPluginManager::class);
        $validatorOptions = $processorOptions[self::VALIDATOR_OPTION_KEY] ?? null;

        if (($validatorPluginManager instanceof ValidatorPluginManager)
            && $validatorPluginManager->has($validatorRequestedName)) {

            $validator = $validatorPluginManager->get($validatorRequestedName, $validatorOptions);
        } elseif ($container->has($validatorRequestedName)) {
            $validator = $container->get($validatorRequestedName);
        }

        return clone $validator;
    }

    /**
     * @param array $pluginOptions
     * @return array
     */
    protected function clearProcessorOptions(array $pluginOptions)
    {
        unset($pluginOptions[self::VALIDATOR_KEY]);
        unset($pluginOptions[self::VALIDATOR_OPTION_KEY]);

        return $pluginOptions;
    }
}
