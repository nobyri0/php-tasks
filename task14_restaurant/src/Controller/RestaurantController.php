<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Dish;
use App\Entity\RestaurantOrder;
use App\Repository\ClientRepository;
use App\Repository\DishRepository;
use App\Repository\RestaurantOrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RestaurantController extends AbstractController
{
    #[Route('/restaurant', name: 'restaurant_index')]
    public function index(
        ClientRepository $clientRepo,
        DishRepository $dishRepo,
        RestaurantOrderRepository $orderRepo
    ): Response {
        return $this->render('restaurant/index.html.twig', [
            'clients' => $clientRepo->findAll(),
            'dishes' => $dishRepo->findAll(),
            'orders' => $orderRepo->findBy([], ['orderDate' => 'DESC'], 10),
        ]);
    }

    #[Route('/restaurant/client/add', name: 'restaurant_client_add', methods: ['POST'])]
    public function addClient(Request $request, EntityManagerInterface $em): Response
    {
        $name = trim($request->request->get('name'));
        $phone = trim($request->request->get('phone'));

        if (empty($name)) {
            $this->addFlash('error', 'Имя клиента обязательно');
            return $this->redirectToRoute('restaurant_index');
        }

        $client = new Client();
        $client->setName($name)->setPhone($phone);
        
        $em->persist($client);
        $em->flush();

        $this->addFlash('success', 'Клиент добавлен');
        return $this->redirectToRoute('restaurant_index');
    }
    #[Route('/restaurant/client/edit/{id}', name: 'restaurant_client_edit', methods: ['POST'])]
    public function editClient(
        int $id,
        Request $request,
        ClientRepository $clientRepo,
        EntityManagerInterface $em
    ): Response {
        $client = $clientRepo->find($id);
        
        if (!$client) {
            $this->addFlash('error', 'Клиент не найден');
            return $this->redirectToRoute('restaurant_index');
        }

        $name = trim($request->request->get('name'));
        $phone = trim($request->request->get('phone'));

        if (empty($name)) {
            $this->addFlash('error', 'Имя клиента обязательно');
            return $this->redirectToRoute('restaurant_index');
        }

        $client->setName($name)->setPhone($phone);
        $em->flush();

        $this->addFlash('success', 'Клиент обновлён');
        return $this->redirectToRoute('restaurant_index');
    }
    #[Route('/restaurant/client/delete/{id}', name: 'restaurant_client_delete', methods: ['POST'])]
    public function deleteClient(
        int $id,
        ClientRepository $clientRepo,
        EntityManagerInterface $em
    ): Response {
        $client = $clientRepo->find($id);
        
        if (!$client) {
            $this->addFlash('error', 'Клиент не найден');
            return $this->redirectToRoute('restaurant_index');
        }

        $em->remove($client);
        $em->flush();

        $this->addFlash('success', 'Клиент удалён');
        return $this->redirectToRoute('restaurant_index');
    }
    #[Route('/restaurant/dish/edit/{id}', name: 'restaurant_dish_edit', methods: ['POST'])]
    public function editDish(
        int $id,
        Request $request,
        DishRepository $dishRepo,
        EntityManagerInterface $em
    ): Response {
        $dish = $dishRepo->find($id);
        
        if (!$dish) {
            $this->addFlash('error', 'Блюдо не найдено');
            return $this->redirectToRoute('restaurant_index');
        }

        $name = trim($request->request->get('name'));
        $price = $request->request->get('price');
        $category = trim($request->request->get('category'));

        if (empty($name)) {
            $this->addFlash('error', 'Название блюда обязательно');
            return $this->redirectToRoute('restaurant_index');
        }

        if ($price <= 0) {
            $this->addFlash('error', 'Цена должна быть больше 0');
            return $this->redirectToRoute('restaurant_index');
        }

        $dish->setName($name)->setPrice($price)->setCategory($category);
        $em->flush();

        $this->addFlash('success', 'Блюдо обновлено');
        return $this->redirectToRoute('restaurant_index');
    }
    #[Route('/restaurant/dish/delete/{id}', name: 'restaurant_dish_delete', methods: ['POST'])]
    public function deleteDish(
        int $id,
        DishRepository $dishRepo,
        EntityManagerInterface $em
    ): Response {
        $dish = $dishRepo->find($id);
        
        if (!$dish) {
            $this->addFlash('error', 'Блюдо не найдено');
            return $this->redirectToRoute('restaurant_index');
        }

        $em->remove($dish);
        $em->flush();

        $this->addFlash('success', 'Блюдо удалено');
        return $this->redirectToRoute('restaurant_index');
    }
     #[Route('/restaurant/order/status/{id}', name: 'restaurant_order_status', methods: ['POST'])]
    public function updateOrderStatus(
        int $id,
        Request $request,
        RestaurantOrderRepository $orderRepo,
        EntityManagerInterface $em
    ): Response {
        $order = $orderRepo->find($id);
        
        if (!$order) {
            $this->addFlash('error', 'Заказ не найден');
            return $this->redirectToRoute('restaurant_index');
        }

        $status = $request->request->get('status');
        $validStatuses = ['pending', 'preparing', 'completed', 'cancelled'];
        
        if (!in_array($status, $validStatuses)) {
            $this->addFlash('error', 'Неверный статус');
            return $this->redirectToRoute('restaurant_index');
        }

        $order->setStatus($status);
        $em->flush();

        $this->addFlash('success', 'Статус заказа обновлён');
        return $this->redirectToRoute('restaurant_index');
    }
    #[Route('/restaurant/order/delete/{id}', name: 'restaurant_order_delete', methods: ['POST'])]
    public function deleteOrder(
        int $id,
        RestaurantOrderRepository $orderRepo,
        EntityManagerInterface $em
    ): Response {
        $order = $orderRepo->find($id);
        
        if (!$order) {
            $this->addFlash('error', 'Заказ не найден');
            return $this->redirectToRoute('restaurant_index');
        }

        $em->remove($order);
        $em->flush();

        $this->addFlash('success', 'Заказ удалён');
        return $this->redirectToRoute('restaurant_index');
    }
    #[Route('/restaurant/dish/add', name: 'restaurant_dish_add', methods: ['POST'])]
    public function addDish(Request $request, EntityManagerInterface $em): Response
    {
        $name = trim($request->request->get('name'));
        $price = $request->request->get('price');
        $category = trim($request->request->get('category'));

        if (empty($name)) {
            $this->addFlash('error', 'Название блюда обязательно');
            return $this->redirectToRoute('restaurant_index');
        }

        if ($price <= 0) {
            $this->addFlash('error', 'Цена должна быть больше 0');
            return $this->redirectToRoute('restaurant_index');
        }

        $dish = new Dish();
        $dish->setName($name)->setPrice($price)->setCategory($category);
        
        $em->persist($dish);
        $em->flush();

        $this->addFlash('success', 'Блюдо добавлено');
        return $this->redirectToRoute('restaurant_index');
    }
    #[Route('/restaurant/order/add', name: 'restaurant_order_add', methods: ['POST'])]
    public function addOrder(
        Request $request,
        EntityManagerInterface $em,
        ClientRepository $clientRepo,
        DishRepository $dishRepo
    ): Response {
        $clientId = $request->request->get('client_id');
        $dishIds = $request->request->all('dish_ids');

        if (!$clientId) {
            $this->addFlash('error', 'Выберите клиента');
            return $this->redirectToRoute('restaurant_index');
        }

        if (empty($dishIds)) {
            $this->addFlash('error', 'Выберите хотя бы одно блюдо');
            return $this->redirectToRoute('restaurant_index');
        }

        $client = $clientRepo->find($clientId);
        $order = new RestaurantOrder();
        $order->setClient($client);

        foreach ($dishIds as $dishId) {
            $dish = $dishRepo->find($dishId);
            if ($dish) {
                $order->addDish($dish);
            }
        }

        $order->calculateTotal();
        $em->persist($order);
        $em->flush();

        $this->addFlash('success', 'Заказ создан! Номер: #' . $order->getId());
        return $this->redirectToRoute('restaurant_index');
    }
}