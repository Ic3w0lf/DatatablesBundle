<?php

namespace Sg\DatatablesBundle\Twig\Json\Cases;

/**
 * Class AbstractCase
 */
abstract class AbstractCase
{
    /**
     * @return array
     */
    public function createReplacementId()
    {
        return uniqid('func');
    }

    /**
     * @param string $key
     * @param mixed  &$value
     *
     * @return bool
     */
    abstract public function isSpecialCase($key, &$value);

    /**
     * @param string $key
     * @param mixed  &$value
     *
     * @return array With funcID as key and function as value
     */
    abstract public function handleCase($key, &$value);
}
