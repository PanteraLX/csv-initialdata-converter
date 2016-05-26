<?php
/**
 * AbstractConvertCommand.php
 */
namespace AppBundle\Command;

use AppBundle\Strategy\StrategyContext;
use parseCSV;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class AbstractConvertCommand
 * @package AppBundle\Command
 * @author  List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link    http://swisscom.ch
 */
class ConvertCommand extends ContainerAwareCommand
{
    /**
     * @var parseCSV
     */
    private $csv;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var array
     */
    private $strategies = array('yml', 'json');

    /**
     * ConvertCommand constructor.
     */
    public function __construct()
    {
        //TODO: DI
        $this->csv = new parseCSV();
        $this->filesystem = new Filesystem();
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('graviton:csv')
            ->setDescription('Convert CSV to JSON or YAML')
            ->addArgument(
                'csvPath',
                InputArgument::REQUIRED,
                'Path to the CSV file which should be imported'
            )
            ->addArgument(
                'outputPath',
                InputArgument::REQUIRED,
                'Path to the directory where the JSON/YAML files should be dumped'
            )
            ->addOption(
                'add-header',
                'ah',
                InputOption::VALUE_NONE,
                'Add initialdata header'
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion('<question>Please choose strategy:</question> ', $this->strategies);
        $strategy = $helper->ask($input, $output, $question);
        $strategyContext = new StrategyContext($input, $output, new Filesystem(), $helper, $strategy);

        $this->csv->delimiter = ";";
        $this->csv->parse($input->getArgument('csvPath'));
        $rows = $this->csv->data;

        $counter = 0;
        foreach ($rows as $row) {
            $header = $input->getOption('add-header') ? $strategyContext->getHeader($row) : '';
            $data = $strategyContext->resolveReferences($row);

            $filePath = sprintf("%s%s.%s", $input->getArgument('outputPath'), $data['id'], $strategy);
            $dataString = $strategyContext->convert($data);
            $this->filesystem->dumpFile($filePath, $header . $dataString);

            $counter++;
        }
        $output->writeln('<info>' . $counter . ' Files created or updated</info>');
    }
}
