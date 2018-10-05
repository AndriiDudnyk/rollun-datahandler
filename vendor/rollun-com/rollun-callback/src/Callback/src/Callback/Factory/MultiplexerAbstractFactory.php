<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 18.02.17
 * Time: 2:20 PM
 */

namespace rollun\callback\Callback\Factory;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Psr\Log\LoggerInterface;
use rollun\callback\Callback\Callback;
use rollun\callback\Callback\CallbackInterface;
use rollun\callback\Callback\Interruptor\InterruptorInterface;
use rollun\callback\Callback\Multiplexer;
use rollun\callback\Callback\Interruptor\Process;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class MultiplexerAbstractFactory extends CallbackAbstractFactoryAbstract
{
    const KEY_CALLBACKS_SERVICES = 'interrupters';

    const DEFAULT_CLASS = Multiplexer::class;

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return CallbackInterface|InterruptorInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        $config = $container->get('config');
        $logger = $container->get(LoggerInterface::class);
        $factoryConfig = $config[static::KEY][$requestedName];

        $callbacks = [];
        if (isset($factoryConfig[static::KEY_CALLBACKS_SERVICES])) {
            $callbackService = $factoryConfig[static::KEY_CALLBACKS_SERVICES];
            foreach ($callbackService as $name => $callback) {
                if (is_callable($callback)) {
                    $callbacks[$name] = $callback instanceof Callback ? $callback : new Callback($callback);
                } else if ($container->has($callback)) {
                    $callbacks[$name] = ($container->get($callback));
                } else {
                    $logger->alert("Callback with name $callback not found in container.");
                }
            }
        }

        $class = $factoryConfig[static::KEY_CLASS];
        $multiplexer = new $class($callbacks);

        return $multiplexer;
    }
}