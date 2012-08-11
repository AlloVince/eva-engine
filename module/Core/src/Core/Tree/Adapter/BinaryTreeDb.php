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

use Eva\Api,
    Zend\Db\ResultSet\ResultSet,
    Zend\Db\Sql\Expression;
/**
 * Tree adapter class for the BinaryTreeDB protocol
 *
 * @category   Eva
 * @package    Eva_Tree
 * @copyright  Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license    http://framework.zend.com/license/new-bsd     New BSD Licens
 */
class BinaryTreeDb extends AbstractAdapter
{
    /**
     * The Root Node
     *
     * @var array
     */ 
    protected $rootNode;
    
    /**
     * The dbTable Object
     *
     * @var object
     */ 
    protected $dbTable; 


    /**
     * The dbTable Name
     *
     * @var string
     */ 
    protected $tableName; 

    /**
     * Constructor for Http File Transfers
     *
     * @param array $options OPTIONAL Options to set
     */
    public function __construct($options = array())
    {
        $this->setOptions($options)
             ->setDbTable()
             ->setRootNode();
    }

    /**
     * Sets Options for adapters
     *
     * @param array $options Options to set
     */
    public function setOptions($options = array())
    {
        if (empty($options)) {
        }

        $this->options = $options;
        
        return $this;
    }
    
    /**
     * Sets dbTable for adapters
     *
     * @param array $dbTable Options to set
     */
    public function setDbTable($dbTable = null)
    {
        if (empty($dbTable)) {
            $this->dbTable = Api::_()->getDbTable($this->options['dbTable']);
        } else {
            $this->dbTable = Api::_()->getDbTable($dbTable);
        }

        $this->tableName = $this->dbTable->initTableName()->getTable();
        
        return $this;
    }

    /**
     * Sets Root Node for adapters
     *
     * @param array $dbTable Options to set
     */
    public function setRootNode()
    {
        $dbTable = $this->dbTable;
        $dbTable->columns(array('right'))->order('right DESC');
        $node = $dbTable->find('one');
        
        if ($node !== false) {
            $node = (array) $node;
            $rootNode = array(
                'left' => 1,
                'right' => $node['right'] + 1,
            );
        } else {
            $rootNode = array(
                'left' => 1,
                'right' => 2,
            );      
        }
        
        $this->rootNode = $rootNode;
        
        return $this;
    }

    public function getDbTable()
    {
        return $this->dbTable;
    }

    public function getChildren($node = null, $includeSelf = true)
    {
        $this->getTree($node, $includeSelf);
    }

    public function getChild($node = null, $includeSelf = true)
    {
        $nodes = $this->getTree($node, $includeSelf);
        
        $res = array();
            
        if ($includeSelf) {
            $len = 2;
        } else {
            $len = 1;
        }
        $level = $nodes[0]['level'];
        $len += $level;

        foreach ($nodes as $key=>$node) {
            if ($node['level'] < $len) {
                $res[] = $node;
            } 
        }
    
        return $res;
    }

    public function getParents($node, $includeSelf = true)
    {
        if (!$node) {
            return $node;
        }
    
        if (!$nodes['parentId']) {
            return null;
        }

        if ($includeSelf === true) {
            $dbTable->where(function($where) use ($node){
                $where->greaterThanOrEqualTo('right', $node['right']);
                $where->lessThanOrEqualTo('left', $node['left']);
                return $where;
            });
        } else {
            $dbTable->where(function($where) use ($node){
                $where->greaterThan('right', $node['right']);
                $where->lessThan('left', $node['left']);
                return $where;
            });
        }
        $dbTable->disableLimit(); 
        $dbTable->order('left ASC');
        $nodes = $dbTable->find('all');

        return $nodes;
    }

    public function getParent($node)
    {
        if (!$node) {
            return $node;
        }

        if (!$nodes['parentId']) {
            return null;
        }

        $parentNode = $dbTable->where(array('id' => $node['parentId']))->find('one');

        return $parentNode;
    }

    public function getTree($node = null, $includeSelf = true)
    {
        $dbTable = $this->dbTable;

        if (!$node) {
            $node = $this->rootNode;
        }

        $tableName = $this->tableName;

        // $dbTable->columns(array("level" => new Expression("(SELECT COUNT(*) FROM $tableName WHERE `left` < {$node['left']} AND `right` > {$node['right']})")));

        if ($includeSelf === true) {
            $dbTable->where(function($where) use ($node){
                $where->greaterThanOrEqualTo('left', $node['left']);
                $where->lessThanOrEqualTo('right', $node['right']);
                return $where;
            });
        } else {
            $dbTable->where(function($where) use ($node){
                $where->greaterThan('left', $node['left']);
                $where->lessThan('right', $node['right']);
                return $where;
            });
        }
        
        $dbTable->order('left ASC');
        $dbTable->disableLimit(); 
        $nodes = $dbTable->find('all');
        $nodes = $this->getLevel($nodes);
        
        $nodes = $this->sortByOrderNumber($nodes);

        return $nodes;
    }

