<?php
/**
 * StrategyContext.php
 */
namespace AppBundle\Strategy;

use AppBundle\Utils\CoreDataUtils;
use AppBundle\Utils\ParamDataUtils;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class StrategyContext
 * @author  List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link    http://swisscom.ch
 */
class StrategyContext implements StrategyInterface
{
    /**
     * @var StrategyInterface
     */
    private $strategy = NULL;

    /**
     * StrategyContext constructor.
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param Filesystem      $filesystem
     * @param QuestionHelper  $questionHelper
     * @param string          $strategyId
     */
    public function __construct(
        InputInterface $input,
        OutputInterface $output,
        Filesystem $filesystem,
        QuestionHelper $questionHelper,
        $strategyId
    ) {
        switch ($strategyId) {
            case "yml":
                $this->strategy = new ParamDataUtils($input, $output, $filesystem, $questionHelper);
                break;
            case "json":
                $this->strategy = new CoreDataUtils($input, $output, $filesystem, $questionHelper);
                break;
        }
    }

    /**
     * @param $data
     * @return mixed
     */
    public function convert($data)
    {
        return $this->strategy->convert($data);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function resolveReferences($data)
    {
        return $this->strategy->resolveReferences($data);
    }

    /**
     * @param $data
     * @return string
     */
    public function getHeader($data)
    {
        return $this->strategy->getHeader($data);
    }
}
