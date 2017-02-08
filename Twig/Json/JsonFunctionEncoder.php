<?php

namespace Sg\DatatablesBundle\Twig\Json;

use Sg\DatatablesBundle\Twig\Json\Cases\AbstractCase;

/**
 * Class JsonFunctionEncoder
 */
class JsonFunctionEncoder
{
    /**
     * @var array
     */
    private $funcArray;

    /**
     * @var AbstractCase[]
     */
    protected $caseCollection;

    /**
     * JsonFunctionEncoder constructor.
     *
     * @param AbstractCase[] $caseCollection
     */
    public function __construct(array $caseCollection = array())
    {
        $this->caseCollection = $caseCollection;
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public function getJsJsonString($params)
    {
        $this->funcArray = array();
        $this->renderTemplateFunctions($params);

        $jsonString = str_replace(
            array_keys($this->funcArray),
            $this->funcArray,
            json_encode($params, JSON_PRETTY_PRINT)
        );

        return $jsonString;
    }

    /**
     * @param array &$params
     *
     * @throws \Exception
     */
    private function renderTemplateFunctions(&$params)
    {
        foreach ($params as $key => &$value) {
            //iterate through cases
            foreach ($this->caseCollection as $case) {
                if ($case->isSpecialCase($key, $value)) {
                    $this->funcArray = array_merge($this->funcArray, $case->handleCase($key, $value));
                }
            }

            //recursive function
            if (is_array($value)) {
                $this->renderTemplateFunctions($value);
            }
        }
    }
}
