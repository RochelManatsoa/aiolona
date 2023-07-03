<?php

namespace App\Twig;

use App\Repository\AccountRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('show_account_desc', [$this, 'showAccountDesc']),
            new TwigFunction('show_country', [$this, 'showCountry']),
        ];
    }

    public function showAccountDesc(int $accountId)
    {
        return $this->accountRepository->findOneById($accountId)->getDescription();
    }

    public function showCountry($countryCode)
    {
        return \Symfony\Component\Intl\Countries::getName($countryCode);
    }
}
