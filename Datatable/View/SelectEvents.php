<?php

namespace Sg\DatatablesBundle\Datatable\View;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SelectEvents
 */
class SelectEvents extends AbstractViewOptions
{
    /**
     * Items (rows, columns or cells) have been selected
     *
     * @var array
     */
    protected $select;

    /**
     * Items (rows, columns or cells) have been deselected
     *
     * @var array
     */
    protected $deselect;

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'select'   => array(),
            'deselect' => array()
        ));

        $resolver->setAllowedTypes('select', 'array');
        $resolver->setAllowedTypes('deselect', 'array');

        $this->nestedOptionsResolver = new OptionsResolver();

        return $this;
    }

    /**
     * Configure and resolve nested options.
     *
     * @param array $options
     *
     * @return $this
     */
    public function configureAndResolveNestedOptions(array $options)
    {
        $this->nestedOptionsResolver->setDefaults(array(
            'template' => '',
            'vars' => null,
        ));

        $this->nestedOptionsResolver->setAllowedTypes('template', 'string');
        $this->nestedOptionsResolver->setAllowedTypes('vars', array('array', 'null'));

        $this->nestedOptionsResolver->resolve($options);

        return $this;
    }

    /**
     * @return array
     */
    public function getSelect()
    {
        return $this->select;
    }

    /**
     * @param array $select
     *
     * @return SelectEvents
     */
    public function setSelect(array $select)
    {
        $this->select = $select;

        return $this;
    }

    /**
     * @return array
     */
    public function getDeselect()
    {
        return $this->deselect;
    }

    /**
     * @param array $deselect
     *
     * @return SelectEvents
     */
    public function setDeselect(array $deselect)
    {
        $this->deselect = $deselect;

        return $this;
    }
}
