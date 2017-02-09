<?php

namespace Sg\DatatablesBundle\Twig\Json\Cases;

/**
 * Class TemplateArrayCase
 */
class TemplateArrayCase extends AbstractCase
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
     * @param mixed  $value
     *
     * @return bool
     */
    public function isSpecialCase($key, &$value)
    {
        return (is_array($value) && isset($value['template']));
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return array
     */
    public function handleCase($key, &$value)
    {
        $funcId     = $this->createReplacementId();
        $replaceKey = '"' . $funcId . '"';

        $templateVars = isset($value['vars']) ? $value['vars'] : array();
        $function     = $this->twig->render($value['template'], $templateVars);

        $value = $funcId;

        return array($replaceKey => $function);
    }
}