    protected function getLevel($nodes)
    {   
        if (!$nodes) {
            return $nodes;
        }
    
        $nodes = $nodes->toArray();
        
        foreach ($nodes as $key=>$node) {
            $nodes[$key]['level'] = 1;

            foreach ($nodes as $tmpnode) {
                if ($tmpnode['left'] < $node['left'] && $tmpnode['right'] > $node['right']) {
                    $nodes[$key]['level']++;
                }
            }
        }
        // return new ResultSet('array', $nodes);
    
        return $nodes;
    }

    protected function sortByOrderNumber($nodes)
    {   
        if (!$nodes) {
            return $nodes;
        }
            
        $nodes = \Eva\Stdlib\Arraylib\Sort::multiSortArray(
            $nodes, 
            'level', 'SORT_ASC', 'SORT_NUMERIC',
            'orderNumber', 'SORT_ASC', 'SORT_NUMERIC'
        );
        
        $levelNeedle = array();

        $node = $nodes[0];
        unset($nodes[0]);
        $res[] = $node;
        $levelNeedle[$node['level']] = $node;

        $count = count($nodes);
        $i = 1;
        while ($i <= $count){

            list($node, $nodes, $levelNeedle) = $this->findNextNode($node, $nodes, $levelNeedle);

            $res[] = $node;

            if (count($nodes) < 1) {
                break;
            }

            $i++;
        }

        return $res;
    }

    protected function findNextNode($current, $nodes, $levelNeedle)
    {
        if (!$current || !$nodes) {
            return array($current, $nodes, $levelNeedle);
        }   

        foreach ($nodes as $key=>$node) {
            if ($current['id'] == $node['parentId']) {
                unset($nodes[$key]);
                $levelNeedle[$node['level']] = $node;
                return array($node, $nodes, $levelNeedle);
            }
        }
        
        if (count($levelNeedle) > 1 && $current['level'] > 1) {
            unset($levelNeedle[$current['level']]);
            return $this->findNextNode($levelNeedle[$current['level'] - 1], $nodes, $levelNeedle);
        }
        
        $levelNeedle = array();
   
        foreach ($nodes as $key=>$node) {
            unset($nodes[$key]);
            $levelNeedle[$node['level']] = $node;
            return array($node, $nodes, $levelNeedle);
        }
    }

    public function updateNode($nodeUpdate)
    {
        if (empty($nodeUpdate['id']) || empty($nodeUpdate)) {
            return false;
        }

        $dbTable = $this->dbTable;
        $tableName = $this->tableName;

        $node = $dbTable->where(array('id' => $nodeUpdate['id']))->find('one');

        $node = (array) $node;

        $width = $node['right'] - $node['left'] + 1;
        
        if ($nodeUpdate['parentId'] != $node['parentId']) {
            if ($nodeUpdate['parentId']) {
                $parentNode = $dbTable->where(array('id' => $nodeUpdate['parentId']))->find('one');
            } else {
                $parentNode = $this->rootNode;
            }
            if ($parentNode['left'] >= $node['left'] && $parentNode['right'] <= $node['right']) {
                return false;
            }        

            $dbTable->where(function($where) use ($node){
                $where->greaterThanOrEqualTo('left', $node['left']);
                $where->lessThanOrEqualTo('right', $node['right']);
                return $where;
            });

            $dbTable->disableLimit(); 
            $dbTable->columns(array('id'));
            $subNodes = $dbTable->find('all');

            if (count($subNodes) > 0) {
                foreach ($subNodes as $subNode) {
                    $subNodeIds[] = $subNode['id'];
                }
            }  
            
            if ($node['left'] > $parentNode['left']) {

                $dbTable->where(function($where) use ($node, $parentNode){
                    $where->lessThan('left', $node['left']);
                    $where->greaterThan('left', $parentNode['left']);
                    $where->notEqualTo('id', $node['parentId']);
                    return $where;
                });
                $dbTable->save(array(
                    'right' => new Expression("`right` + $width"),
                    'left' => new Expression("`left` + $width")
                ));
                
                $dbTable->where(array('id' => $parentNode['id']));
                $dbTable->save(array(
                    'right' => new Expression("`right` + $width")
                )); 

                $dbTable->where(array('id' => $node['parentId']));
                $dbTable->save(array(
                    'left' => new Expression("`left` + $width")
                ));  
                
                $leftWidth = $parentNode['left'] - $node['left'] + 1;
                $dbTable->where(function($where) use ($subNodeIds){
                    $where->in('id', $subNodeIds);
                    return $where;
                });
                $dbTable->save(array(
                    'left' => new Expression("`left` + $leftWidth"),
                    'right' => new Expression("`right` + $leftWidth")
                ));   
            } else {

                $dbTable->where(function($where) use ($node, $parentNode){
                    $where->greaterThan('right', $node['right']);
                    $where->lessThan('right', $parentNode['right']);
                    $where->notEqualTo('id', $node['parentId']);
                    return $where;
                });
                $dbTable->save(array(
                    'right' => new Expression("`right` - $width"),
                    'left' => new Expression("`left` - $width")
                ));

                $dbTable->where(array('id' => $parentNode['id']));
                $dbTable->save(array(
                    'left' => new Expression("`left` - $width")
                )); 

                $dbTable->where(array('id' => $node['parentId']));
                $dbTable->save(array(
                    'right' => new Expression("`right` - $width")
                ));  

                $leftWidth = $parentNode['right'] - $node['right'] - 1;
                $dbTable->where(function($where) use ($subNodeIds){
                    $where->in('id', $subNodeIds);
                    return $where;
                });
                $dbTable->save(array(
                    'left' => new Expression("`left` + $leftWidth"),
                    'right' => new Expression("`right` + $leftWidth")
                ));    
            }

        } else {
            unset($nodeUpdate['left']);
            unset($nodeUpdate['right']);
        } 

        if (!$node) {
            return false;
        }

        $node = $dbTable->where(array('id' => $node['id']))->save($nodeUpdate);

        return true;
    }

