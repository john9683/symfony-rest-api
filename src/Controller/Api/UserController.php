<?php

namespace App\Controller\Api;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
  /**
   * @var string $ROLE_GRANTED
   */
  public static string $ROLE_GRANTED = 'ROLE_API';

  /**
   * @Route("/api/user", methods={"POST"}, name="app_api_register_user")
   * @param Request $request
   * @param UserService $userService
   * @return JsonResponse
   */
  public function registerUser(Request $request, UserService $userService): JsonResponse
  {
    $this->denyAccessUnlessGranted(self::$ROLE_GRANTED);

    if ($userService->isEmailExist($request->toArray()['email'])) {
      return  $this->json([
        'message' => 'Пользователь с таким email уже существует'
      ], 403);
    }

    $response = $userService->registerByApi($request);

    return $this->json([
      'userId' => $response['id'],
      'userName' => $response['name'],
      'userEmail' => $response['email']
    ], 201);
  }

  /**
   * @Route("/api/user/{id}", methods={"GET"}, name="app_api_get_user")
   * @param string $id
   * @param UserService $userService
   * @return JsonResponse
   */
  public function getUserById(string $id, UserService $userService): JsonResponse
  {
    $this->denyAccessUnlessGranted(self::$ROLE_GRANTED);

    if (!$userService->getUserById($id)) {
      return  $this->json([
        'message' => 'Пользователь с таким id не существует'
      ], 404);
    }

    $response = $userService->getUserByApi($id);

    return $this->json([
      'userId' => $response['id'],
      'userName' => $response['name'],
      'userEmail' => $response['email']
    ]);
  }

  /**
   * @Route("/api/user/{id}", methods={"PATCH"}, name="app_api_update_user")
   * @param string $id
   * @param Request $request
   * @param UserService $userService
   * @return JsonResponse
   */
  public function updateUserById(string $id, Request $request, UserService $userService): JsonResponse
  {
    $this->denyAccessUnlessGranted(self::$ROLE_GRANTED);

    if (!$userService->getUserById($id)) {
      return  $this->json([
        'message' => 'Пользователь с таким id не существует'
      ], 404);
    }

    $response = $userService->updateUserByApi($id, $request);

    return $this->json([
      'userId' => $response['id'],
      'userName' => $response['name'],
      'userEmail' => $response['email']
    ]);
  }

  /**
   * @Route("/api/user/{id}", methods={"DELETE"}, name="app_api_delete_user")
   * @param string $id
   * @param UserService $userService
   * @return JsonResponse
   */
  public function deleteUserById(string $id, UserService $userService): JsonResponse
  {
    $this->denyAccessUnlessGranted(self::$ROLE_GRANTED);

    if (!$userService->getUserById($id)) {
      return  $this->json([
        'message' => 'Пользователь с таким id не существует'
      ], 404);
    }

    $userService->deleteUserByApi($id);

    return $this->json([], 204);
  }
}
