<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $productModel = new \App\Models\ProductModel();
        
        // Dummy products
        $products = [
            // Action Figures
            [
                'category_id' => 1,
                'name' => 'Superhero Action Figure - Captain Justice',
                'slug' => 'superhero-action-figure-captain-justice',
                'description' => 'Highly detailed superhero action figure with 20 points of articulation. Includes accessories and display stand.',
                'price' => 89.99,
                'stock' => 25,
                'is_featured' => 1,
                'image' => 'captain_justice.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'category_id' => 1,
                'name' => 'Robot Warrior Transformer',
                'slug' => 'robot-warrior-transformer',
                'description' => 'Transforms from robot to vehicle in 30 steps. Features LED lights and sound effects.',
                'price' => 124.99,
                'stock' => 15,
                'is_featured' => 1,
                'image' => 'robot_warrior.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            
            // Educational Toys
            [
                'category_id' => 2,
                'name' => 'Interactive Learning Tablet',
                'slug' => 'interactive-learning-tablet',
                'description' => 'Kid-friendly tablet with educational games, stories, and creative activities. Ages 3-8.',
                'price' => 79.99,
                'stock' => 30,
                'is_featured' => 0,
                'image' => 'learning_tablet.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'category_id' => 2,
                'name' => 'Science Experiment Kit',
                'slug' => 'science-experiment-kit',
                'description' => '50+ safe science experiments for kids. Includes microscope, test tubes, and instruction manual.',
                'price' => 54.99,
                'stock' => 20,
                'is_featured' => 0,
                'image' => 'science_kit.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            
            // Building Blocks
            [
                'category_id' => 3,
                'name' => 'Deluxe Building Blocks Set - 500 Pieces',
                'slug' => 'deluxe-building-blocks-set-500-pieces',
                'description' => 'Premium quality building blocks with 500 pieces in various colors and shapes. Compatible with major brands.',
                'price' => 45.99,
                'stock' => 40,
                'is_featured' => 1,
                'image' => 'building_blocks.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'category_id' => 3,
                'name' => 'Castle Building Kit',
                'slug' => 'castle-building-kit',
                'description' => 'Build your own medieval castle with 300+ pieces. Includes knights, horses, and accessories.',
                'price' => 34.99,
                'stock' => 25,
                'is_featured' => 0,
                'image' => 'castle_kit.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            
            // Dolls & Plushies
            [
                'category_id' => 4,
                'name' => 'Fashion Doll - Princess Collection',
                'slug' => 'fashion-doll-princess-collection',
                'description' => 'Beautiful fashion doll with multiple outfits and accessories. Ages 3+.',
                'price' => 29.99,
                'stock' => 35,
                'is_featured' => 0,
                'image' => 'fashion_doll.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'category_id' => 4,
                'name' => 'Giant Teddy Bear - 24 inch',
                'slug' => 'giant-teddy-bear-24-inch',
                'description' => 'Super soft and cuddly teddy bear. Perfect for hugs and decoration.',
                'price' => 39.99,
                'stock' => 20,
                'is_featured' => 1,
                'image' => 'teddy_bear.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            
            // Vehicles & Remote Control
            [
                'category_id' => 5,
                'name' => 'Remote Control Racing Car - 1:10 Scale',
                'slug' => 'remote-control-racing-car-1-10-scale',
                'description' => 'High-speed RC car with 2.4GHz control. Reaches speeds up to 30mph. Includes rechargeable battery.',
                'price' => 149.99,
                'stock' => 12,
                'is_featured' => 1,
                'image' => 'rc_car.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'category_id' => 5,
                'name' => 'Construction Vehicle Set',
                'slug' => 'construction-vehicle-set',
                'description' => 'Set of 5 construction vehicles including excavator, bulldozer, and dump truck. Friction powered.',
                'price' => 24.99,
                'stock' => 30,
                'is_featured' => 0,
                'image' => 'construction_set.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            
            // Board Games & Puzzles
            [
                'category_id' => 6,
                'name' => 'Strategy Board Game - Kingdom Quest',
                'slug' => 'strategy-board-game-kingdom-quest',
                'description' => 'Epic strategy game for 2-4 players. Build your kingdom and conquer your foes. Ages 12+.',
                'price' => 44.99,
                'stock' => 18,
                'is_featured' => 0,
                'image' => 'strategy_game.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'category_id' => 6,
                'name' => '1000 Piece Jigsaw Puzzle - Landscapes',
                'slug' => '1000-piece-jigsaw-puzzle-landscapes',
                'description' => 'Beautiful landscape jigsaw puzzle. High-quality pieces with vibrant colors.',
                'price' => 19.99,
                'stock' => 25,
                'is_featured' => 0,
                'image' => 'jigsaw_puzzle.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            
            // Arts & Crafts
            [
                'category_id' => 7,
                'name' => 'Deluxe Art Set - 150 Pieces',
                'slug' => 'deluxe-art-set-150-pieces',
                'description' => 'Complete art set with markers, colored pencils, paints, brushes, and more. Comes in carrying case.',
                'price' => 34.99,
                'stock' => 22,
                'is_featured' => 0,
                'image' => 'art_set.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'category_id' => 7,
                'name' => 'DIY Jewelry Making Kit',
                'slug' => 'diy-jewelry-making-kit',
                'description' => 'Create your own bracelets, necklaces, and earrings. Includes beads, strings, and tools.',
                'price' => 24.99,
                'stock' => 28,
                'is_featured' => 0,
                'image' => 'jewelry_kit.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            
            // Outdoor & Sports
            [
                'category_id' => 8,
                'name' => 'Kids Soccer Ball - Size 3',
                'slug' => 'kids-soccer-ball-size-3',
                'description' => 'Professional quality soccer ball for kids. Durable and weather resistant.',
                'price' => 22.99,
                'stock' => 35,
                'is_featured' => 0,
                'image' => 'soccer_ball.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'category_id' => 8,
                'name' => 'Flying Disc - Professional Grade',
                'slug' => 'flying-disc-professional-grade',
                'description' => 'High-quality flying disc perfect for park play and competitions. 175g weight.',
                'price' => 14.99,
                'stock' => 40,
                'is_featured' => 0,
                'image' => 'flying_disc.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            
            // Electronic Toys
            [
                'category_id' => 9,
                'name' => 'Electronic Learning Robot',
                'slug' => 'electronic-learning-robot',
                'description' => 'Interactive robot that teaches coding, math, and science. Voice activated and programmable.',
                'price' => 99.99,
                'stock' => 15,
                'is_featured' => 1,
                'image' => 'learning_robot.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'category_id' => 9,
                'name' => 'Digital Walkie Talkies - Pair',
                'slug' => 'digital-walkie-talkies-pair',
                'description' => 'Long-range digital walkie talkies with clear sound. Built-in flashlight and belt clips.',
                'price' => 39.99,
                'stock' => 20,
                'is_featured' => 0,
                'image' => 'walkie_talkies.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            
            // Musical Instruments
            [
                'category_id' => 10,
                'name' => 'Kids Electronic Keyboard - 61 Keys',
                'slug' => 'kids-electronic-keyboard-61-keys',
                'description' => 'Electronic keyboard with 61 keys, multiple instrument sounds, and built-in songs. Includes microphone.',
                'price' => 69.99,
                'stock' => 18,
                'is_featured' => 0,
                'image' => 'electronic_keyboard.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'category_id' => 10,
                'name' => 'Junior Drum Set',
                'slug' => 'junior-drum-set',
                'description' => 'Complete drum set for kids including bass drum, snare, tom-toms, cymbals, and drumsticks.',
                'price' => 119.99,
                'stock' => 10,
                'is_featured' => 0,
                'image' => 'drum_set.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        foreach ($products as $product) {
            $productModel->insert($product);
        }

        echo "ProductSeeder: Inserted " . count($products) . " products\n";
    }
}
