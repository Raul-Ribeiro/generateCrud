<?php

include "GenerateCrud.php";

$nomeFuncao = $_POST["nomeFuncao"];

$collectionAttr = array();
$contador = 0;
while ($contador < $_POST["qtdCampos"]) {

    $nome = "nome" . $contador;
    $tipo = "tipo" . $contador;
    $obrigatorio = "obrigatorio" . $contador;

    $arrNome = explode("_", $_POST[$nome]);

    $novoNome = "";
    for ($j = 0; $j < count($arrNome); $j++) {
        if ($j == 0) {
            $novoNome .= $arrNome[$j];
        } else {
            $novoNome .= ucfirst($arrNome[$j]);
        }
    }

    if ($novoNome != "") {
        $nomeCamelCase = $novoNome;
    }

    $attr = [
        "nome" => $_POST[$nome],
        "nomeCamelCase" => $nomeCamelCase,
        "tipo" => $_POST[$tipo],
        "obrigatorio" => $_POST[$obrigatorio],
    ];

    array_push($collectionAttr, $attr);
    $contador++;
}

$objGenerateCrud = new GenerateCrud();

//criando tabela
if (isset($_POST["checkTable"])) {
    // criando Table
    echo $objGenerateCrud->createTable($nomeFuncao, $_POST["prefixo"], $collectionAttr);
}

$objGenerateCrud->createPasta($nomeFuncao);

// criando DAO
$objGenerateCrud->createDao($nomeFuncao, $_POST["prefixo"], $collectionAttr);

// criando Facade
$objGenerateCrud->createFacade($nomeFuncao, $_POST["prefixo"], $collectionAttr);

array_unshift($collectionAttr, ["nome" => "codigo", "nomeCamelCase" => "codigo"]);

// criando Model
$objGenerateCrud->createModel($nomeFuncao, $collectionAttr);

// criando Action
$objGenerateCrud->createAction($nomeFuncao);

// criando Form
$objGenerateCrud->createForm($nomeFuncao, $collectionAttr);
