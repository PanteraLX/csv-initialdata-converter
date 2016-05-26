<?php
/**
 * ConvertParamCommand.php
 */
namespace AppBundle\Utils;

use Symfony\Component\Console\Question\Question;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ConvertCommand
 * @package AppBundle\Command
 * @author  List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link    http://swisscom.ch
 */
class ParamDataUtils extends AbstractUtils
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var string
     */
    private $collection;

    /**
     * @param $data
     * @return string
     */
    public function convert($data)
    {
        return Yaml::dump($data);
    }

    /**
     * @param $data
     * @return string
     */
    public function getHeader($data)
    {
        $question = new Question('<question>Please type collection api:</question> ', false);
        $this->collection = empty($this->collection)
            ? $this->questionHelper->ask($this->input, $this->output, $question)
            : $this->collection;

        $this->baseUrl = $this->input->getOption('base-url') ?: 'http://localhost/';

        return sprintf("---\ntarget: %s%s%s\n---\n", $this->baseUrl, $this->collection, $data['id']);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function resolveReferences($data)
    {
        foreach ($data as $fieldName => $fieldEntry) {
            if (false !== strpos($fieldName, $this->needle)) {

                $api = $this->getReferencedCollectionApi($fieldName);

                $referencesStringArray = explode(PHP_EOL, $fieldEntry);
                $references = [];

                foreach($referencesStringArray as $referenceString) {
                    array_push($references , ['$ref' => $this->baseUrl . $api . $referenceString]);
                }

                unset($data[$fieldName]);
                $data[str_replace($this->needle, "", $fieldName)] = $references;
            }

            if ("" === $fieldEntry && "integer" === gettype($fieldName)) {
                unset($data[$fieldName]);
            }
        }
        return $data;
    }

    /**
     * @param $fieldName
     * @return mixed
     */
    private function getReferencedCollectionApi($fieldName)
    {
        $question = new Question('Please type collection api for the reference (' . $fieldName .'),  e.g. financing/cashflowtype/: ', false);
        if (!isset($this->collectionNames[$fieldName])) {
            $this->collectionNames[$fieldName] = $this->questionHelper->ask($this->input, $this->output, $question);
        }
        return $this->collectionNames[$fieldName];
    }
}
