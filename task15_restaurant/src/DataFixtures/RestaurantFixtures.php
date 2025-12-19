<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Dish;
use App\Entity\RestaurantOrder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RestaurantFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Клиенты
        $clients = [];
        $clientsData = [
            ['name' => 'Иван Петров', 'phone' => '+7-999-123-45-67'],
            ['name' => 'Мария Сидорова', 'phone' => '+7-999-234-56-78'],
            ['name' => 'Алексей Козлов', 'phone' => '+7-999-345-67-89'],
        ];

        foreach ($clientsData as $data) {
            $client = new Client();
            $client->setName($data['name'])->setPhone($data['phone']);
            $manager->persist($client);
            $clients[] = $client;
        }

        // Блюда
        $dishes = [];
        $dishesData = [
            ['name' => 'Борщ', 'price' => '350.00', 'category' => 'Супы'],
            ['name' => 'Стейк рибай', 'price' => '1500.00', 'category' => 'Мясо'],
            ['name' => 'Паста карбонара', 'price' => '550.00', 'category' => 'Паста'],
            ['name' => 'Цезарь с курицей', 'price' => '450.00', 'category' => 'Салаты'],
            ['name' => 'Тирамису', 'price' => '320.00', 'category' => 'Десерты'],
        ];

        foreach ($dishesData as $data) {
            $dish = new Dish();
            $dish->setName($data['name'])
                ->setPrice($data['price'])
                ->setCategory($data['category']);
            $manager->persist($dish);
            $dishes[] = $dish;
        }

        // Заказы
        $order1 = new RestaurantOrder();
        $order1->setClient($clients[0])
            ->addDish($dishes[0])
            ->addDish($dishes[3]);
        $order1->calculateTotal();
        $manager->persist($order1);

        $manager->flush();
    }
}