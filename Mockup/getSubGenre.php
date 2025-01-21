<?php
require_once 'helpers/GenreSelectDAO.php';

if (isset($_GET['MainGenreID'])) {
    $mainGenreId = intval($_GET['MainGenreID']);
    $genreSelectDAO = new GenreSelectDAO();

    // サブジャンルを取得
    $subGenres = $genreSelectDAO->getSubGenresByMainGenreID($mainGenreId);

    header('Content-Type: application/json');
    echo json_encode($subGenres);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request: MainGenreID is missing']);
}

?>
