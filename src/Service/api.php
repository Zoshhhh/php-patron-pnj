<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json; charset=utf-8');

$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

class SimpleDice
{
    private $sides;

    public function __construct($sides)
    {
        $this->sides = $sides;
    }

    public function roll()
    {
        return rand(1, $this->sides);
    }

    public function rollMultiple($count)
    {
        $results = [];
        for ($i = 0; $i < $count; $i++) {
            $results[] = $this->roll();
        }
        return $results;
    }
}

class DataManager
{
    private $file = 'simple_dice_data.json';

    public function saveResult($dice, $result, $results = null, $count = 1)
    {
        $data = [
            'dice' => $dice,
            'result' => $result,
            'count' => $count
        ];

        // Si c'est un lancer multiple
        if ($results !== null && is_array($results)) {
            $data['results'] = $results;
            $data['total'] = $result; // $result contient déjà le total
        }

        return file_put_contents($this->file, json_encode($data)) !== false;
    }

    public function getResult()
    {
        return file_exists($this->file) ? json_decode(file_get_contents($this->file), true) : null;
    }
}

$manager = new DataManager();

if (($_POST['action'] ?? '') === 'roll') {
    $diceType = $_POST['dice'] ?? 'd20';
    $diceCount = max(1, min(20, (int) ($_POST['count'] ?? 1))); // Entre 1 et 20 dés

    $sides = (int) str_replace('d', '', $diceType);
    $dice = new SimpleDice($sides);

    if ($diceCount === 1) {
        // Lancer simple
        $result = $dice->roll();
        $saved = $manager->saveResult($diceType, $result, null, 1);

        echo json_encode([
            'success' => $saved,
            'result' => $result,
            'dice' => $diceType,
            'count' => 1
        ]);
    } else {
        // Lancers multiples
        $results = $dice->rollMultiple($diceCount);
        $total = array_sum($results);
        $saved = $manager->saveResult($diceType, $total, $results, $diceCount);

        echo json_encode([
            'success' => $saved,
            'total' => $total,
            'results' => $results,
            'dice' => $diceType,
            'count' => $diceCount
        ]);
    }

    exit;
}

// Pour récupération périodique
if (($_GET['action'] ?? '') === 'get') {
    echo json_encode($manager->getResult() ?? []);
    exit;
}
?>