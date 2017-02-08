<?php

namespace Sg\DatatablesBundle\Twig\Json\Cases;

/**
 * Class TemplateNameCase
 */
class TemplateNameCase extends AbstractCase
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * TemplateNameCase constructor.
     *
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param string $key
     * @param mixed  &$value
     *
     * @return bool
     */
    public function isSpecialCase($key, &$value)
    {
        return (is_string($value) && strpos($value, '@') === 0);
    }

    /**
     * @param string $key
     * @param mixed  &$value
     *
     * @return array
     */
    public function handleCase($key, &$value)
    {
        $funcId     = $this->createReplacementId();
        $replaceKey = '"' . $funcId . '"';

        $function = $this->twig->render($value);

        $value = $funcId;

        return array($replaceKey => $function);
    }
}
