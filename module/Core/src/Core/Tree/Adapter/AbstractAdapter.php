<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Core_Tree
 * @author    AlloVince
 */

namespace Core\Tree\Adapter;

/**
 * Abstract class tree
 *
 * @category   Eva
 * @package    Core_Tree
 * @copyright  Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license    http://framework.zend.com/license/new-bsd     New BSD Licens
 */
abstract class AbstractAdapter
{
    /**
     * Internal list of nodes
     *
     * @var array
     */
    protected $nodes = array();

    /**
     * options
     *
     * @var array
     */
    protected $options = array();
    
    public function setOptions($options = array())
    {
        $this->options = $options;
        return $this;    
    }

    public function getOptions($optionName = null)
    {
        if (isset($this->options[$optionName])) {
            return $this->options[$optionName];
        } else {
            return $this->options;
        }
    }
    
    abstract public function getChildren($node = null, $includeSelf = true);
    
    abstract public function getChild($node = null, $includeSelf = true);
    
    abstract public function getParents($node, $includeSelf = true);
    
    abstract public function getParent($node);
    
    abstract public function getTree($node = null, $includeSelf = true);
    
    abstract public function updateNode($nodeUpdate);
    
    abstract public function insertNode($node);
    
    abstract public function deleteNode($nodeId);
}
