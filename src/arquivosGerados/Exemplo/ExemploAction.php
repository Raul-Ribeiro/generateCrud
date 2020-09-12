<?php
include_once ROOT_WEBCORR_APLICACAO_VALOR . '/sistema/WEB-INF/classes/includeDinamico.php';

class ExemploAction extends DefaultAction
{
    public function ExemploAction()
    {
        parent::AbstractBaseAction();
    }

    public function inicio($mapping, $form, &$request, &$response)
    {
        $smarty = parent::startSmarty($mapping, $form, $request, $response);

        $actionMessages = &$form->actionErrors;
        $pmr = &$this->pmr;
        $locale = &$this->locale;

        try {
            $this->dadosPesquisa($mapping, $form, $request, $response);

            $myForward = $mapping->findForwardConfig('pesquisarExemplo');
        } catch (Exception $e) {
            // Setando erro
            $actionMessages->add('erro', new ActionError($e->getMessage()));
            $this->saveErrors($request, $actionMessages);
            $smarty->assign("arrayMessages", $actionMessages->messages);

            // Redirecionando
            $myForward = $mapping->findForwardConfig('pesquisarExemplo');
        }

        return $myForward;
    }

    public function editar($mapping, $form, &$request, &$response)
    {
        $smarty = parent::startSmarty($mapping, $form, $request, $response);
        $actionMessages = &$form->actionErrors;
        $pmr = &$this->pmr;
        $locale = &$this->locale;

        $objExemploForm = new ExemploForm();
        $objExemploFacade = new ExemploFacade();

        try {
            if ($request->getParameter('acao') != "I") {
                $objExemplo = $objExemploFacade->obterExemplo($request->getParameter('codigo'));
                $objExemploForm->transfereModelForm($objExemplo);
            }
        } catch (Exception $e) {
            // Setando erro
            $msg = $pmr->getMessage($locale, 'editar.erro');
            $actionMessages->add('erro', new ActionError($e->getMessage()));
            $this->saveErrors($request, $actionMessages);

            // Redirecionando
            $myForward = $mapping->findForwardConfig('pesquisarExemplo');
        }
            
        // Paginacao
        $objExemploForm->setAcao($request->getParameter('acao'));
        $objExemploForm->setNomePesquisa($request->getParameter('nomePesquisa'));
        $objExemploForm->setPaginaAtual($request->getParameter('paginaAtual'));
        $objExemploForm->setTotalRegistros($request->getParameter('totalRegistros'));

        $smarty->assign("objExemploForm", $objExemploForm);
        $smarty->assign("arrayMessages", $actionMessages->messages);

        $myForward = $mapping->findForwardConfig('editarExemplo');
        return $myForward;
    }

    public function incluir($mapping, $form, &$request, &$response)
    {
        $smarty = parent::startSmarty($mapping, $form, $request, $response);
        $actionMessages = &$form->actionErrors;
        $pmr = &$this->pmr;
        $locale = &$this->locale;

        $objExemploForm = new ExemploForm();
        $objExemploFacade = new ExemploFacade();

        $actionMessages = $form->validate($mapping, $request);
        if ($actionMessages->isEmpty() == true) {
            try {

                $objExemploForm->transfereRequestForm($request->getPostVars());
                $objExemplo = $objExemploForm->transfereFormModel();
                $objExemploFacade->incluirExemplo($objExemplo);

                // Setando mensagem
                $args = array(
                    "Exemplo",
                );
                $msg = $pmr->getMessage($locale, 'inclusao.sucesso', $args);
                $actionMessages->add('sucesso', new ActionError($msg));
                $this->saveErrors($request, $actionMessages);

                // Redirecionando
                $myForward = $this->inicio($mapping, $form, $request, $response);
            } catch (Exception $e) {
                $this->telaCadastrar($mapping, $form, $request, $response);

                // Setando erro
                $args = array(
                    "Exemplo",
                );
                $msg = $pmr->getMessage($locale, 'inclusao.erro', $args);
                $actionMessages->add('erro', new ActionError($e->getMessage()));
                $this->saveErrors($request, $actionMessages);

                // Redirecionando
                $myForward = $mapping->findForwardConfig('editarExemplo');
            }
        } else {
            $this->telaCadastrar($mapping, $form, $request, $response);
            $myForward = $mapping->findForwardConfig('editarExemplo');
        }

        $smarty->assign("arrayMessages", $actionMessages->messages);
        return $myForward;
    }

    public function alterar($mapping, $form, &$request, &$response)
    {
        $smarty = parent::startSmarty($mapping, $form, $request, $response);
        $actionMessages = &$form->actionErrors;
        $pmr = &$this->pmr;
        $locale = &$this->locale;

        $objExemploForm = new ExemploForm();
        $objExemploFacade = new ExemploFacade();

        $actionMessages = $form->validate($mapping, $request);
        if ($actionMessages->isEmpty() == true) {
            try {

                $objExemploForm->transfereRequestForm($request->getPostVars());
                $objExemplo = $objExemploForm->transfereFormModel();

                $objExemploFacade->alterarExemplo($objExemplo);

                // Setando mensagem
                $args = array(
                    "Exemplo",
                );
                $msg = $pmr->getMessage($locale, 'alteracao.sucesso', $args);
                $actionMessages->add('sucesso', new ActionError($msg));
                $this->saveErrors($request, $actionMessages);

                // Redirecionando
                $myForward = $this->inicio($mapping, $form, $request, $response);
            } catch (Exception $e) {
                $this->telaCadastrar($mapping, $form, $request, $response);
                // Setando erro
                $args = array(
                    "Exemplo",
                );
                $msg = $pmr->getMessage($locale, 'alteracao.erro', $args);
                $actionMessages->add('erro', new ActionError($e->getMessage()));
                $this->saveErrors($request, $actionMessages);

                // Redirecionando
                $myForward = $mapping->findForwardConfig('editarExemplo');
            }
        } else {
            $this->telaCadastrar($mapping, $form, $request, $response);
            $myForward = $mapping->findForwardConfig('editarExemplo');
        }

        $smarty->assign("nomePesquisa", $request->getParameter("nomePesquisa"));

        $smarty->assign("arrayMessages", $actionMessages->messages);
        return $myForward;
    }

