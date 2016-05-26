<?php
/**
 * StrategyInterface.php
 */
namespace AppBundle\Strategy;

/**
 * Interface StrategyInterface
 * @package AppBundle\Strategy
 * @author  Samuel Heinzmann <samuel.heinzman@swisscom.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link    https://github.com/PanteraLX/scrumfony
 */
interface StrategyInterface
{
    /**
     * @param $data
     * @return mixed
     */
    public function convert($data);

    /**
     * @return mixed
     */
    public function resolveReferences($data);

    /**
     * @param $data
     * @return mixed
     */
    public function getHeader($data);
}
