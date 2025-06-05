<?php

namespace App\Model\Personnage;

use App\Interface\PersonnageInterface;
use App\Interface\ComportementCombatInterface;
use App\Model\Item\CombatItem;

abstract class PersonnageAbstrait implements PersonnageInterface
{
    protected string $nom;
    protected int $pointsDeVie;
    protected int $force;
    protected int $dexterite;
    protected int $constitution;
    protected int $intelligence;
    protected int $sagesse;
    protected int $charisme;
    protected int $classeArmure;
    protected int $vitesse;
    protected ComportementCombatInterface $comportementCombat;
    protected array $inventaire = [];

    public function __construct(string $nom, ComportementCombatInterface $comportementCombat)
    {
        $this->nom = $nom;
        $this->comportementCombat = $comportementCombat;
        $this->inventaire = [];
    }

    public function attaquer(PersonnageInterface $cible): void
    {
        $degats = $this->comportementCombat->attaquer();
        
        // Bonus de dégâts des objets de combat équipés
        foreach ($this->inventaire as $item) {
            if ($item instanceof CombatItem && $item->estUtilisable()) {
                $degats += $item->getDegats();
                $item->utiliser();
            }
        }
        
        $cible->recevoirDegats($degats);
    }

    public function recevoirDegats(int $degats): void
    {
        $this->pointsDeVie = max(0, $this->pointsDeVie - $degats);
    }

    public function ajouterItem(CombatItem $item): void
    {
        $this->inventaire[] = $item;
    }

    public function retirerItem(int $index): ?CombatItem
    {
        if (isset($this->inventaire[$index])) {
            $item = $this->inventaire[$index];
            unset($this->inventaire[$index]);
            $this->inventaire = array_values($this->inventaire); // Réindexer le tableau
            return $item;
        }
        return null;
    }

    public function getInventaire(): array
    {
        return array_map(function($item) {
            return $item->toArray();
        }, $this->inventaire);
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPointsDeVie(): int
    {
        return $this->pointsDeVie;
    }

    public function estVivant(): bool
    {
        return $this->pointsDeVie > 0;
    }

    public function getForce(): int
    {
        return $this->force;
    }

    public function getDexterite(): int
    {
        return $this->dexterite;
    }

    public function getConstitution(): int
    {
        return $this->constitution;
    }

    public function getIntelligence(): int
    {
        return $this->intelligence;
    }

    public function getSagesse(): int
    {
        return $this->sagesse;
    }

    public function getCharisme(): int
    {
        return $this->charisme;
    }

    public function getClasseArmure(): int
    {
        return $this->classeArmure;
    }

    public function getVitesse(): int
    {
        return $this->vitesse;
    }

    public function getModificateur(int $valeurCaracteristique): int
    {
        return (int) floor(($valeurCaracteristique - 10) / 2);
    }

    public function toArray(): array
    {
        return [
            'nom' => $this->nom,
            'pointsDeVie' => $this->pointsDeVie,
            'force' => $this->force,
            'dexterite' => $this->dexterite,
            'constitution' => $this->constitution,
            'intelligence' => $this->intelligence,
            'sagesse' => $this->sagesse,
            'charisme' => $this->charisme,
            'classeArmure' => $this->classeArmure,
            'vitesse' => $this->vitesse,
            'inventaire' => $this->getInventaire()
        ];
    }
} 