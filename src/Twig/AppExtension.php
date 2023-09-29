<?php

namespace App\Twig;

use Twig\TwigFunction;
use App\Entity\AIcores;
use App\Entity\Posting;
use App\Entity\Identity;
use App\Entity\TechnicalSkill;
use App\Manager\PostingManager;
use App\Repository\AINoteRepository;
use App\Repository\AccountRepository;
use Symfony\Component\Intl\Countries;
use Twig\Extension\AbstractExtension;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

class AppExtension extends AbstractExtension
{

    public function __construct(
        private AccountRepository $accountRepository, 
        private AINoteRepository $aINoteRepository,
        private PostingManager $postingManager,
        private TranslatorInterface $translator,
        private RequestStack $requestStack  
        )
    {

    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('show_account_desc', [$this, 'showAccountDesc']),
            new TwigFunction('show_country', [$this, 'showCountry']),
            new TwigFunction('getNote', [$this, 'getNote']),
            new TwigFunction('getIdentitySkillNote', [$this, 'getIdentitySkillNote']),
            new TwigFunction('getIdentityAiNote', [$this, 'getIdentityAiNote']),
            new TwigFunction('checkNotNull', [$this, 'checkNotNull']),
            new TwigFunction('isoToEmoji', [$this, 'isoToEmoji']),
            new TwigFunction('getNoteDesc', [$this, 'getNoteDesc']),
            new TwigFunction('getStars', [$this, 'getStars']),
            new TwigFunction('getSkills', [$this, 'getSkills']),
            new TwigFunction('checkInfo', [$this, 'checkInfo']),
            new TwigFunction('meta_title', [$this, 'metaTitle']),
            new TwigFunction('meta_description', [$this, 'metaDescription']),
            new TwigFunction('meta_keywords', [$this, 'metaKeywords']),
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

    public function getIdentitySkillNote(TechnicalSkill $skills, Identity $identity)
    {
        $note = 0;
        foreach($identity->getSkillNotes() as $key => $value){
            if($value->getTechnicalSkill() == $skills){
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
                'url' => $aicore->getUrl(),
                'type' => $aicore->getType(),
                'image' => $aicore->getImage(),
                'exp' => $this->getCount($aicore->getExperiences(), $identity),
                'slogan' => $aicore->getSlogan(),
                'note' => $this->getIdentityAiNote($aicore, $identity)
            ];
        }

        return $stars;
    }

    public function getSkills(Identity $identity)
    {
        $stars = [];
        foreach ($identity->getTechnicalSkills() as $skill) {
            $stars[] = [
                'name' => $skill->getName(),
                'url' => $skill->getUrl(),
                'type' => $skill->getType(),
                'image' => $skill->getImage(),
                'exp' => $this->getCount($skill->getExperience(), $identity),
                'note' => $this->getIdentitySkillNote($skill, $identity)
            ];
        }

        return $stars;
    }

    public function checkInfo(Posting $posting): bool
    {
        return $this->postingManager->checkInfo($posting);
    }

    private function getCount(Collection $experiences, Identity $identity): int
    {
        $count = 0;
        foreach ($experiences as $key => $experience) {
            if($experience->getIdentity() === $identity){
                $count ++;
            }
        }
        return $count;
    }

    public function metaTitle(): string
    {
        $routeName = $this->requestStack->getCurrentRequest()->attributes->get('_route'); 

        return $this->translator->trans($routeName . '.title');
    }

    public function metaDescription(): string
    {
        $routeName = $this->requestStack->getCurrentRequest()->attributes->get('_route'); 
        return $this->translator->trans($routeName . '.description');
    }

    public function metaKeywords(): string
    {
        $routeName = $this->requestStack->getCurrentRequest()->attributes->get('_route');  
        return $this->translator->trans($routeName . '.keywords');
    }
}
