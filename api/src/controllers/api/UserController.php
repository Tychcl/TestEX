<?php
namespace Api;

use Classes\Validate;
use Core\JWToken;
use Core\Response;
use Core\Route;
use Exception;
use Models\Users;
use Models\UsersQuery;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Volunteer API',
    description: 'API для волонтёрского приложения. Управление пользователями, задачами, проектами и чатами.'
)]
#[OA\Server(
    url: 'http://localhost:8080',
    description: 'Локальный сервер'
)]
#[OA\SecurityScheme(
    securityScheme: 'cookieAuth',
    type: 'apiKey',
    in: 'cookie',
    name: 'jwt',
    description: 'Аутентификация через JWT в cookie (HttpOnly)'
)]
#[Route("/api/users")]
class UserController
{
    /**
     * Регистрация нового пользователя
     */
    #[OA\Post(
        path: '/api/users/register',
        summary: 'Регистрация нового пользователя',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['full_name', 'password', 'confirm'],
                properties: [
                    new OA\Property(property: 'full_name', type: 'string', example: 'Иван Иванов'),
                    new OA\Property(property: 'phone', type: 'string', example: '+79001234567'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'ivan@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'secret'),
                    new OA\Property(property: 'confirm', type: 'string', format: 'password', example: 'secret'),
                    new OA\Property(property: 'city', type: 'string', example: 'Пермь'),
                    new OA\Property(property: 'birth_date', type: 'string', format: 'date', example: '2000-05-15'),
                    new OA\Property(property: 'about', type: 'string', example: 'О себе')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Успешная регистрация',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'result', type: 'string', example: 'successful')
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Ошибка валидации (не все параметры, пароли не совпадают, неверный формат)',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 409,
                description: 'Пользователь с таким телефоном или email уже существует',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string')
                    ]
                )
            )
        ]
    )]
    #[Route("/register", "POST", false)]
    public function register($params)
    {
        try {
            // Проверяем обязательные поля
            $required = ['full_name', 'password', 'confirm'];
            if (Validate::checkParams($required, $params)) {
                return new Response(400, ['error' => 'Missing required parameters']);
            }

            // Проверка совпадения паролей
            if ($params['password'] !== $params['confirm']) {
                return new Response(400, ['error' => 'Passwords do not match']);
            }

            // Должен быть указан хотя бы телефон или email
            $phone = isset($params['phone']) ? trim($params['phone']) : null;
            $email = isset($params['email']) ? trim($params['email']) : null;
            if (!$phone && !$email) {
                return new Response(400, ['error' => 'Either phone or email must be provided']);
            }

            // Валидация форматов, если они переданы
            if ($phone && Validate::phone($phone, $r)) {
                return $r;
            }
            if ($email && Validate::email($email, $r)) {
                return $r;
            }

            // Проверка уникальности телефона и email
            if ($email && Validate::findUserByField('Email', $email) || $phone && Validate::findUserByField('Phone', $phone, $r)) {
                return new Response(409, ['error' => 'User with this email or phone already exists']);
            }

            // Валидация даты рождения, если передана
            if (isset($params['birth_date']) && !Validate::date($params['birth_date'], $r)) {
                return $r;
            }

            // Создание пользователя
            $user = new Users();
            $user->setFullName(trim($params['full_name']));
            $user->setPassword(password_hash($params['password'], PASSWORD_DEFAULT));

            if ($phone) {
                $user->setPhone($phone);
            }
            if ($email) {
                $user->setEmail($email);
            }
            if (isset($params['city'])) {
                $user->setCity(trim($params['city']));
            }
            if (isset($params['birth_date'])) {
                $user->setBirthDate($params['birth_date']); // Propel setter ожидает строку или DateTime
            }
            if (isset($params['about'])) {
                $user->setAbout(trim($params['about']));
            }
            // Поля по умолчанию
            $user->setBalance(0.00);
            $user->setAvailabilityStatus('available');
            $user->setIsAdmin(false);
            $user->setIsModerator(false);
            $user->setIsOnline(false);
            $user->setAverageRating(0.00);

            $user->save();

            return new Response(200, ['result' => 'successful']);
        } catch (Exception $ex) {
            return Validate::Ex($ex);
        }
    }

    /**
     * Вход в систему
     */
    #[OA\Post(
        path: '/api/users/signin',
        summary: 'Авторизация пользователя',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['login', 'password'],
                properties: [
                    new OA\Property(property: 'login', type: 'string', example: '+79001234567', description: 'Телефон или email'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'secret')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Успешный вход. В cookie устанавливается JWT, в теле возвращаются данные пользователя',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer'),
                                new OA\Property(property: 'phone', type: 'string', nullable: true),
                                new OA\Property(property: 'email', type: 'string', nullable: true),
                                new OA\Property(property: 'full_name', type: 'string')
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Неверный логин/пароль или отсутствуют параметры',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string')
                    ]
                )
            )
        ]
    )]
    #[Route("/signin", "POST", false)]
    public function signIn($params)
    {
        try {
            $login = $params['login'] ?? null;
            $password = $params['password'] ?? null;

            if (!$login || !$password) {
                return new Response(400, ['error' => 'Login and password required']);
            }

            // Определяем, что пришло: телефон или email
            $user = null;
            $r = new Response();
            if (!Validate::phone($login, $r)) {
                $user = UsersQuery::create()->findOneByPhone($login);
            } elseif (!Validate::email($login, $r)) {
                $user = UsersQuery::create()->findOneByEmail($login);
            } else {
                return new Response(400, ['error' => 'Invalid login format']);
            }

            if (!$user) {
                return new Response(400, ['error' => 'Wrong login or password']);
            }

            if (!password_verify($password, $user->getPassword())) {
                return new Response(400, ['error' => 'Wrong login or password']);
            }

            // Обновляем время последнего визита и статус онлайн
            $user->setLastSeenAt(new \DateTime());
            $user->setIsOnline(true);
            $user->save();

            $payload = [
                'id' => $user->getId(),
                'phone' => $user->getPhone(),
                'email' => $user->getEmail(),
                'full_name' => $user->getFullName()
            ];

            $token = JWToken::generateToken($payload);

            $response = new Response(200, ['success' => $payload]);
            $response->setCook(
                'jwt',
                $token,
                time() + (60 * 60 * 6),
                '/',
                '',
                false,
                true,
                'Strict'
            );

            session_start();
            $_SESSION = $payload;

            return $response;
        } catch (Exception $ex) {
            return Validate::Ex($ex);
        }
    }

    /**
     * Получение профиля текущего пользователя
     */
    #[OA\Get(
        path: '/api/users/me',
        summary: 'Получить данные текущего авторизованного пользователя',
        security: [['cookieAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Данные профиля',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer'),
                        new OA\Property(property: 'full_name', type: 'string'),
                        new OA\Property(property: 'phone', type: 'string', nullable: true),
                        new OA\Property(property: 'email', type: 'string', nullable: true),
                        new OA\Property(property: 'city', type: 'string', nullable: true),
                        new OA\Property(property: 'birth_date', type: 'string', format: 'date', nullable: true),
                        new OA\Property(property: 'about', type: 'string', nullable: true),
                        new OA\Property(property: 'profile_picture', type: 'string', nullable: true),
                        new OA\Property(property: 'vk_id', type: 'string', nullable: true),
                        new OA\Property(property: 'tg_id', type: 'string', nullable: true),
                        new OA\Property(property: 'balance', type: 'number', format: 'float'),
                        new OA\Property(property: 'availability_status', type: 'string', enum: ['available', 'busy', 'offline']),
                        new OA\Property(property: 'is_admin', type: 'boolean'),
                        new OA\Property(property: 'is_moderator', type: 'boolean'),
                        new OA\Property(property: 'last_seen_at', type: 'string', format: 'date-time', nullable: true),
                        new OA\Property(property: 'is_online', type: 'boolean'),
                        new OA\Property(property: 'average_rating', type: 'number', format: 'float'),
                        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time')
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Не авторизован',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Пользователь не найден',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string')
                    ]
                )
            )
        ]
    )]
    #[Route("/me", "GET")]
    public function getCurrentUser()
    {
        try {
            if(!session_status() == PHP_SESSION_NONE){
                session_start();
            }
            
            $userId = $_SESSION['id'] ?? null;
            if (!$userId) {
                return new Response(401, ['error' => 'Unauthorized']);
            }

            $user = UsersQuery::create()->findOneById($userId);
            if (!$user) {
                return new Response(404, ['error' => 'User not found']);
            }

            // Возвращаем все данные профиля (кроме пароля)
            return new Response(200, $this->formatUserProfile($user));
        } catch (Exception $ex) {
            return Validate::Ex($ex);
        }
    }

    /**
     * Получение публичной информации о пользователе по ID
     */
    #[OA\Get(
        path: '/api/users/{id}',
        summary: 'Получить публичные данные пользователя по ID',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer'),
                description: 'ID пользователя'
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Публичные данные пользователя',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer'),
                        new OA\Property(property: 'full_name', type: 'string'),
                        new OA\Property(property: 'city', type: 'string', nullable: true),
                        new OA\Property(property: 'birth_date', type: 'string', format: 'date', nullable: true),
                        new OA\Property(property: 'about', type: 'string', nullable: true),
                        new OA\Property(property: 'profile_picture', type: 'string', nullable: true),
                        new OA\Property(property: 'availability_status', type: 'string', enum: ['available', 'busy', 'offline']),
                        new OA\Property(property: 'average_rating', type: 'number', format: 'float'),
                        new OA\Property(property: 'last_seen_at', type: 'string', format: 'date-time', nullable: true),
                        new OA\Property(property: 'is_online', type: 'boolean')
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'ID не передан',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Пользователь не найден',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string')
                    ]
                )
            )
        ]
    )]
    #[Route("/{id}", "GET")]
    public function getUserById($params)
    {
        try {
            $id = $params['id'] ?? null;
            if (!$id) {
                return new Response(400, ['error' => 'User ID required']);
            }

            $user = UsersQuery::create()->findOneById($id);
            if (!$user) {
                return new Response(404, ['error' => 'User not found']);
            }

            // Публичные данные (без конфиденциальной информации)
            $publicData = [
                'id' => $user->getId(),
                'full_name' => $user->getFullName(),
                'city' => $user->getCity(),
                'birth_date' => $user->getBirthDate() ? $user->getBirthDate()->format('Y-m-d') : null,
                'about' => $user->getAbout(),
                'profile_picture' => $user->getProfilePicture(),
                'availability_status' => $user->getAvailabilityStatus(),
                'average_rating' => $user->getAverageRating(),
                'last_seen_at' => $user->getLastSeenAt() ? $user->getLastSeenAt()->format('Y-m-d H:i:s') : null,
                'is_online' => $user->getIsOnline()
            ];

            return new Response(200, $publicData);
        } catch (Exception $ex) {
            return Validate::Ex($ex);
        }
    }

    /**
     * Редактирование профиля
     */
    #[OA\Put(
        path: '/api/users',
        summary: 'Редактирование профиля текущего пользователя',
        security: [['cookieAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'full_name', type: 'string', example: 'Пётр Петров'),
                    new OA\Property(property: 'phone', type: 'string', example: '+79007654321'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'petr@example.com'),
                    new OA\Property(property: 'city', type: 'string', example: 'Москва'),
                    new OA\Property(property: 'birth_date', type: 'string', format: 'date', example: '1995-03-20'),
                    new OA\Property(property: 'about', type: 'string', example: 'Новый текст о себе'),
                    new OA\Property(property: 'profile_picture', type: 'string', example: 'https://example.com/avatar.jpg'),
                    new OA\Property(property: 'availability_status', type: 'string', enum: ['available', 'busy', 'offline'])
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Профиль обновлён',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'result', type: 'string', example: 'successful')
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Ошибка валидации (неверный формат, занятый телефон/email и т.д.)',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Не авторизован',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Пользователь не найден',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 409,
                description: 'Телефон или email уже заняты',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string')
                    ]
                )
            )
        ]
    )]
    #[Route("", "PUT")]
    public function edit($params)
    {
        try {
            session_start();
            $userId = $_SESSION['id'] ?? null;
            if (!$userId) {
                return new Response(401, ['error' => 'Unauthorized']);
            }

            $user = UsersQuery::create()->findOneById($userId);
            if (!$user) {
                return new Response(404, ['error' => 'User not found']);
            }

            // Обновляем только переданные поля
            $editableFields = ['full_name', 'city', 'about', 'profile_picture', 'availability_status'];
            foreach ($editableFields as $field) {
                if (isset($params[$field])) {
                    $setter = 'set' . str_replace('_', '', ucwords($field, '_')); // fullName -> setFullName, profile_picture -> setProfilePicture
                    $user->$setter(trim($params[$field]));
                }
            }

            // Отдельно обрабатываем дату рождения
            if (isset($params['birth_date'])) {
                if (!Validate::date($params['birth_date'], $r)) {
                    return $r;
                }
                $user->setBirthDate($params['birth_date']);
            }

            // Отдельно обрабатываем телефон и email (с проверкой уникальности)
            if (isset($params['phone']) && trim($params['phone']) !== $user->getPhone()) {
                $phone = trim($params['phone']);
                if (Validate::phone($phone, $r)) {
                    return $r;
                }
                if (Validate::findUserByField('phone', $phone, $r, $userId)) {
                    return $r;
                }
                $user->setPhone($phone);
            }

            if (isset($params['email']) && trim($params['email']) !== $user->getEmail()) {
                $email = trim($params['email']);
                if (Validate::email($email, $r)) {
                    return $r;
                }
                if (Validate::findUserByField('email', $email, $r, $userId)) {
                    return $r;
                }
                $user->setEmail($email);
            }

            $user->save();

            return new Response(200, ['result' => 'successful']);
        } catch (Exception $ex) {
            return Validate::Ex($ex);
        }
    }

    /**
     * Смена пароля
     */
    #[OA\Put(
        path: '/api/users/password',
        summary: 'Смена пароля текущего пользователя',
        security: [['cookieAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['old', 'new', 'confirm'],
                properties: [
                    new OA\Property(property: 'old', type: 'string', format: 'password', example: 'oldpass'),
                    new OA\Property(property: 'new', type: 'string', format: 'password', example: 'newpass'),
                    new OA\Property(property: 'confirm', type: 'string', format: 'password', example: 'newpass')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Пароль изменён',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'result', type: 'string', example: 'successful')
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Не все поля, не совпадают, неверный старый пароль',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Не авторизован',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Пользователь не найден',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string')
                    ]
                )
            )
        ]
    )]
    #[Route("/password", "PUT")]
    public function changePassword($params)
    {
        try {
            session_start();
            $userId = $_SESSION['id'] ?? null;
            if (!$userId) {
                return new Response(401, ['error' => 'Unauthorized']);
            }

            $old = $params['old'] ?? null;
            $new = $params['new'] ?? null;
            $confirm = $params['confirm'] ?? null;

            if (!$old || !$new || !$confirm) {
                return new Response(400, ['error' => 'All password fields required']);
            }

            if ($new !== $confirm) {
                return new Response(400, ['error' => 'New password and confirm do not match']);
            }

            $user = UsersQuery::create()->findOneById($userId);
            if (!$user) {
                return new Response(404, ['error' => 'User not found']);
            }

            if (!password_verify($old, $user->getPassword())) {
                return new Response(400, ['error' => 'Wrong old password']);
            }

            $user->setPassword(password_hash($new, PASSWORD_DEFAULT));
            $user->save();

            return new Response(200, ['result' => 'successful']);
        } catch (Exception $ex) {
            return Validate::Ex($ex);
        }
    }

    /**
     * Удаление своего профиля
     */
    #[OA\Delete(
        path: '/api/users',
        summary: 'Удаление профиля текущего пользователя',
        security: [['cookieAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Профиль удалён',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'result', type: 'string', example: 'successful')
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Не авторизован',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Пользователь не найден',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string')
                    ]
                )
            )
        ]
    )]
    #[Route("", "DELETE")]
    public function delete()
    {
        try {
            session_start();
            $userId = $_SESSION['id'] ?? null;
            if (!$userId) {
                return new Response(401, ['error' => 'Unauthorized']);
            }

            return $this->innerDelete($userId);
        } catch (Exception $ex) {
            return Validate::Ex($ex);
        }
    }

    /**
     * Удаление профиля по ID (только свой)
     */
    #[OA\Delete(
        path: '/api/users/{id}',
        summary: 'Удаление профиля по ID (только для самого пользователя)',
        security: [['cookieAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer'),
                description: 'ID пользователя'
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Профиль удалён',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'result', type: 'string', example: 'successful')
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'ID не передан',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Не авторизован',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Попытка удалить чужой профиль',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Пользователь не найден',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string')
                    ]
                )
            )
        ]
    )]
    #[Route("/{id}", "DELETE")]
    public function deleteByID($params)
    {
        try {
            $id = $params['id'] ?? null;
            if (!$id) {
                return new Response(400, ['error' => 'User ID required']);
            }

            session_start();
            if (($_SESSION['id'] ?? null) != $id) {
                return new Response(403, ['error' => 'You can only delete your own profile']);
            }

            return $this->innerDelete($id);
        } catch (Exception $ex) {
            return Validate::Ex($ex);
        }
    }

    /**
     * Выход из системы
     */
    #[OA\Post(
        path: '/api/users/logout',
        summary: 'Выход из системы (удаление сессии и cookie)',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Успешный выход',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true)
                    ]
                )
            )
        ]
    )]
    #[Route("/logout", "POST", false)]
    public function logout()
    {
        session_start();
        session_destroy();
        $_SESSION = [];

        $response = new Response(200, ['success' => true]);
        $response->setCook('jwt', '', time() - 3600, '/', '', true, true, 'Strict');

        return $response;
    }

    /**
     * Внутренний метод удаления пользователя
     */
    private function innerDelete($userId)
    {
        $user = UsersQuery::create()->findOneById($userId);
        if (!$user) {
            return new Response(404, ['error' => 'User not found']);
        }

        $user->delete();

        // Завершаем сессию и удаляем куку
        session_destroy();
        $_SESSION = [];
        $response = new Response(200, ['result' => 'successful']);
        $response->setCook('jwt', '', time() - 3600, '/', '', true, true, 'Strict');

        return $response;
    }

    /**
     * Форматирование полного профиля пользователя (без пароля)
     */
    private function formatUserProfile(Users $user): array
    {
        return [
            'id' => $user->getId(),
            'full_name' => $user->getFullName(),
            'phone' => $user->getPhone(),
            'email' => $user->getEmail(),
            'city' => $user->getCity(),
            'birth_date' => $user->getBirthDate() ? $user->getBirthDate()->format('Y-m-d') : null,
            'about' => $user->getAbout(),
            'profile_picture' => $user->getProfilePicture(),
            'vk_id' => $user->getVkId(),
            'tg_id' => $user->getTgId(),
            'balance' => $user->getBalance(),
            'availability_status' => $user->getAvailabilityStatus(),
            'is_admin' => $user->getIsAdmin(),
            'is_moderator' => $user->getIsModerator(),
            'last_seen_at' => $user->getLastSeenAt() ? $user->getLastSeenAt()->format('Y-m-d H:i:s') : null,
            'is_online' => $user->getIsOnline(),
            'average_rating' => $user->getAverageRating(),
            'created_at' => $user->getCreatedAt() ? $user->getCreatedAt()->format('Y-m-d H:i:s') : null,
            'updated_at' => $user->getUpdatedAt() ? $user->getUpdatedAt()->format('Y-m-d H:i:s') : null
        ];
    }
}