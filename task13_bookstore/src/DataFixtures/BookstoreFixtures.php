<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Book;
use App\Entity\Order;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookstoreFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Создаём клиентов
        $customers = [];
        $customerData = [
            ['name' => 'Иван Иванов', 'email' => 'ivan@example.com'],
            ['name' => 'Мария Петрова', 'email' => 'maria@example.com'],
            ['name' => 'Алексей Сидоров', 'email' => 'alexey@example.com'],
            ['name' => 'Елена Смирнова', 'email' => 'elena@example.com'],
        ];

        foreach ($customerData as $data) {
            $customer = new Customer();
            $customer->setName($data['name']);
            $customer->setEmail($data['email']);
            $manager->persist($customer);
            $customers[] = $customer;
        }

        // Создаём книги
        $books = [];
        $bookData = [
            ['title' => 'Война и мир', 'price' => '1200.00'],
            ['title' => 'Преступление и наказание', 'price' => '800.00'],
            ['title' => 'Мастер и Маргарита', 'price' => '950.00'],
            ['title' => '1984', 'price' => '600.00'],
            ['title' => 'Анна Каренина', 'price' => '1100.00'],
        ];

        foreach ($bookData as $data) {
            $book = new Book();
            $book->setTitle($data['title']);
            $book->setPrice($data['price']);
            $manager->persist($book);
            $books[] = $book;
        }

        // Создаём заказы
        // Заказ 1: Иван покупает 3 книги
        $order1 = new Order();
        $order1->setCustomer($customers[0]);
        $order1->addBook($books[0]);
        $order1->addBook($books[1]);
        $order1->addBook($books[2]);
        $order1->calculateTotal();
        $manager->persist($order1);

        // Заказ 2: Мария покупает 2 книги
        $order2 = new Order();
        $order2->setCustomer($customers[1]);
        $order2->addBook($books[3]);
        $order2->addBook($books[2]);
        $order2->calculateTotal();
        $manager->persist($order2);

        // Заказ 3: Иван делает ещё заказ
        $order3 = new Order();
        $order3->setCustomer($customers[0]);
        $order3->addBook($books[4]);
        $order3->calculateTotal();
        $manager->persist($order3);

        $manager->flush();
    }
}