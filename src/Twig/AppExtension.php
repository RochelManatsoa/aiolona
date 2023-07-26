<?php

namespace App\Twig;

use App\Entity\AIcores;
use App\Entity\Identity;
use App\Entity\Posting;
use App\Manager\PostingManager;
use App\Repository\AccountRepository;
use App\Repository\AINoteRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $accountRepository;
    private $aINoteRepository;

    public function __construct(
        AccountRepository $accountRepository, 
        AINoteRepository $aINoteRepository,
        PostingManager $postingManager
        )
    {
        $this->accountRepository = $accountRepository;
        $this->aINoteRepository = $aINoteRepository;
        $this->postingManager = $postingManager;
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
            new TwigFunction('getNoteDesc', [$this, 'getNoteDesc']),
            new TwigFunction('getStars', [$this, 'getStars']),
            new TwigFunction('checkInfo', [$this, 'checkInfo']),
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

    public function getNote($note)
    {
        if($note == null) return 0;
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

    public function getNoteDesc(int $note)
    {
        if($note == 0) return 'Veuillez noter votre comptetence sur cet outil';
        return $this->aINoteRepository->findBy(['note' => $note])[0]->getDescription();
    }

    public function getStars(Identity $identity)
    {
        $stars = [];
        foreach ($identity->getAicores() as $aicore) {
            $stars[] = [
                'name' => $aicore->getName(),
                'note' => $this->getIdentityAiNote($aicore, $identity)
            ];
        }

        return $stars;
    }

    public function checkInfo(Posting $posting): bool
    {
        return $this->postingManager->checkInfo($posting);
    }
}
