<?php
include_once ROOT_WEBCORR_APLICACAO_VALOR.'/sistema/WEB-INF/classes/includeDinamico.php';

class ExemploDAO extends DAOFactory
{
    public function incluirExemplo($objExemplo)
    {
        $sql = 'INSERT INTO construtora.tb_exemplo ';
        $sql .= '( ';
        $sql .= '    exe_nome, ';
        $sql .= '    exe_sobrenome, ';
        $sql .= '    exe_peso, ';
        $sql .= '    exe_idade ';
        $sql .= ') ';
        $sql .= 'VALUES ';
        $sql .= '( ';
        $sql .= '    :nome, ';
        $sql .= '    :sobrenome, ';
        $sql .= '    :peso, ';
        $sql .= '    :idade ';
        $sql .= ') ';

        $query = parent::$connection->pdo->prepare($sql);

        $query->bindParam(':nome', $objExemplo->getNome(), PDO::PARAM_STR);
        $query->bindParam(':sobrenome', $objExemplo->getSobrenome(), PDO::PARAM_STR);
        $query->bindParam(':peso', $objExemplo->getPeso(), PDO::PARAM_INT);
        $query->bindParam(':idade', $objExemplo->getIdade(), PDO::PARAM_INT);

        if (!$query->execute()) {
            $collectionErro = $query->errorInfo();
            throw new Exception('ExemploDAO->incluirExemplo ' . $collectionErro[2]);
        }

        return true;
    }

    public function alterarExemplo($objExemplo)
    {
        $sql = 'UPDATE construtora.tb_exemplo SET ';
        $sql .= '    exe_nome = :nome, ';
        $sql .= '    exe_sobrenome = :sobrenome, ';
        $sql .= '    exe_peso = :peso, ';
        $sql .= '    exe_idade = :idade ';
        $sql .= 'WHERE ';
        $sql .= '    exe_exemplo = :codigoExemplo ';

        $query = parent::$connection->pdo->prepare($sql);

        $query->bindParam(':codigoExemplo', $objExemplo->getCodigo(), PDO::PARAM_INT);
        $query->bindParam(':nome', $objExemplo->getNome(), PDO::PARAM_STR);
        $query->bindParam(':sobrenome', $objExemplo->getSobrenome(), PDO::PARAM_STR);
        $query->bindParam(':peso', $objExemplo->getPeso(), PDO::PARAM_INT);
        $query->bindParam(':idade', $objExemplo->getIdade(), PDO::PARAM_INT);

        if (!$query->execute()) {
            $collectionErro = $query->errorInfo();
            throw new Exception('ExemploDAO->alterarExemplo ' . $collectionErro[2]);
        }

        return true;
    }

    public function excluirExemplo($codigo)
    {
        $sql = 'DELETE FROM construtora.tb_exemplo ';
        $sql .= 'WHERE ';
        $sql .= '    exe_codigo = :codigoExemplo ';

        $query = parent::$connection->pdo->prepare($sql);

        $query->bindParam(':codigoExemplo', $codigo, PDO::PARAM_INT);

        if (!$query->execute()) {
            $collectionErro = $query->errorInfo();
            throw new Exception('ExemploDAO->excluirExemplo ' . $collectionErro[2]);
        }

        return true;
    }

    public function obterExemplo($codigo)
    {
        $sql = 'SELECT ';
        $sql .= '    exe_nome, ';
        $sql .= '    exe_sobrenome, ';
        $sql .= '    exe_peso, ';
        $sql .= '    exe_idade ';
        $sql .= 'FROM construtora.tb_exemplo ';
        $sql .= 'WHERE ';
        $sql .= '    exe_codigo = :codigoExemplo ';

        $query = parent::$connection->pdo->prepare($sql);

        $query->bindParam(':codigoExemplo', $codigo, PDO::PARAM_INT);

        if ($query->execute()) {
            $rs = $query->fetch(PDO::FETCH_ASSOC);
            $objExemplo = new Exemplo();

            $objExemplo->setCodigo($rs['exe_codigo']);
            $objExemplo->setNome($rs['exe_nome']);
            $objExemplo->setSobrenome($rs['exe_sobrenome']);
            $objExemplo->setPeso($rs['exe_peso']);
            $objExemplo->setIdade($rs['exe_idade']);
        } else {
            $collectionErro = $query->errorInfo();
            throw new Exception('ExemploDAO->obterExemplo ' . $collectionErro[2]);
        }

        return $objExemplo;
    }

    public function listarExemplo()
    {
        $sql = 'SELECT ';
        $sql .= '    exe_nome, ';
        $sql .= '    exe_sobrenome, ';
        $sql .= '    exe_peso, ';
        $sql .= '    exe_idade ';
        $sql .= 'FROM construtora.tb_exemplo ';

        $query = parent::$connection->pdo->prepare($sql);

        $collectionExemplo = array();
        if ($query->execute()) {
            while($rs = $query->fetch(PDO::FETCH_ASSOC)) {
                $objExemplo = new Exemplo();

                $objExemplo->setCodigo($rs['exe_codigo']);
                $objExemplo->setNome($rs['exe_nome']);
                $objExemplo->setSobrenome($rs['exe_sobrenome']);
                $objExemplo->setPeso($rs['exe_peso']);
                $objExemplo->setIdade($rs['exe_idade']);
                array_push($collectionExemplo, $objExemplo);
            }
        } else {
            $collectionErro = $query->errorInfo();
            throw new Exception('ExemploDAO->listarExemplo ' . $collectionErro[2]);
        }

        return $collectionExemplo;
    }
}