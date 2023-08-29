<?php

namespace App\Service\Message;

use App\Repository\MessageRepository;
use App\Service\User\UserService;

class MessageService
{
    public function __construct(
        private MessageRepository $messageRepository,
        private UserService $userService,
    )
    {
        $this->messageRepository = $messageRepository;
        $this->userService = $userService;
    }

    public function getReceivedMessages(): array
    {
        return $this->messageRepository->findBy([
            'recipient' => $this->userService->getCurrentUser()
        ]);
    }

    public function getSentMessages(): array
    {
        return $this->messageRepository->findBy([
            'sender' => $this->userService->getCurrentUser()
        ]);
    }

    public function getUnreadMessages(): array
    {
        return $this->messageRepository->findBy([
            'user' => $this->userService->getCurrentUser()
        ]);
    }

}