    public function insertNode($node)
    {
        if (empty($node)) {
            return false;
        }

        $dbTable = $this->dbTable;
        $tableName = $this->tableName;

        if (isset($node['parentId']) && $node['parentId'] > 0) {
            $parentNode = $dbTable->where(array('id' => $node['parentId']))->find('one');
        } else {
            $parentNode = $this->rootNode;
        }

        $dbTable->where(function($where) use ($parentNode){
            $where->greaterThan('right', $parentNode['left']);
            return $where;
        });
        $dbTable->save(array('right' => new Expression("`right` + 2")));

        $dbTable->where(function($where) use ($parentNode){
            $where->greaterThan('left', $parentNode['left']);
            return $where;
        });
        $dbTable->save(array('left' => new Expression("`left` + 2")));

        $node['right'] = $parentNode['left'] + 2;
        $node['left'] = $parentNode['left'] + 1;

        $dbTable->create($node);

        return $dbTable->getLastInsertValue();
    }

    public function deleteNode($node)
    {
        if (empty($node)) {
            return false;
        }

        $dbTable = $this->dbTable;
        $tableName = $this->tableName;

        $node = $dbTable->where(array('id' => $node['id']))->find('one');

        if (!$node) {
            return false;
        }

       $dbTable->where(function($where) use ($node){
            $where->greaterThanOrEqualTo('left', $node['left']);
            $where->lessThanOrEqualTo('right', $node['right']);
            return $where;
        });
        $dbTable->remove();

        $width = $node['right'] - $node['left'] + 1;
        
        $dbTable->where(function($where) use ($node){
            $where->greaterThan('left', $node['right']);
            $where->notEqualTo('id', $node['parentId']);
            return $where;
        });
        $dbTable->save(array(
            'right' => new Expression("`right` - $width"),
            'left' => new Expression("`left` - $width")
        ));

        $dbTable->where(array('id' => $node['parentId']));
        $dbTable->save(array(
            'right' => new Expression("`right` - $width")
        ));  

        return true;
/*
        $dbTable->where(function($where) use ($node){
            $where->greaterThan('left', $node['left']);
            $where->lessThan('right', $node['right']);
            return $where;
        });
        $dbTable->disableLimit(); 
        $dbTable->columns(array('id'));
        $subNodes = $dbTable->find('all');
        if (count($subNodes) > 0) {
            foreach ($subNodes as $subNode) {
                $subNodeIds[] = $subNode['id'];
            }
        }  
        
        $dbTable->where(function($where) use ($node){
            $where->greaterThan('left', $node['right']);
            $where->notEqualTo('id', $node['parentId']);
            return $where;
        });
        $dbTable->save(array(
            'right' => new Expression("`right` - 2"),
            'left' => new Expression("`left` - 2")
        ));

        $dbTable->where(array('id' => $node['parentId']));
        $dbTable->save(array(
            'right' => new Expression("`right` - 2")
        ));  

        if (count($subNodes) > 0) {
            $dbTable->where(function($where) use ($subNodeIds){
                $where->in('id', $subNodeIds);
                return $where;
            });
            $dbTable->save(array(
                'right' => new Expression("`right` - 1"),
                'left' => new Expression("`left` - 1")
            ));
        }

        $node = $dbTable->where(array('id' => $node['id']))->remove();

        return true;
*/
    }
}
