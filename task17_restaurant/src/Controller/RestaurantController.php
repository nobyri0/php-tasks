<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Dish;
use App\Entity\RestaurantOrder;
use App\Entity\OrderFile;
use App\Repository\ClientRepository;
use App\Repository\DishRepository;
use App\Repository\RestaurantOrderRepository;
use App\Service\FileUploader;
use App\Service\OrderExcelExporter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;

class RestaurantController extends AbstractController
{
    private FileUploader $dishImageUploader;
    private FileUploader $orderFileUploader;
    private string $dishImagesDirectory;
    private string $orderFilesDirectory;

    public function __construct(
        FileUploader $dishImageUploader,
        FileUploader $orderFileUploader,
        string $dishImagesDirectory,
        string $orderFilesDirectory
    ) {
        $this->dishImageUploader = $dishImageUploader;
        $this->orderFileUploader = $orderFileUploader;
        $this->dishImagesDirectory = $dishImagesDirectory;
        $this->orderFilesDirectory = $orderFilesDirectory;
    }

    #[Route('/restaurant', name: 'restaurant_index')]
    public function index(
        ClientRepository $clientRepo,
        DishRepository $dishRepo,
        RestaurantOrderRepository $orderRepo
    ): Response {
        $user = $this->getUser();
        $isAuthenticatedClient = $user instanceof Client;

        return $this->render('restaurant/index.html.twig', [
            'clients' => $clientRepo->findAll(),
            'dishes' => $dishRepo->findAll(),
            'orders' => $orderRepo->findBy([], ['orderDate' => 'DESC'], 10),
            'isAuthenticatedClient' => $isAuthenticatedClient,
            'currentClient' => $isAuthenticatedClient ? $user : null,
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
        $client->setPassword('$2y$13$EmptyPasswordHash');
        
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

    #[Route('/restaurant/dish/add', name: 'restaurant_dish_add', methods: ['POST'])]
    public function addDish(Request $request, EntityManagerInterface $em): Response
    {
        $name = trim($request->request->get('name'));
        $price = $request->request->get('price');
        $category = trim($request->request->get('category'));
        
        /** @var UploadedFile $imageFile */
        $imageFile = $request->files->get('image');

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
        if ($imageFile) {
            $allowedMimeTypes = ['image/jpeg', 'image/png'];
            if (!in_array($imageFile->getMimeType(), $allowedMimeTypes)) {
                $this->addFlash('error', 'Допустимы только JPG и PNG');
                return $this->redirectToRoute('restaurant_index');
            }
            
            try {
                $imageFileName = $this->dishImageUploader->upload($imageFile);
                $dish->setImagePath($imageFileName);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Ошибка загрузки изображения: ' . $e->getMessage());
                return $this->redirectToRoute('restaurant_index');
            }
        }
        
        $em->persist($dish);
        $em->flush();

        $this->addFlash('success', 'Блюдо добавлено');
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
        
        /** @var UploadedFile $imageFile */
        $imageFile = $request->files->get('image');
        $deleteImage = $request->request->get('delete_image');

        if (empty($name)) {
            $this->addFlash('error', 'Название блюда обязательно');
            return $this->redirectToRoute('restaurant_index');
        }

        if ($price <= 0) {
            $this->addFlash('error', 'Цена должна быть больше 0');
            return $this->redirectToRoute('restaurant_index');
        }

        $dish->setName($name)->setPrice($price)->setCategory($category);
        if ($deleteImage && $dish->getImagePath()) {
            $oldImagePath = $this->dishImagesDirectory . '/' . $dish->getImagePath();
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
            $dish->setImagePath(null);
        }
        if ($imageFile) {
            $allowedMimeTypes = ['image/jpeg', 'image/png'];
            if (!in_array($imageFile->getMimeType(), $allowedMimeTypes)) {
                $this->addFlash('error', 'Допустимы только JPG и PNG');
                return $this->redirectToRoute('restaurant_index');
            }
            if ($dish->getImagePath()) {
                $oldImagePath = $this->dishImagesDirectory . '/' . $dish->getImagePath();
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            try {
                $imageFileName = $this->dishImageUploader->upload($imageFile);
                $dish->setImagePath($imageFileName);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Ошибка загрузки изображения: ' . $e->getMessage());
                return $this->redirectToRoute('restaurant_index');
            }
        }
        
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

        // Удаляем файл изображения
        if ($dish->getImagePath()) {
            $imagePath = $this->dishImagesDirectory . '/' . $dish->getImagePath();
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $em->remove($dish);
        $em->flush();

        $this->addFlash('success', 'Блюдо удалено');
        return $this->redirectToRoute('restaurant_index');
    }

    #[Route('/restaurant/order/add', name: 'restaurant_order_add', methods: ['POST'])]
    public function addOrder(
        Request $request,
        EntityManagerInterface $em,
        ClientRepository $clientRepo,
        DishRepository $dishRepo
    ): Response {
        $user = $this->getUser();
        $isAuthenticatedClient = $user instanceof Client;

        if ($isAuthenticatedClient) {
            $client = $user;
        } else {
            $clientId = $request->request->get('client_id');
            
            if (!$clientId) {
                $this->addFlash('error', 'Выберите клиента');
                return $this->redirectToRoute('restaurant_index');
            }
            
            $client = $clientRepo->find($clientId);
            
            if (!$client) {
                $this->addFlash('error', 'Клиент не найден');
                return $this->redirectToRoute('restaurant_index');
            }
        }

        $dishIds = $request->request->all('dish_ids');

        if (empty($dishIds)) {
            $this->addFlash('error', 'Выберите хотя бы одно блюдо');
            
            if ($isAuthenticatedClient) {
                return $this->redirectToRoute('client_orders');
            }
            return $this->redirectToRoute('restaurant_index');
        }

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
        
        if ($isAuthenticatedClient) {
            return $this->redirectToRoute('client_orders');
        }
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

    #[Route('/restaurant/order/{id}/upload', name: 'restaurant_order_upload', methods: ['POST'])]
    public function uploadOrderFile(
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

        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('file');
        
        if (!$uploadedFile) {
            $this->addFlash('error', 'Файл не выбран');
            return $this->redirectToRoute('restaurant_index');
        }

        $allowedMimeTypes = [
            'application/pdf',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'text/plain',
            'image/jpeg',
            'image/png'
        ];
        
        if (!in_array($uploadedFile->getMimeType(), $allowedMimeTypes)) {
            $this->addFlash('error', 'Недопустимый тип файла. Разрешены: PDF, DOCX, TXT, JPG, PNG');
            return $this->redirectToRoute('restaurant_index');
        }
        
        if ($uploadedFile->getSize() > 5242880) {
            $this->addFlash('error', 'Файл слишком большой (максимум 5MB)');
            return $this->redirectToRoute('restaurant_index');
        }

        try {
            $fileName = $this->orderFileUploader->upload($uploadedFile);
            
            $orderFile = new OrderFile();
            $orderFile->setFileName($fileName);
            $orderFile->setOriginalName($uploadedFile->getClientOriginalName());
            $orderFile->setMimeType($uploadedFile->getMimeType());
            $orderFile->setFileSize($uploadedFile->getSize());
            $orderFile->setRestaurantOrder($order);
            
            $em->persist($orderFile);
            $em->flush();
            
            $this->addFlash('success', 'Файл загружен');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Ошибка загрузки файла: ' . $e->getMessage());
        }

        return $this->redirectToRoute('restaurant_index');
    }

    #[Route('/restaurant/order/{id}/files', name: 'restaurant_order_files_json')]
    public function getOrderFilesJson(
        int $id,
        RestaurantOrderRepository $orderRepo
    ): Response {
        $order = $orderRepo->find($id);
        
        if (!$order) {
            return $this->json(['error' => 'Order not found'], 404);
        }

        $filesData = [];
        foreach ($order->getFiles() as $file) {
            $filesData[] = [
                'id' => $file->getId(),
                'originalName' => $file->getOriginalName(),
                'fileSize' => $file->getFileSize(),
                'uploadedAt' => $file->getUploadedAt()->format('d.m.Y H:i'),
            ];
        }

        return $this->json($filesData);
    }

    #[Route('/restaurant/order/file/{id}/download', name: 'restaurant_order_file_download')]
    public function downloadOrderFile(
        int $id,
        EntityManagerInterface $em
    ): Response {
        $orderFile = $em->getRepository(OrderFile::class)->find($id);
        
        if (!$orderFile) {
            throw $this->createNotFoundException('Файл не найден');
        }

        $filePath = $this->orderFilesDirectory . '/' . $orderFile->getFileName();
        
        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('Файл не существует на сервере');
        }

        $response = new BinaryFileResponse($filePath);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $orderFile->getOriginalName()
        );

        return $response;
    }

    #[Route('/restaurant/order/file/{id}/delete', name: 'restaurant_order_file_delete', methods: ['POST'])]
    public function deleteOrderFile(
        int $id,
        EntityManagerInterface $em
    ): Response {
        $orderFile = $em->getRepository(OrderFile::class)->find($id);
        
        if (!$orderFile) {
            $this->addFlash('error', 'Файл не найден');
            return $this->redirectToRoute('restaurant_index');
        }

        $filePath = $this->orderFilesDirectory . '/' . $orderFile->getFileName();
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $em->remove($orderFile);
        $em->flush();

        $this->addFlash('success', 'Файл удалён');
        return $this->redirectToRoute('restaurant_index');
    }

    #[Route('/restaurant/orders/export', name: 'restaurant_orders_export')]
    public function exportOrders(
        RestaurantOrderRepository $orderRepo,
        OrderExcelExporter $exporter
    ): Response {
        $orders = $orderRepo->findBy([], ['orderDate' => 'DESC']);
        
        if (empty($orders)) {
            $this->addFlash('error', 'Нет заказов для экспорта');
            return $this->redirectToRoute('restaurant_index');
        }
        
        try {
            $tempFile = $exporter->export($orders);
            
            $fileName = 'orders_' . (new \DateTime())->format('Y-m-d_H-i-s') . '.xlsx';
            
            $response = new BinaryFileResponse($tempFile);
            $response->setContentDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                $fileName
            );
            
            $response->deleteFileAfterSend(true);
            
            return $response;
            
        } catch (\Exception $e) {
            $this->addFlash('error', 'Ошибка экспорта: ' . $e->getMessage());
            return $this->redirectToRoute('restaurant_index');
        }
    }
}