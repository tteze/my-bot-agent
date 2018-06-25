<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class SkillsConversation extends Conversation
{
    private $skills = [
        'top' => [
            'PHP' => 'J\'ai effectué plusieurs projets en PHP depuis que j\'ai 16 ans je connais un grand nombre de fonctions 
                mais aussi comment php stocke et calcul',
            'mysql' => 'À travers ma formation j\'ai non seulement appris à faire des requêtes compliqués. Mais aussi qomment sont stockées
                les données et comment optimiser les requêtes. Le MCD n\'a plus de secret pour moi !',
            'Laravel' => 'J\'ai découvert Laravel il y 1 an et depuis c\est devenu mon compagnon de route. J\'ai la chance de pouvoir 
                en faire tout les jours actuelement ! Ce bot utilise lui aussi une couche Laravel :)',
        ],
        'middle' => [
            'Spring' => 'J\'en ai fais à travers ma formation.',
            'VueJs' => 'Il s\'agit d\'un framework front que j\'utilise depuis peu mais qui m\'est facile d\'accès car à travers ma
                formation j\'ai reçu un bagage théorique commun à la plupart des frameworks côté client.',
            'Angular' => 'J\'ai eu l\'occation de donner une formation d\'approche à Angular pour toute ma classe de Master.',
            'Symfony' => 'J\'ai rapidement travailler avec Syfony 2.3 pour un projet chez Valeur et Capital qui consistait à mettre
                à disposition des documents marketings. J\'ai une plus grande expérience avec Doctrine que nous avions intégré sur le 
                framework CodeIgniter'
        ],
        'bottom' => [
            'Spark' => 'J\'ai eu l\'occasion de voir et d\'expérimenter Spark à travers un projet de cours.',
            'Big Data' => 'J\'ai une approche au big data sur le stckage avec HDFS et mongo mais ausi sur le traitement. On a eu l\'occasion
                à travers un projet de fouille de données d\'extratire les motifs fréquents de goût d\'utilisateur de l\'application yelp
                à l\'aide de l\'algorithme "à priori"',
            'Sécurité' => 'J\'ai eu un cours de sécurité ou on a appris à exploiter et contrer les 10 failles OWASP 2017.'
        ]
    ];

    /**
     * @return void
     */
    public function run()
    {
        $this->askForParticularSkills();
    }

    /**
     * Ask user if he want more information about a skill
     */
    public function askForParticularSkills() {
        $top = implode(', ', array_keys($this->skills['top']));
        $middle = implode(', ', array_keys($this->skills['middle']));
        $bottom = implode(', ', array_keys($this->skills['bottom']));

        $this->ask('Mes points forts sont la maîtrise du ' . $top . '. Je me débrouille bien aussi en ' .
            $middle . '. J\'ai aussi des notions de ' . $bottom . '. Est-ce que vous voulez en savoir un peu plus sur l\'une de ces
             compétences ?', $this->getSkillsDetails());
    }

    /**
     * Prepare answers about each skills
     */
    private function getSkillsDetails() {
        return collect($this->skills)->flatten()->map(function ($experience, $skillName) {
            return [
                'pattern' => '.*(' . $skillName . ').*',
                'callback' => function () use ($experience) {
                    $this->say($experience);
                    $this->askForAnotherCompetences();
                }
            ];
        })->push([
            'pattern' => '.*',
            'callback' => function () {
                $this->say('De quoi voulez-vous parlez ?');
            }
        ])->toArray();
    }

    /**
     * Add a listener about each skills
     */
    public function listenAboutSkills() {
        $skillsAnswers = $this->getSkillsDetails();
        foreach ($skillsAnswers as $skillsAnswer) {
            $this->getBot()->hears($skillsAnswer['pattern'], $skillsAnswer['callback']);
        }
    }

    /**
     *
     */
    private function askForAnotherCompetences() {
        $responses = $this->getSkillsDetails();
        $this->ask('Vous voulez en savoir plus sur une autre de mes compétences ?', $responses);
    }
}