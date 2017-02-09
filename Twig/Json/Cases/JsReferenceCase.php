<?php

namespace Sg\DatatablesBundle\Twig\Json\Cases;

/**
 * Class JsReferenceCase
 */
class JsReferenceCase extends AbstractCase
{
    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return bool
     */
    public function isSpecialCase($key, &$value)
    {
        return (is_string($value) && strpos($value, '$$') === 0);
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return array With funcID as key and variable as value
     */
    public function handleCase($key, &$value)
    {
        $funcId     = $this->createReplacementId();
        $replaceKey = '"' . $funcId . '"';

        $jsReference = str_replace('$$', '', $value);

        $value = $funcId;

        return array($replaceKey => $jsReference);
    }
}
