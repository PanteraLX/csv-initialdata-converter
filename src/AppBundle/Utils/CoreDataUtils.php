<?php
/**
 * ConvertCoreCommand.php
 */
namespace AppBundle\Utils;

use stdClass;
use Symfony\Component\Console\Question\Question;

/**
 * Class ConvertCommand
 * @package AppBundle\Command
 * @author  List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link    http://swisscom.ch
 */
class CoreDataUtils extends AbstractUtils
{
    /**
     * @var string
     */
    private $header;

    /**
     * @param $data
     * @return string
     */
    public function convert($data)
    {
        return json_encode($data, JSON_PRETTY_PRINT);
    }

    /**
     * @param $data
     * @return string
     */
    public function getHeader($data)
    {
        $question = new Question('<question>Please type collection name:</question> ', false);

        return empty($this->header)
            ? $this->header = "---\ncollection: " . $this->questionHelper->ask($this->input, $this->output, $question) . "\n---\n"
            : $this->header;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function resolveReferences($data)
    {
        foreach ($data as $fieldName => $fieldEntry) {
            if ("" === $fieldEntry && "integer" === gettype($fieldName)) {
                unset($data[$fieldName]);
            }

            if (false !== strpos($fieldName, $this->needle)) {
                $reference = new stdClass();
                $reference->ref = new stdClass();

                $reference->ref->{'$ref'} = $this->getReferencedCollectionName($fieldName);
                $reference->ref->{'$id'} = $fieldEntry;

                unset($data[$fieldName]);
                $data[str_replace($this->needle, "", $fieldName)] = $reference;
            }
        }

        return $data;
    }

    /**
     * @param $fieldName
     * @return mixed
     */
    private function getReferencedCollectionName($fieldName)
    {
        $question = new Question('Please type collection name for the reference (' . $fieldName . '): ', false);

        if (!isset($this->collectionNames[$fieldName])) {
            $this->collectionNames[$fieldName] = $this->questionHelper->ask($this->input, $this->output, $question);
        }
        return $this->collectionNames[$fieldName];
    }
}
