<?php

namespace App\Twig;

use App\Entity\AIcores;
use App\Entity\Identity;
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
            new TwigFunction('getNote', [$this, 'getNote']),
            new TwigFunction('getIdentityAiNote', [$this, 'getIdentityAiNote']),
            new TwigFunction('checkNotNull', [$this, 'checkNotNull']),
            new TwigFunction('isoToEmoji', [$this, 'isoToEmoji']),
        ];
    }

    public function showAccountDesc(int $accountId)
    {
        return $this->accountRepository->findOneById($accountId)->getDescription();
    }

    public function showCountry($countryCode)
    {
        if(null !== $countryCode){
            return \Symfony\Component\Intl\Countries::getName($countryCode);
        }
        return null;
    }

    public function getNote(string $note)
    {
        return (int)$note;
    }

    public function getIdentityAiNote(AIcores $aIcores, Identity $identity)
    {
        $note = 0;
        foreach($identity->getNotes() as $key => $value){
            if($value->getAiCore() == $aIcores){
                $note = (int)$value->getNote();
            }
        }

        return $note;
    }

    public function checkNotNull($variable)
    {
        if(null !== $variable){
            return $variable;
        }
        return null;
    }

    public function isoToEmoji(string $code)
    {
        return implode(
            '',
            array_map(
                fn ($letter) => mb_chr(ord($letter) % 32 + 0x1F1E5),
                str_split($code)
            )
        );
    }
}
