<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Magento
 * @package     Magento_Catalog
 * @subpackage  integration_tests
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/*
 * Test class for Mage_Catalog_Seo_SitemapController.
 *
 * @magentoDataFixture Mage/Catalog/_files/categories.php
 */
class Mage_Catalog_Seo_SitemapControllerTest extends Magento_Test_TestCase_ControllerAbstract
{

    /**
     * @magentoDataFixture Mage/Catalog/_files/categories.php
     */
    public function testCategoryAction()
    {
        $this->dispatch('catalog/seo_sitemap/category/');

        $responseBody = $this->getResponse()->getBody();

        /* General content */
        $this->assertContains('<h1>' . Mage::app()->getTranslator()->translate(array('Categories')) . '</h1>', $responseBody);
        $this->assertContains(Mage::app()->getTranslator()->translate(array('%s Item(s)', 5)), $responseBody);

        /* Sitemap content */
        $matchesCount = preg_match('#<ul class="sitemap">.+?</ul>#s', $responseBody, $matches);
        $this->assertEquals(1, $matchesCount);
        $listHtml = $matches[0];

        $this->assertContains('Category 1', $listHtml);
        $this->assertContains('Category 1.1', $listHtml);
        $this->assertContains('Category 1.1.1', $listHtml);
        $this->assertContains('Category 2', $listHtml);
        $this->assertContains('Movable', $listHtml);

        $this->assertContains('/index.php/category-1.html', $listHtml);

        $this->markTestIncomplete('Bug MAGETWO-144');

        $this->assertContains('/index.php/category-1/category-1-1.html', $listHtml);
        $this->assertContains('/index.php/category-1/category-1-1/category-1-1-1.html', $listHtml);
        $this->assertContains('/index.php/category-2.html', $listHtml);
        $this->assertContains('/index.php/movable.html', $listHtml);
    }

    /**
     * @magentoDataFixture Mage/Catalog/_files/categories.php
     * @magentoConfigFixture current_store catalog/sitemap/tree_mode 1
     */
    public function testCategoryActionTreeMode()
    {

        $this->markTestSkipped('Skipped because fails in Magento 1.x.');

        /*
        $this->dispatch('catalog/seo_sitemap/category/');

        $handles = Mage::app()->getLayout()->getUpdate()->getHandles();
        $this->assertContains('catalog_seo_sitemap_category_type_tree', $handles);
        */
    }
    
    /**
     * @magentoDataFixture Mage/Catalog/_files/categories.php
     */
    public function testProductAction()
    {
        $this->dispatch('catalog/seo_sitemap/product/');

        $responseBody = $this->getResponse()->getBody();

        /* General content */
        $this->assertContains('<h1>' . Mage::app()->getTranslator()->translate(array('Products')) . '</h1>', $responseBody);
        $this->assertContains(Mage::app()->getTranslator()->translate(array('%s Item(s)', 2)), $responseBody);

        /* Sitemap content */
        $matchesCount = preg_match('#<ul class="sitemap">.+?</ul>#s', $responseBody, $matches);
        $this->assertEquals(1, $matchesCount);
        $listHtml = $matches[0];

        $this->assertContains('Simple Product', $listHtml);
        $this->assertContains('Simple Product Two', $listHtml);

        $this->assertContains('/index.php/simple-product.html', $listHtml);
        $this->assertContains('/index.php/simple-product-two.html', $listHtml);
    }
}