<?php
include_once ROOT_WEBCORR_APLICACAO_VALOR.'/sistema/WEB-INF/classes/includeDinamico.php';

class _NomeFacade
{
    public function incluir_Nome($obj_Nome)
    {
        DAOFactory::getDAOFactory(DAOFactory::$POSTGRESQL);

        $obj_NomeDao = new _NomeDAO();
        try {
            DAOFactory::$connection->pdo->beginTransaction();

            $obj_NomeDao->incluir_Nome($obj_Nome);

            DAOFactory::$connection->pdo->commit();
            DAOFactory::$connection->closePDO();
        } catch (Exception $e) {
            DAOFactory::$connection->pdo->rollBack();
            DAOFactory::$connection->closePDO();
            throw new Exception($e->getMessage());
        }

        return true;
    }

    public function alterar_Nome($obj_Nome)
    {
        DAOFactory::getDAOFactory(DAOFactory::$POSTGRESQL);

        $obj_NomeDao = new _NomeDAO();
        try {
            DAOFactory::$connection->pdo->beginTransaction();

            $obj_NomeDao->alterar_Nome($obj_Nome);

            DAOFactory::$connection->pdo->commit();
            DAOFactory::$connection->closePDO();
        } catch (Exception $e) {
            DAOFactory::$connection->pdo->rollBack();
            DAOFactory::$connection->closePDO();
            throw new Exception($e->getMessage());
        }

        return true;
    }

    public function excluir_Nome($codigo)
    {
        DAOFactory::getDAOFactory(DAOFactory::$POSTGRESQL);

        $obj_NomeDao = new _NomeDAO();
        try {
            DAOFactory::$connection->pdo->beginTransaction();

            $obj_NomeDao->excluir_Nome($codigo);

            DAOFactory::$connection->pdo->commit();
            DAOFactory::$connection->closePDO();
        } catch (Exception $e) {
            DAOFactory::$connection->pdo->rollBack();
            DAOFactory::$connection->closePDO();
            throw new Exception($e->getMessage());
        }
        return true;
    }

    public function obter_Nome($codigo)
    {
        DAOFactory::getDAOFactory(DAOFactory::$POSTGRESQL);

        $obj_NomeDao = new _NomeDAO();

        try {
            DAOFactory::$connection->pdo->beginTransaction();

            $obj_Nome = $obj_NomeDao->obter_Nome($codigo);

            DAOFactory::$connection->pdo->commit();
            DAOFactory::$connection->closePDO();
        } catch (Exception $e) {
            DAOFactory::$connection->pdo->rollBack();
            DAOFactory::$connection->closePDO();
            throw new Exception($e->getMessage());
        }

        return $obj_Nome;
    }

    public function listar_Nome()
    {
        $collection_Nome = array();
        DAOFactory::getDAOFactory(DAOFactory::$POSTGRESQL);

        $obj_NomeDao = new _NomeDAO();
        try {
            DAOFactory::$connection->pdo->beginTransaction();

            $collection_Nome = $obj_NomeDao->listar_Nome();

            DAOFactory::$connection->pdo->commit();
            DAOFactory::$connection->closePDO();
        } catch (Exception $e) {
            DAOFactory::$connection->pdo->rollBack();
            DAOFactory::$connection->closePDO();
            throw new Exception($e->getMessage());
        }

        return $collection_Nome;
    }
}
