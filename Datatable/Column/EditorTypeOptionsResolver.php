<?php

namespace Datatable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EditorTypeOptionsResolver
 */
class EditorTypeOptionsResolver
{
    /**
     * @param array $options
     *
     * @return array
     */
    public function resolve(array $options)
    {
        $resolver = new OptionsResolver();

        switch ($options['type']) {
            case 'text':
                $resolver->setDefault('attr', array());
                $resolver->setAllowedTypes('attr', 'array');
                break;
            case 'checkbox':
                $resolver->setDefaults(array(
                    'options'         => array(),
                    'optionsPair'     => array(),
                    'seperator'       => null,
                    'unselectedValue' => null
                ));
                $resolver->setAllowedTypes('options', 'array');
                $resolver->setAllowedTypes('options', 'array');
                $resolver->setAllowedTypes('options', array('null', 'string'));
                $resolver->setAllowedTypes('unselectedValue', array('null', 'string'));
                break;
            case 'date':
                $resolver->setDefaults(array(
                    'attr' => array(),
                    'dateFormat' => '$.datepicker.RFC_2822',
                    'dateImage'  =>  '../../images/calender.png',
                    'opts'       => array()
                ));
        }
    }
}
