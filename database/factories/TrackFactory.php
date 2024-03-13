<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Track>
 */
class TrackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'starting_location' => fake()->city(),
            'destination_location' => fake()->city(),
            // 'geojson' => "{'type': 'LineString', 'coordinates': []}",
            'geojson' => '{
                "type": "LineString",
                "coordinates": [
                    [
                        8.04331,
                        47.36773
                    ],
                    [
                        8.18147,
                        47.38487
                    ],
                    [
                        8.08007,
                        47.40025
                    ]
                ]
            }',
        ];
    }
}
