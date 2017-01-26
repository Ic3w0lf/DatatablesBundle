<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Column;

use Sg\DatatablesBundle\Datatable\Data\DatatableQuery;
use Sg\DatatablesBundle\Datatable\View\AbstractViewOptions;
use Sg\DatatablesBundle\OptionsResolver\OptionsInterface;
use Sg\DatatablesBundle\Datatable\Filter\FilterInterface;
use Sg\DatatablesBundle\Datatable\Filter\FilterFactory;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Closure;

/**
 * Class AbstractColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
abstract class AbstractColumn implements ColumnInterface, OptionsInterface
{
    /**
     * Column options.
     *
     * @var array
     */
    protected $options;

    /**
     * Set the data source for the column from the rows data object / array.
     *
     * @var null|string
     */
    protected $data;

    /**
     * Data source copy.
     *
     * @var null|string
     */
    protected $dql;

    /**
     * Class to assign to each cell in the column.
     *
     * @var string
     */
    protected $class;

    /**
     * Add padding to the text content used when calculating the optimal with for a table.
     *
     * @var string
     */
    protected $padding;

    /**
     * Set default, static, content for a column.
     *
     * @var string|null
     */
    protected $defaultContent;

    /**
     * Set a descriptive name for a column.
     *
     * @var string
     */
    protected $name;

    /**
     * Enable or disable ordering on this column.
     *
     * @var boolean
     */
    protected $orderable;

    /**
     * Render (process) the data for use in the table.
     *
     * @var null|string
     */
    protected $render;

    /**
     * Additional render method arguments
     *
     * @var array
     */
    protected $renderArgs;

    /**
     * Enable or disable filtering on the data in this column.
     *
     * @var boolean
     */
    protected $searchable;

    /**
     * Set the column title.
     *
     * @var string
     */
    protected $title;

    /**
     * Set the column type - used for filtering and sorting string processing.
     *
     * @var string
     */
    protected $type;

    /**
     * Enable or disable the display of this column.
     *
     * @var boolean
     */
    protected $visible;

    /**
     * Column width assignment.
     *
     * @var string
     */
    protected $width;

    /**
     * Order direction application sequence.
     *
     * @var array
     */
    protected $orderSequence;

    /**
     * A Filter instance.
     *
     * @var FilterInterface
     */
    protected $filter;

    /**
     * Add column only if parameter / conditions are TRUE
     *
     * @var Closure|null
     */
    protected $addIf;

    /**
     * Editable flag.
     *
     * @var boolean
     */
    protected $editable;

    /**
     * Name of datatable view.
     *
     * @var string
     */
    protected $tableName;

    /**
     * Column index.
     * Saves the position in the columns array.
     *
     * @var integer
     */
    protected $index;

    /**
     * Holds editor specific column options
     *
     * @var array
     */
    protected $editorOptions;

    /**
     * Property accessor.
     *
     * @var PropertyAccess
     */
    protected $accessor;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     */
    public function __construct()
    {
        $this->options  = array();
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function setDql($dql)
    {
        $this->dql = $dql;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDql()
    {
        return $this->dql;
    }

    /**
     * {@inheritdoc}
     */
    public function addDataToOutputArray(&$row)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function renderContent(&$row, DatatableQuery $datatableQuery = null)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function isAddIfClosure()
    {
        if ($this->addIf instanceof Closure) {
            return call_user_func($this->addIf);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isAssociation()
    {
        return (false === strstr($this->data, '.') ? false : true);
    }

    //-------------------------------------------------
    // OptionsInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        // most common column options
        $resolver->setDefaults(array(
            'class'           => '',
            'default_content' => null,
            'padding'         => '',
            'name'            => '',
            'orderable'       => true,
            'render'          => null,
            'render_args'     => array(),
            'searchable'      => true,
            'title'           => '',
            'type'            => '',
            'visible'         => true,
            'width'           => '',
            'order_sequence'  => null,
            'add_if'          => null,
            'editor_options'  => array()
        ));

        $resolver->setAllowedTypes('class', 'string');
        $resolver->setAllowedTypes('default_content', array('string', 'null'));
        $resolver->setAllowedTypes('padding', 'string');
        $resolver->setAllowedTypes('name', 'string');
        $resolver->setAllowedTypes('orderable', 'bool');
        $resolver->setAllowedTypes('render', array('string', 'null'));
        $resolver->setAllowedTypes('render_args', 'array');
        $resolver->setAllowedTypes('searchable', 'bool');
        $resolver->setAllowedTypes('title', 'string');
        $resolver->setAllowedTypes('type', 'string');
        $resolver->setAllowedTypes('visible', 'bool');
        $resolver->setAllowedTypes('width', 'string');
        $resolver->setAllowedTypes('order_sequence', array('array', 'null'));
        $resolver->setAllowedTypes('add_if', array('Closure', 'null'));
        $resolver->setAllowedTypes('editor_options', 'array');

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function configureEditorOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'className'     => '',
            'data'          => $this->getData(),
            'def'           => null,
            'entityDecode'  => true,
            'fieldInfo'     => null,
            'id'            => null,
            'label'         => $this->options['title'],
            'labelInfo'     => null,
            'message'       => null,
            'multiEditable' => true,
            'name'          => $this->getData(),
            'type'          => 'text',
            'options'       => null, //todo
            'attr'          => array(), //todo
            'opts'          => array() //todo
        ));

        $resolver->setAllowedTypes('className', 'string');
        $resolver->setAllowedTypes('data', array('string', 'integer'));
        $resolver->setAllowedTypes('def', array('null', 'string', 'integer'));
        $resolver->setAllowedTypes('entityDecode', 'boolean');
        $resolver->setAllowedTypes('fieldInfo', array('null', 'string'));
        $resolver->setAllowedTypes('id', array('null', 'string'));
        $resolver->setAllowedTypes('label', array('null', 'string'));
        $resolver->setAllowedTypes('labelInfo', array('null', 'string'));
        $resolver->setAllowedTypes('message', array('null', 'string'));
        $resolver->setAllowedTypes('multiEditable', 'boolean');
        $resolver->setAllowedTypes('name', 'string');
        $resolver->setAllowedTypes('type', 'string');

        return $this;
    }

    //-------------------------------------------------
    // OptionsResolver
    //-------------------------------------------------

    /**
     * Setup options resolver.
     *
     * @param array $options
     *
     * @return $this
     * @throws \Exception
     */
    public function setupOptionsResolver(array $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);

        $editorResolver = new OptionsResolver();
        $this->configureEditorOptions($editorResolver);
        $this->options['editor_options'] = $editorResolver->resolve($this->options['editor_options']);

        AbstractViewOptions::callingSettersWithOptions($this->options, $this);

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get data.
     *
     * @return null|string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set class.
     *
     * @param string $class
     *
     * @return $this
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set padding.
     *
     * @param string $padding
     *
     * @return $this
     */
    public function setPadding($padding)
    {
        $this->padding = $padding;

        return $this;
    }

    /**
     * Get padding.
     *
     * @return string
     */
    public function getPadding()
    {
        return $this->padding;
    }

    /**
     * Set default content.
     *
     * @param string|null $defaultContent
     *
     * @return $this
     */
    public function setDefaultContent($defaultContent)
    {
        $this->defaultContent = $defaultContent;

        return $this;
    }

    /**
     * Get default content.
     *
     * @return string|null
     */
    public function getDefaultContent()
    {
        return $this->defaultContent;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set orderable.
     *
     * @param boolean $orderable
     *
     * @return $this
     */
    public function setOrderable($orderable)
    {
        $this->orderable = (boolean) $orderable;

        return $this;
    }

    /**
     * Get orderable.
     *
     * @return boolean
     */
    public function getOrderable()
    {
        return $this->orderable;
    }

    /**
     * Set render.
     *
     * @param string $render
     *
     * @return $this
     */
    public function setRender($render)
    {
        $this->render = $render;

        return $this;
    }

    /**
     * Get render.
     *
     * @return null|string
     */
    public function getRender()
    {
        return $this->render;
    }

    /**
     * @param array $renderArgs
     */
    public function setRenderArgs($renderArgs)
    {
        $this->renderArgs = $renderArgs;
    }

    /**
     * @return array
     */
    public function getRenderArgs()
    {
        return $this->renderArgs;
    }

    /**
     * Set searchable.
     *
     * @param boolean $searchable
     *
     * @return $this
     */
    public function setSearchable($searchable)
    {
        $this->searchable = (boolean) $searchable;

        return $this;
    }

    /**
     * Get searchable.
     *
     * @return boolean
     */
    public function getSearchable()
    {
        return $this->searchable;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set visible.
     *
     * @param boolean $visible
     *
     * @return $this
     */
    public function setVisible($visible)
    {
        $this->visible = (boolean) $visible;

        return $this;
    }

    /**
     * Get visible.
     *
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set width.
     *
     * @param string $width
     *
     * @return $this
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width.
     *
     * @return string
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set orderSequence.
     *
     * @param array|null $orderSequence
     *
     * @return $this
     */
    public function setOrderSequence($orderSequence)
    {
        $this->orderSequence = $orderSequence;

        return $this;
    }

    /**
     * Get orderSequence.
     *
     * @return array|null
     */
    public function getOrderSequence()
    {
        return $this->orderSequence;
    }

    /**
     * Set Filter instance.
     *
     * @param array $filter
     *
     * @return $this
     */
    public function setFilter(array $filter)
    {
        /** @var \Sg\DatatablesBundle\Datatable\Filter\AbstractFilter $newFilter */
        $newFilter    = FilterFactory::createFilterByType($filter[0]);
        $this->filter = $newFilter->setupOptionsResolver($filter[1]);

        return $this;
    }

    /**
     * Get Filter instance.
     *
     * @return FilterInterface
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * Set addIf.
     *
     * @param Closure|null $addIf
     *
     * @return $this
     */
    public function setAddIf($addIf)
    {
        $this->addIf = $addIf;

        return $this;
    }

    /**
     * Get addIf.
     *
     * @return Closure|null
     */
    public function getAddIf()
    {
        return $this->addIf;
    }

    /**
     * Set editable.
     *
     * @param boolean $editable
     *
     * @return $this
     */
    public function setEditable($editable)
    {
        $this->editable = $editable;

        return $this;
    }

    /**
     * Get editable.
     *
     * @return boolean
     */
    public function getEditable()
    {
        return $this->editable;
    }

    /**
     * Set table name.
     *
     * @param string $tableName
     *
     * @return $this
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;

        return $this;
    }

    /**
     * Get table name.
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * Set index.
     *
     * @param integer $index
     *
     * @return $this
     */
    public function setIndex($index)
    {
        $this->index = $index;

        return $this;
    }

    /**
     * Get index.
     *
     * @return integer
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @return array
     */
    public function getEditorOptions()
    {
        return $this->editorOptions;
    }

    /**
     * @param array $editorOptions
     *
     * @return $this
     */
    public function setEditorOptions($editorOptions)
    {
        $this->editorOptions = $editorOptions;

        return $this;
    }

    /**
     * Get dqlProperty.
     *
     * @return string
     */
    public function getDqlProperty()
    {
        return '['.str_replace('.', '][', $this->dql).']';
    }

    /**
     * Get accessor.
     *
     * @return PropertyAccess|\Symfony\Component\PropertyAccess\PropertyAccessor
     */
    public function getAccessor()
    {
        return $this->accessor;
    }
}
