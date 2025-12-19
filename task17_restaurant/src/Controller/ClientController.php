<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Repository\RestaurantOrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ClientController extends AbstractController
{
    #[Route('/client/register', name: 'client_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em,
        ClientRepository $clientRepo
    ): Response {
        // Если пользователь уже залогинен, перенаправляем на заказы
        if ($this->getUser()) {
            return $this->redirectToRoute('client_orders');
        }

        $error = null;

        if ($request->isMethod('POST')) {
            $name = trim($request->request->get('name'));
            $phone = trim($request->request->get('phone'));
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirm_password');

            // Валидация
            if (empty($name) || empty($phone) || empty($password)) {
                $error = 'Все поля обязательны для заполнения';
            } elseif (strlen($password) < 6) {
                $error = 'Пароль должен содержать минимум 6 символов';
            } elseif ($password !== $confirmPassword) {
                $error = 'Пароли не совпадают';
            } elseif ($clientRepo->findOneBy(['phone' => $phone])) {
                $error = 'Клиент с таким номером телефона уже зарегистрирован';
            } else {
                // Создаем нового клиента
                $client = new Client();
                $client->setName($name);
                $client->setPhone($phone);
                
                // Хешируем пароль
                $hashedPassword = $passwordHasher->hashPassword($client, $password);
                $client->setPassword($hashedPassword);

                $em->persist($client);
                $em->flush();

                $this->addFlash('success', 'Регистрация успешна! Теперь вы можете войти.');
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('client/register.html.twig', [
            'error' => $error,
        ]);
    }

    #[Route('/client/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Если пользователь уже залогинен, перенаправляем на заказы
        if ($this->getUser()) {
            return $this->redirectToRoute('client_orders');
        }

        // Получаем ошибку входа, если она есть
        $error = $authenticationUtils->getLastAuthenticationError();
        
        // Последний введенный телефон
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('client/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/client/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Этот метод может быть пустым - он будет перехвачен firewall
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/client/orders', name: 'client_orders')]
    public function orders(RestaurantOrderRepository $orderRepo): Response
    {
        /** @var Client $user */
        $user = $this->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Получаем заказы текущего клиента
        $orders = $orderRepo->findBy(
            ['client' => $user],
            ['orderDate' => 'DESC']
        );

        return $this->render('client/orders.html.twig', [
            'client' => $user,
            'orders' => $orders,
        ]);
    }
}