    public function excluir($mapping, $form, &$request, &$response)
    {
        $smarty = parent::startSmarty($mapping, $form, $request, $response);
        $actionMessages = &$form->actionErrors;
        $pmr = &$this->pmr;
        $locale = &$this->locale;

        $objExemploFacade = new ExemploFacade();

        try {
            $objExemploFacade->excluirExemplo($request->getParameter('codigo'));

            // Setando mensagem
            $args = array(
                "Exemplo",
            );
            $msg = $pmr->getMessage($locale, 'excluir.sucesso', $args);
            $actionMessages->add('sucesso', new ActionError($msg));
            $this->saveErrors($request, $actionMessages);

            // Redirecionando
            $myForward = $this->inicio($mapping, $form, $request, $response);
        } catch (Exception $e) {

            $objExemplo = $objExemploFacade->obterDadosExemplo($request->getParameter('codigo'));
            $objExemploForm->transfereModelForm($objExemplo);
            $objExemploForm->setAcao($form->getAcao());

            // Setando variavel no smarty
            $smarty->assign("objExemploForm", $objExemploForm);

            // Setando erro
            $args = array(
                "Exemplo",
            );
            $msg = $pmr->getMessage($locale, 'excluir.erro', $args);
            $actionMessages->add('erro', new ActionError($e->getMessage()));
            $this->saveErrors($request, $actionMessages);

            // Redirecionando
            $myForward = $mapping->findForwardConfig('exibirExemplo');
        }

        $objExemploForm->setNomePesquisa($request->getParameter("nomePesquisa"));

        $smarty->assign("arrayMessages", $actionMessages->messages);
        return $myForward;
    }

    public function telaCadastrar($mapping, $form, &$request, &$response)
    {
        $smarty = parent::startSmarty($mapping, $form, $request, $response);
        $actionMessages = &$form->actionErrors;
        $pmr = &$this->pmr;
        $locale = &$this->locale;

        $objExemploForm = new ExemploForm();
        try {
            $objExemploForm->transfereRequestForm($request->getPostVars());

            $smarty->assign("objExemploForm", $objExemploForm);
        } catch (Exception $e) {
            // Setando erro
            $args = array(
                "Exemplo",
            );

            $msg = $pmr->getMessage($locale, 'excluir.erro', $args);
            $actionMessages->add('erro', new ActionError($e->getMessage()));
            $this->saveErrors($request, $actionMessages);
        }
    }

    public function listarPaginado($mapping, $form, $request, $response)
    {
        $smarty = parent::startSmarty($mapping, $form, $request, $response);

        $actionMessages = &$form->actionErrors;
        $pmr = &$this->pmr;
        $locale = &$this->locale;

        try {

            $this->dadosPesquisa($mapping, $form, $request, $response);
        } catch (Exception $e) {
            $actionMessages->add('erro', new ActionError($e->getMessage()));
            $this->saveErrors($request, $actionMessages);
        }

        $smarty->assign("arrayMessages", $actionMessages->messages);
        $myForward = $mapping->findForwardConfig('listarExemplo');
        $display = $myForward->getPath();
        $myForward->path = "";
        $smarty->display($display);
        return $myForward;
    }

    public function dadosPesquisa($mapping, $form, $request, $response)
    {
        $smarty = parent::startSmarty($mapping, $form, $request, $response);

        $actionMessages = &$form->actionErrors;
        $pmr = &$this->pmr;
        $locale = &$this->locale;

        $objExemploForm = new ExemploForm();
        $objExemploFacade = new ExemploFacade();

        $nomePesquisa = ($request->getParameter("nomePesquisa"));

        try {
            // POG - substitui '*' da palavra e coloca espa�o.
            $nomePesquisa = str_replace("*", " ", $nomePesquisa);

            $enderecoBusca = "mudaPagina('" . $nomePesquisa . "',";
            $qtdPorPagina = 25;
            $paginaAtual = ($request->getParameter("paginaAtual") == null || $request->getParameter("paginaAtual") == "" ? "1" : $request->getParameter("paginaAtual"));
            $totalRegistros = ($request->getParameter("totalRegistros") == null || $request->getParameter("totalRegistros") == "" ? "0" : $request->getParameter("totalRegistros"));

            $objPagina = new Pagina($qtdPorPagina, $paginaAtual, $totalRegistros, $enderecoBusca);
            $objPagina = $objExemploFacade->listarExemploPaginado($nomePesquisa, $objPagina);
            $smarty->assign('pg', $objPagina);
        } catch (Exception $e) {
            $actionMessages->add('erro', new ActionError($e->getMessage()));
            $this->saveErrors($request, $actionMessages);
            $myForward = $mapping->findForwardConfig('listarExemplo');
        }
        $smarty->assign("nomePesquisa", $request->getParameter("nomePesquisa"));

        $smarty->assign("arrayMessages", $actionMessages->messages);
        $smarty->assign('objExemploForm', $objExemploForm);

        $myForward = $mapping->findForwardConfig('listarExemplo');
        return $myForward;
    }
}
