<?php
/**
 * AppExtension.php
 */
namespace AppBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\Extension;

/**
 * Class AppExtension
 * @package AppBundle\DependencyInjection
 * @author  List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link    http://swisscom.ch
 */
class AppExtension extends Extension
{
    /**
     * All services and parameters (e.g. routing) related to this extension will be loaded
     *
     * @param array            $configs   Configurations
     * @param ContainerBuilder $container Container Builder
     *
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . "/../Resources/config/"));
        $loader->load("services.xml");
    }
}