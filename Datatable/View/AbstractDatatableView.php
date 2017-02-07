<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\View;

use Sg\DatatablesBundle\Datatable\Column\ColumnBuilder;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Sg\DatatablesBundle\Datatable\Column\ColumnFactory;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\DependencyInjection\Container;
use Exception;

/**
 * Class AbstractDatatableView
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
abstract class AbstractDatatableView implements DatatableViewInterface
{
    const NAME_REGEX = '/[a-zA-Z0-9\-\_]+/';

    /**
     * The AuthorizationChecker service.
     *
     * @var AuthorizationCheckerInterface
     */
    protected $authorizationChecker;

    /**
     * The SecurityTokenStorage service.
     *
     * @var TokenStorageInterface
     */
    protected $securityToken;

    /**
     * The Translator service.
     *
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * The Router service.
     *
     * @var RouterInterface
     */
    protected $router;

    /**
     * The doctrine orm entity manager service.
     *
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var DatatableViewConfiguration
     */
    protected $configuration;

    /**
     * A ColumnBuilder instance.
     *
     * @var ColumnBuilder
     */
    protected $columnBuilder;

    /**
     * Data to use as the display data for the table.
     *
     * @var mixed
     */
    protected $data;

    /**
     * This variable stores the array of column names as keys and column ids as values
     * in order to perform search column id by name.
     *
     * @var array
     */
    private $columnNames;

    /**
     * A custom query.
     *
     * @var QueryBuilder
     */
    protected $qb;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TokenStorageInterface         $securityToken
     * @param TranslatorInterface           $translator
     * @param RouterInterface               $router
     * @param EntityManagerInterface        $em
     * @param ColumnFactory                 $columnFactory
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $securityToken,
        TranslatorInterface $translator,
        RouterInterface $router,
        EntityManagerInterface $em,
        ColumnFactory $columnFactory
    )
    {
        $this->assertTheNameOnlyContainsAllowedCharacters();

        $this->authorizationChecker = $authorizationChecker;
        $this->securityToken        = $securityToken;
        $this->translator           = $translator;
        $this->router               = $router;
        $this->em                   = $em;

        $this->configuration = new DatatableViewConfiguration();
        $this->columnBuilder = new ColumnBuilder($this->getName(), $columnFactory);

        $this->data = null;
        $this->qb   = null;
    }

    //-------------------------------------------------
    // DatatableViewInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * @return DatatableViewConfiguration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function getColumnBuilder()
    {
        return $this->columnBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getQb()
    {
        return $this->qb;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set Data.
     *
     * @param mixed $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get Data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    //-------------------------------------------------
    // Helper
    //-------------------------------------------------

    /**
     * Truncate text.
     * 
     * @param string  $text
     * @param integer $chars
     * 
     * @return string
     */
    public function truncate($text, $chars = 25)
    {
        if (strlen($text) > $chars) {
            $text = substr($text . ' ', 0, $chars);
            $text = substr($text, 0, strrpos($text, ' ')) . '...';
        }

        return $text;
    }

    /**
     * Searches the column index by column name.
     * Returns the position of the column in datatable columns or false if column is not found.
     *
     * @param string $name
     *
     * @return int|bool
     */
    public function getColumnIdByColumnName($name)
    {
        if (count($this->columnNames) == 0) {
            /** @var \Sg\DatatablesBundle\Datatable\Column\AbstractColumn $column */
            foreach ($this->getColumnBuilder()->getColumns() as $key => $column) {
                $this->columnNames[$column->getData()] = $key;
            }
        }

        return array_key_exists($name, $this->columnNames) ? $this->columnNames[$name] : false;
    }

    /**
     * Returns options array based on key/value pairs, where key and value are the object's properties.
     *
     * @param ArrayCollection $entitiesCollection
     * @param string          $keyPropertyName
     * @param string          $valuePropertyName
     *
     * @return array
     */
    public function getCollectionAsOptionsArray($entitiesCollection, $keyPropertyName = 'id', $valuePropertyName = 'name')
    {
        $options = array();

        foreach ($entitiesCollection as $entity) {
            $keyPropertyName = Container::camelize($keyPropertyName);
            $keyGetter = 'get' . ucfirst($keyPropertyName);
            $valuePropertyName = Container::camelize($valuePropertyName);
            $valueGetter = 'get' . ucfirst($valuePropertyName);
            $options[$entity->$keyGetter()] = $entity->$valueGetter();
        }

        return $options;
    }

    /**
     * Checks the name only contains letters, numbers, underscores or dashes.
     *
     * @throws Exception
     */
    private function assertTheNameOnlyContainsAllowedCharacters()
    {
        if (1 !== preg_match(self::NAME_REGEX, $this->getName())) {
            throw new Exception('The result of the getName method can only contain letters, numbers, underscore and dashes.');
        }
    }
}
