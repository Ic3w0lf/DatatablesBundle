<?php

namespace Sg\DatatablesBundle\Datatable\Option;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Editor
 */
class Editor extends AbstractViewOptions
{
    /**
     * URL for CRUD Ajax calls
     *
     * @var string
     */
    protected $ajaxUrl;

    /**
     * Request type (usually POST)
     *
     * @var string
     */
    protected $ajaxType;

    /**
     * A JS callback function to modify data sent to the
     * CRUD controller
     *
     * @var string
     */
    protected $ajaxData;

    /**
     * Display option
     *
     * @var string
     */
    protected $display;

    /**
     * Path to a twig template which has a form template
     * which this Editor will use
     *
     * @var string
     */
    protected $twigTemplate;

    /**
     * The form ID of the template that was passed
     *
     * @var string
     */
    protected $templateFormId;

    /**
     * Array of events, key hold events name
     *
     * @var array
     */
    protected $events;

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'ajax_url'  => '',
                'ajax_type' => 'POST',
                'ajax_data' => null,
                'display'   => 'bootstrap',
                'template'  => array('template' => null, 'id' => null),
                'events'    => array()
            )
        );

        $resolver->setAllowedTypes('ajax_url', 'string');
        $resolver->setAllowedTypes('ajax_type', 'string');
        $resolver->setAllowedTypes('ajax_data', array('null', 'string'));
        $resolver->setAllowedTypes('display', 'string');
        $resolver->setAllowedTypes('template', 'array');
        $resolver->setAllowedTypes('events', 'array');

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
     *
     * @return $this
     */
    public function setAjaxUrl($ajaxUrl)
    {
        $this->ajaxUrl = $ajaxUrl;

        return $this;
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
     *
     * @return $this
     */
    public function setAjaxType($ajaxType)
    {
        $this->ajaxType = $ajaxType;

        return $this;
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
     *
     * @return $this
     */
    public function setAjaxData($ajaxData)
    {
        $this->ajaxData = $ajaxData;

        return $this;
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
     *
     * @return $this
     */
    public function setDisplay($display)
    {
        $this->display = $display;

        return $this;
    }

    /**
     * @return string
     */
    public function getTwigTemplate()
    {
        return $this->twigTemplate;
    }

    /**
     * @return string
     */
    public function getTemplateFormId()
    {
        return $this->templateFormId;
    }

    /**
     * @param array $template
     *
     * @return $this
     */
    public function setTemplate(array $template)
    {
        $this->twigTemplate   = $template['template'];
        $this->templateFormId = $template['id'];

        return $this;
    }

    /**
     * @return array
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param array $events
     *
     * @return Editor
     */
    public function setEvents($events)
    {
        $this->events = $events;

        return $this;
    }
}
