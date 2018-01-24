<?php

class PluginTest extends \PHPUnit_Framework_TestCase
{
    protected $plugin;

    public function setUp()
    {
        $this->plugin = new \EncodeonSalesRepManager\Plugin;
    }

    public function test_that_plugin_class_exists()
    {
        $this->assertNotNull($this->plugin);
    }
}
