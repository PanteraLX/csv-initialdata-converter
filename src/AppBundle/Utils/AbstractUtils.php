<?php
/**
 * AbstractUtils.php
 */
namespace AppBundle\Utils;

use AppBundle\Strategy\StrategyInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class AbstractUtils
 * @package AppBundle\Utils
 * @author  List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link    http://swisscom.ch
 */
abstract class AbstractUtils implements StrategyInterface
{
    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var
     */
    protected $questionHelper;

    /**
     * @var array
     */
    protected $collectionNames = [];

    /**
     * @var string
     */
    protected $needle = ".\$ref";

    /**
     * AbstractUtils constructor.
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param Filesystem      $filesystem
     * @param                 $questionHelper
     */
    public function __construct(
        InputInterface $input,
        OutputInterface $output,
        Filesystem $filesystem,
        QuestionHelper $questionHelper
    ) {
        $this->output = $output;
        $this->input = $input;
        $this->filesystem = $filesystem;
        $this->questionHelper = $questionHelper;
    }
}