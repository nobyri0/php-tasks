<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use App\Repository\OrderRepository;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class BookstoreController extends AbstractController
{
    #[Route('/bookstore', name: 'bookstore_index')]
    public function index(
        CustomerRepository $customerRepo,
        OrderRepository $orderRepo,
        BookRepository $bookRepo,
        EntityManagerInterface $em
    ): Response {
        // 1. Список клиентов с количеством заказов
        $customersWithOrderCount = $em->createQuery(
            'SELECT c.id, c.name, c.email, COUNT(o.id) as orderCount
             FROM App\Entity\Customer c
             LEFT JOIN c.orders o
             GROUP BY c.id
             ORDER BY orderCount DESC'
        )->getResult();

        // 2. Все заказы с суммами
        $ordersWithTotals = $em->createQuery(
            'SELECT o.id, c.name as customerName, o.orderDate, o.totalAmount
             FROM App\Entity\Order o
             JOIN o.customer c
             ORDER BY o.orderDate DESC'
        )->getResult();

        // 3. Топ-3 клиента
        $topCustomers = $em->createQuery(
            'SELECT c.id, c.name, c.email, SUM(o.totalAmount) as totalSpent, COUNT(o.id) as orderCount
             FROM App\Entity\Customer c
             JOIN c.orders o
             GROUP BY c.id
             ORDER BY totalSpent DESC'
        )->setMaxResults(3)->getResult();

        // 4. Средняя сумма заказа
        $averageStats = $em->createQuery(
            'SELECT AVG(o.totalAmount) as avgAmount, COUNT(o.id) as totalOrders,
                    MIN(o.totalAmount) as minAmount, MAX(o.totalAmount) as maxAmount
             FROM App\Entity\Order o'
        )->getSingleResult();

        // 5. Самая дорогая книга
        $mostExpensiveBook = $bookRepo->findOneBy([], ['price' => 'DESC']);

        return $this->render('bookstore/index.html.twig', [
            'customersWithOrderCount' => $customersWithOrderCount,
            'ordersWithTotals' => $ordersWithTotals,
            'topCustomers' => $topCustomers,
            'averageStats' => $averageStats,
            'mostExpensiveBook' => $mostExpensiveBook,
        ]);
    }
}