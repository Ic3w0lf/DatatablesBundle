<?php

namespace Sg\DatatablesBundle\Datatable\View;

use Sg\DatatablesBundle\Datatable\Option\Ajax;
use Sg\DatatablesBundle\Datatable\Option\Callbacks;
use Sg\DatatablesBundle\Datatable\Option\Editor;
use Sg\DatatablesBundle\Datatable\Option\Events;
use Sg\DatatablesBundle\Datatable\Option\Features;
use Sg\DatatablesBundle\Datatable\Option\Options;
use Sg\DatatablesBundle\Datatable\Option\TopActions;

/**
 * Class DatatableViewConfiguration
 */
class DatatableViewConfiguration
{
    /**
     * Actions on the top of the table (e.g. 'New' button).
     *
     * @var TopActions
     */
    protected $topActions;

    /**
     * A Features instance.
     *
     * @var Features
     */
    protected $features;

    /**
     * An Options instance.
     *
     * @var Options
     */
    protected $options;

    /**
     * A Callback instance.
     *
     * @var Callbacks
     */
    protected $callbacks;

    /**
     * An Events instance.
     *
     * @var Events
     */
    protected $events;

    /**
     * An Ajax instance.
     *
     * @var Ajax
     */
    protected $ajax;

    /**
     * An Editor instance.
     *
     * @var Editor
     */
    protected $editor;

    /**
     * DatatableViewConfiguration constructor.
     */
    public function __construct()
    {
        $this->topActions = new TopActions();
        $this->features   = new Features();
        $this->options    = new Options();
        $this->callbacks  = new Callbacks();
        $this->events     = new Events();
        $this->ajax       = new Ajax();
        $this->editor     = new Editor();
    }

    /**
     * @return TopActions
     */
    public function getTopActions()
    {
        return $this->topActions;
    }

    /**
     * @return Features
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * @return Options
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return Callbacks
     */
    public function getCallbacks()
    {
        return $this->callbacks;
    }

    /**
     * @return Events
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @return Ajax
     */
    public function getAjax()
    {
        return $this->ajax;
    }

    /**
     * @return Editor
     */
    public function getEditor()
    {
        return $this->editor;
    }
}
