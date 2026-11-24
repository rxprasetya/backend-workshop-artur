<?php

namespace Tests\Feature;

use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AboutTest extends TestCase
{
    public function test_about_page_can_be_rendered(): void
    {
        $response = $this->get(route('about'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('About')
            ->has('appName')
            ->has('laravelVersion')
            ->has('features', 3)
        );
    }
}

