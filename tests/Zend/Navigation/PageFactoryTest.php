<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Navigation
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

use PHPUnit\Framework\Attributes\Group;

/**
 * Tests Zend_Navigation_Page::factory()
 *
/**
 * @category   Zend
 * @package    Zend_Navigation
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
#[Group('Zend_Navigation')]
class Zend_Navigation_PageFactoryTest extends PHPUnit\Framework\TestCase
{
    protected $_oldIncludePath;

    protected function setUp(): void
    {
        // store old include path
        $this->_oldIncludePath = get_include_path();

        // add _files dir to include path
        $addToPath = dirname(__FILE__) . '/_files';
        set_include_path($addToPath . PATH_SEPARATOR . $this->_oldIncludePath);
    }

    protected function tearDown(): void
    {
        // reset include path
        set_include_path($this->_oldIncludePath);
    }

    public function testDetectMvcPage()
    {
        $pages = array(
            Zend_Navigation_Page::factory(array(
                'label'  => 'MVC Page',
                'action' => 'index'
            )),
            Zend_Navigation_Page::factory(array(
                'label'      => 'MVC Page',
                'controller' => 'index'
            )),
            Zend_Navigation_Page::factory(array(
                'label'  => 'MVC Page',
                'module' => 'index'
            )),
            Zend_Navigation_Page::factory(array(
                'label' => 'MVC Page',
                'route' => 'home'
            )),
            Zend_Navigation_Page::factory(array(
                'label'  => 'MVC Page',
                'params' => array(
                    'foo' => 'bar',
                ),
            )),
        );

        $this->assertContainsOnlyInstancesOf('Zend_Navigation_Page_Mvc', $pages);
    }

    public function testDetectUriPage()
    {
        $page = Zend_Navigation_Page::factory(array(
            'label' => 'URI Page',
            'uri'   => '#'
        ));

        $this->assertInstanceOf(Zend_Navigation_Page_Uri::class, $page);
    }

    public function testSupportsMvcShorthand()
    {
        $mvcPage = Zend_Navigation_Page::factory(array(
            'type'       => 'mvc',
            'label'      => 'MVC Page',
            'action'     => 'index',
            'controller' => 'index'
        ));

        $this->assertInstanceOf(Zend_Navigation_Page_Mvc::class, $mvcPage);
    }

    public function testSupportsUriShorthand()
    {
        $uriPage = Zend_Navigation_Page::factory(array(
            'type'  => 'uri',
            'label' => 'URI Page',
            'uri'   => 'http://www.example.com/'
        ));

        $this->assertInstanceOf(Zend_Navigation_Page_Uri::class, $uriPage);
    }

    public function testSupportsCustomPageTypes()
    {
        $page = Zend_Navigation_Page::factory(array(
            'type'  => 'My_Page',
            'label' => 'My Custom Page'
        ));

        return $this->assertInstanceOf(My_Page::class, $page);
    }

    public function testShouldFailForInvalidType()
    {
        $this->expectException(Zend_Navigation_Exception::class);
        $page = Zend_Navigation_Page::factory(array(
            'type'  => 'My_InvalidPage',
            'label' => 'My Invalid Page'
        ));
    }

    public function testShouldFailForNonExistantType()
    {
        $pageConfig = array(
            'type'  => 'My_NonExistant_Page',
            'label' => 'My non-existant Page'
        );

        try {
            $page = Zend_Navigation_Page::factory($pageConfig);

            $this->fail(
                'A Zend_Exception has not been thrown for non-existant class'
            );
        } catch (Zend_Exception $e) {
            $this->assertEquals(
                'File "My' . DIRECTORY_SEPARATOR . 'NonExistant' . DIRECTORY_SEPARATOR . 'Page.php" does not exist or class '
                . '"My_NonExistant_Page" was not found in the file',
                $e->getMessage()
            );
        }
    }

    public function testShouldFailIfUnableToDetermineType()
    {
        try {
            $page = Zend_Navigation_Page::factory(array(
                'label' => 'My Invalid Page'
            ));

            $this->fail(
                'An exception has not been thrown for invalid page type'
            );
        } catch (Zend_Navigation_Exception $e) {
            $this->assertEquals(
                'Invalid argument: Unable to determine class to instantiate '
                . '(Page label: My Invalid Page)',
                $e->getMessage()
            );
        }
    }
}
