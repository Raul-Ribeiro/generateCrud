<?php
include_once ROOT_WEBCORR_APLICACAO_VALOR.'/sistema/WEB-INF/classes/includeDinamico.php';

class ExemploFacade
{
    public function incluirExemplo($objExemplo)
    {
        DAOFactory::getDAOFactory(DAOFactory::$POSTGRESQL);

        $objExemploDao = new ExemploDAO();
        try {
            DAOFactory::$connection->pdo->beginTransaction();

            $objExemploDao->incluirExemplo($objExemplo);

            DAOFactory::$connection->pdo->commit();
            DAOFactory::$connection->closePDO();
        } catch (Exception $e) {
            DAOFactory::$connection->pdo->rollBack();
            DAOFactory::$connection->closePDO();
            throw new Exception($e->getMessage());
        }

        return true;
    }

    public function alterarExemplo($objExemplo)
    {
        DAOFactory::getDAOFactory(DAOFactory::$POSTGRESQL);

        $objExemploDao = new ExemploDAO();
        try {
            DAOFactory::$connection->pdo->beginTransaction();

            $objExemploDao->alterarExemplo($objExemplo);

            DAOFactory::$connection->pdo->commit();
            DAOFactory::$connection->closePDO();
        } catch (Exception $e) {
            DAOFactory::$connection->pdo->rollBack();
            DAOFactory::$connection->closePDO();
            throw new Exception($e->getMessage());
        }

        return true;
    }

    public function excluirExemplo($codigo)
    {
        DAOFactory::getDAOFactory(DAOFactory::$POSTGRESQL);

        $objExemploDao = new ExemploDAO();
        try {
            DAOFactory::$connection->pdo->beginTransaction();

            $objExemploDao->excluirExemplo($codigo);

            DAOFactory::$connection->pdo->commit();
            DAOFactory::$connection->closePDO();
        } catch (Exception $e) {
            DAOFactory::$connection->pdo->rollBack();
            DAOFactory::$connection->closePDO();
            throw new Exception($e->getMessage());
        }
        return true;
    }

    public function obterExemplo($codigo)
    {
        DAOFactory::getDAOFactory(DAOFactory::$POSTGRESQL);

        $objExemploDao = new ExemploDAO();

        try {
            DAOFactory::$connection->pdo->beginTransaction();

            $objExemplo = $objExemploDao->obterExemplo($codigo);

            DAOFactory::$connection->pdo->commit();
            DAOFactory::$connection->closePDO();
        } catch (Exception $e) {
            DAOFactory::$connection->pdo->rollBack();
            DAOFactory::$connection->closePDO();
            throw new Exception($e->getMessage());
        }

        return $objExemplo;
    }

    public function listarExemplo()
    {
        $collectionExemplo = array();
        DAOFactory::getDAOFactory(DAOFactory::$POSTGRESQL);

        $objExemploDao = new ExemploDAO();
        try {
            DAOFactory::$connection->pdo->beginTransaction();

            $collectionExemplo = $objExemploDao->listarExemplo();

            DAOFactory::$connection->pdo->commit();
            DAOFactory::$connection->closePDO();
        } catch (Exception $e) {
            DAOFactory::$connection->pdo->rollBack();
            DAOFactory::$connection->closePDO();
            throw new Exception($e->getMessage());
        }

        return $collectionExemplo;
    }
}
