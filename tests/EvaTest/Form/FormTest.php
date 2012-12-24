<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Form
 */

namespace EvaTest\Form;

use PHPUnit_Framework_TestCase as TestCase;
use stdClass;
use Zend\Form\Element;
use Zend\Form\Factory;
use Zend\Form\Fieldset;
use Eva\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFilterFactory;
use Zend\Stdlib\Hydrator;
use ZendTest\Form\TestAsset\Entity;

class FormTest extends TestCase
{
    /**
     * @var Form
     */
    protected $form;

    public function setUp()
    {
        $this->form = new Form();
    }
}
