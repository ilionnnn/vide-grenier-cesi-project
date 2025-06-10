<?php

use PHPUnit\Framework\TestCase;

class ProductShowTest extends TestCase
{
    public function testView()
    {
        ob_start();
        include './App/Views/Product/Show.html';
        $output = ob_get_clean();

        // Assertion 1: Check if the CSS class "content-wrapper" exists
        $this->assertStringContainsString('class="content-wrapper single-article mt-40"', $output);

        // Assertion 2: Check if the HTML contains a specific <h1> element
        $this->assertStringContainsString('<h4>Description:</h4>', $output);

        
    }
}