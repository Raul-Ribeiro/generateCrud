<?php
include_once ROOT_WEBCORR_APLICACAO_VALOR.'/sistema/WEB-INF/classes/includeDinamico.php';

class ExemploForm extends AbstractBaseForm
{
    private $codigo;
    private $nome;
    private $sobrenome;
    private $peso;
    private $idade;

    // PAGINACAO 
    private $acao;
    private $nomePesquisa;
    private $paginaAtual;
    private $totalRegistros;

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getSobrenome()
    {
        return $this->sobrenome;
    }

    public function setSobrenome($sobrenome)
    {
        $this->sobrenome = $sobrenome;
    }

    public function getPeso()
    {
        return $this->peso;
    }

    public function setPeso($peso)
    {
        $this->peso = $peso;
    }

    public function getIdade()
    {
        return $this->idade;
    }

    public function setIdade($idade)
    {
        $this->idade = $idade;
    }

    public function getAcao()
    {
        return $this->acao;
    }

    public function setAcao($acao)
    {
        $this->acao = $acao;
    }

    public function getNomePesquisa()
    {
        return $this->nomePesquisa;
    }

    public function setNomePesquisa($nomePesquisa)
    {
        $this->nomePesquisa = $nomePesquisa;
    }

    public function getPaginaAtual()
    {
        return $this->paginaAtual;
    }

    public function setPaginaAtual($paginaAtual)
    {
        $this->paginaAtual = $paginaAtual;
    }

    public function getTotalRegistros()
    {
        return $this->totalRegistros;
    }

    public function setTotalRegistros($totalRegistros)
    {
        $this->totalRegistros = $totalRegistros;
    }

    public function validate(&$mapping, &$request)
    {
        $actionMessages = &$this->actionErrors;
        $pmr = &$this->pmr;
        $locale = &$this->locale;

        $nome = trim($request->getParameter('nome'));
        $sobrenome = trim($request->getParameter('sobrenome'));
        $peso = trim($request->getParameter('peso'));
        $idade = trim($request->getParameter('idade'));

        if (trim($nome) == '') {
            $args = array(
                'Nome',
            );
            $msg = $pmr->getMessage($locale, 'campo.null', $args);
            $actionMessages->add('erro', new ActionError($msg));
        }

        if (trim($sobrenome) == '') {
            $args = array(
                'Sobrenome',
            );
            $msg = $pmr->getMessage($locale, 'campo.null', $args);
            $actionMessages->add('erro', new ActionError($msg));
        }

        if (trim($peso) == '') {
            $args = array(
                'Peso',
            );
            $msg = $pmr->getMessage($locale, 'campo.null', $args);
            $actionMessages->add('erro', new ActionError($msg));
        }

        if (trim($idade) == '') {
            $args = array(
                'Idade',
            );
            $msg = $pmr->getMessage($locale, 'campo.null', $args);
            $actionMessages->add('erro', new ActionError($msg));
        }

        $this->saveErrors($request, $actionMessages);
        $this->saveFormBean($request, $this);
        return $actionMessages;
    }

    public function transfereRequestForm($request)
    {
        $this->setCodigo(trim($request['codigo']));
        $this->setNome(trim($request['nome']));
        $this->setSobrenome(trim($request['sobrenome']));
        $this->setPeso(trim($request['peso']));
        $this->setIdade(trim($request['idade']));

        // PAGINACAO 
        $this->setAcao(trim($request['acao']));
        $this->setNomePesquisa(trim($request['nomePesquisa']));
        $this->setPaginaAtual(trim($request['paginaAtual']));
        $this->setTotalRegistros(trim($request['totalRegistros']));
    }

    public function transfereFormModel()
    {
        $objExemplo = new Exemplo();

        $objExemplo->setCodigo($this->getCodigo());
        $objExemplo->setNome($this->getNome());
        $objExemplo->setSobrenome($this->getSobrenome());
        $objExemplo->setPeso($this->getPeso());
        $objExemplo->setIdade($this->getIdade());

        return $objExemplo;
    }

    public function transfereModelForm($objExemplo)
    {
        $this->setCodigo($objExemplo->getCodigo());
        $this->setNome($objExemplo->getNome());
        $this->setSobrenome($objExemplo->getSobrenome());
        $this->setPeso($objExemplo->getPeso());
        $this->setIdade($objExemplo->getIdade());
    }
}