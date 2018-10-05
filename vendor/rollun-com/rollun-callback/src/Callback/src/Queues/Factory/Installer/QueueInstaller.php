<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.04.17
 * Time: 16:15
 */

namespace rollun\callback\Queues\Factory\Installer;

use rollun\callback\Callback\Factory\CallbackAbstractFactoryAbstract;
use rollun\callback\Callback\Factory\MultiplexerAbstractFactory;
use rollun\callback\Callback\Multiplexer;
use rollun\callback\Queues\Factory\QueueAbstractFactory;
use rollun\installer\Install\InstallerAbstract;

class QueueInstaller extends InstallerAbstract
{

    /**
     * install
     * @return array
     */
    public function install()
    {
        $config = [
            'dependencies' => [
                'abstract_factories' => [
                    QueueAbstractFactory::class,
                ],
            ],
        ];

        return $config;
    }

    public function getName() {

        return static::class;
    }

    public function isInstall()
    {
        $config = $this->container->get('config');
        $result = (
            isset($config['dependencies']['abstract_factories']) &&
            in_array(QueueAbstractFactory::class, $config['dependencies']['abstract_factories'])
        );
        return $result;
    }

    /**
     * Clean all installation
     * @return void
     */
    public function uninstall()
    {
        // TODO: Implement uninstall() method.
    }

    /**
     * Return string with description of installable functional.
     * @param string $lang ; set select language for description getted.
     * @return string
     */
    public function getDescription($lang = "en")
    {
        switch ($lang) {
            case "ru":
                $description = "Предоставляет возможность использовать очедерь для задачь.";
                break;
            default:
                $description = "Does not exist.";
        }
        return $description;
    }

    public function isDefaultOn()
    {
        return true;
    }

    public function getDependencyInstallers()
    {
        return [

        ];
    }
}
