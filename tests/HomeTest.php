<?php

use PHPUnit\Framework\TestCase;

class HomeViewTest extends TestCase
{
    public function testView()
    {
        ob_start();
        include './App/Views/Home/index.html';
        $output = ob_get_clean();

        // Assertion 1: Check if the CSS class "content-wrapper" exists
        $this->assertStringContainsString('class="content-wrapper"', $output);

        // Assertion 2: Check if the HTML contains a specific <ul> element
        $this->assertStringContainsString('<ul class="list-inline">', $output);

        // Assertion 3: Check if the HTML contains a specific <a> element
        $this->assertStringContainsString('<a href="#" class="color-active">', $output);

        // Assertion 4: Check if the HTML contains a specific <div> element
        $this->assertStringContainsString('<div class="clearfix"></div>', $output);

        // Assertion 5: Check if the HTML contains a specific <li> element
        $this->assertStringContainsString('<li><a href="#">Autour de moi</a></li>', $output);

        // Assertion 6: Check if the HTML contains a specific <div> element with a nested <div> element
        $this->assertStringContainsString('<div class="content-block head-div">', $output);
        $this->assertStringContainsString('<div class="cb-header">', $output);

        // Assertion 7: Check if the HTML contains a specific <a> element with nested <span> elements
        $this->assertStringContainsString('<a href="#" class="color-active">', $output);
        $this->assertStringContainsString('<span class="visible-xs">À la une</span>', $output);
        $this->assertStringContainsString('<span class="hidden-xs">À la une</span>', $output);

        // Assertion 8: Check if the HTML contains a specific <div> element with a nested <a> element
        $this->assertStringContainsString('<div class="cb-content">', $output);
        $this->assertStringContainsString('<div class="row" id="articlelist"></div>', $output);
    }
}


