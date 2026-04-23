<?php

test('guests can visit the dashboard', function () {
    $response = $this->get(route('dashboard'));

    $response->assertOk();
});

test('the home route renders the dashboard', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
});
