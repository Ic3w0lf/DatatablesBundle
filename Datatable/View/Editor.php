<?php

namespace Sg\DatatablesBundle\Datatable\View;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Editor
 */
class Editor extends AbstractViewOptions
{
    /**
     * @var string
     */
    protected $ajaxUrl;

    /**
     * @var string
     */
    protected $ajaxType;

    /**
     * @var string
     */
    protected $ajaxData;

    /**
     * @var string
     */
    protected $display;

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
           'ajax_url'  => '',
           'ajax_type' => 'POST',
           'ajax_data' => null,
           'display'   => 'bootstrap',
        ));

        $resolver->setAllowedTypes('ajax_url', 'string');
        $resolver->setAllowedTypes('ajax_type', 'string');
        $resolver->setAllowedTypes('ajax_data', array('null', 'string'));
        $resolver->setAllowedTypes('display', 'string');

        return $this;
    }

    /**
     * @return string
     */
    public function getAjaxUrl()
    {
        return $this->ajaxUrl;
    }

    /**
     * @param string $ajaxUrl
     */
    public function setAjaxUrl($ajaxUrl)
    {
        $this->ajaxUrl = $ajaxUrl;
    }

    /**
     * @return string
     */
    public function getAjaxType()
    {
        return $this->ajaxType;
    }

    /**
     * @param string $ajaxType
     */
    public function setAjaxType($ajaxType)
    {
        $this->ajaxType = $ajaxType;
    }

    /**
     * @return string
     */
    public function getAjaxData()
    {
        return $this->ajaxData;
    }

    /**
     * @param string $ajaxData
     */
    public function setAjaxData($ajaxData)
    {
        $this->ajaxData = $ajaxData;
    }

    /**
     * @return string
     */
    public function getDisplay()
    {
        return $this->display;
    }

    /**
     * @param string $display
     */
    public function setDisplay($display)
    {
        $this->display = $display;
    }
}
