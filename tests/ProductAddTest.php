<?php

use PHPUnit\Framework\TestCase;

class ProductAddTest extends TestCase
{
    public function testView()
    {
        ob_start();
        include './App/Views/Product/Add.html';
        $output = ob_get_clean();

        // Assertion 1: Check if the CSS class "content-wrapper" exists
        $this->assertStringContainsString('class="content-wrapper upload-page edit-page"', $output);

        // Assertion 2: Check if the HTML contains a specific <h1> element
        $this->assertStringContainsString('<h1>Que souhaitez-vous donner ?</h1>', $output);

        // Assertion 3: Check if the HTML contains a specific <form> element
        $this->assertStringContainsString('<form action="" method="post" enctype="multipart/form-data">', $output);

        // Assertion 4: Check if the HTML contains a specific <a> element
        $this->assertStringContainsString('<a href="#">conditions générales</a>', $output);
    }
}