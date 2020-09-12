<?php
include_once ROOT_WEBCORR_APLICACAO_VALOR . '/sistema/WEB-INF/classes/includeDinamico.php';

class _Action extends DefaultAction
{
    public function _Action()
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

            $myForward = $mapping->findForwardConfig('pesquisar_Model');
        } catch (Exception $e) {
            // Setando erro
            $actionMessages->add('erro', new ActionError($e->getMessage()));
            $this->saveErrors($request, $actionMessages);
            $smarty->assign("arrayMessages", $actionMessages->messages);

            // Redirecionando
            $myForward = $mapping->findForwardConfig('pesquisar_Model');
        }

        return $myForward;
    }

    public function editar($mapping, $form, &$request, &$response)
    {
        $smarty = parent::startSmarty($mapping, $form, $request, $response);
        $actionMessages = &$form->actionErrors;
        $pmr = &$this->pmr;
        $locale = &$this->locale;

        $obj_Form = new _Form();
        $obj_Facade = new _Facade();

        try {
            if ($request->getParameter('acao') != "I") {
                $obj_Model = $obj_Facade->obter_Model($request->getParameter('codigo'));
                $obj_Form->transfereModelForm($obj_Model);
            }
        } catch (Exception $e) {
            // Setando erro
            $msg = $pmr->getMessage($locale, 'editar.erro');
            $actionMessages->add('erro', new ActionError($e->getMessage()));
            $this->saveErrors($request, $actionMessages);

            // Redirecionando
            $myForward = $mapping->findForwardConfig('pesquisar_Model');
        }
            
        // Paginacao
        $obj_Form->setAcao($request->getParameter('acao'));
        $obj_Form->setNomePesquisa($request->getParameter('nomePesquisa'));
        $obj_Form->setPaginaAtual($request->getParameter('paginaAtual'));
        $obj_Form->setTotalRegistros($request->getParameter('totalRegistros'));

        $smarty->assign("obj_Form", $obj_Form);
        $smarty->assign("arrayMessages", $actionMessages->messages);

        $myForward = $mapping->findForwardConfig('editar_Model');
        return $myForward;
    }

    public function incluir($mapping, $form, &$request, &$response)
    {
        $smarty = parent::startSmarty($mapping, $form, $request, $response);
        $actionMessages = &$form->actionErrors;
        $pmr = &$this->pmr;
        $locale = &$this->locale;

        $obj_Form = new _Form();
        $obj_Facade = new _Facade();

        $actionMessages = $form->validate($mapping, $request);
        if ($actionMessages->isEmpty() == true) {
            try {

                $obj_Form->transfereRequestForm($request->getPostVars());
                $obj_Model = $obj_Form->transfereFormModel();
                $obj_Facade->incluir_Model($obj_Model);

                // Setando mensagem
                $args = array(
                    "_Model",
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
                    "_Model",
                );
                $msg = $pmr->getMessage($locale, 'inclusao.erro', $args);
                $actionMessages->add('erro', new ActionError($e->getMessage()));
                $this->saveErrors($request, $actionMessages);

                // Redirecionando
                $myForward = $mapping->findForwardConfig('editar_Model');
            }
        } else {
            $this->telaCadastrar($mapping, $form, $request, $response);
            $myForward = $mapping->findForwardConfig('editar_Model');
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

        $obj_Form = new _Form();
        $obj_Facade = new _Facade();

        $actionMessages = $form->validate($mapping, $request);
        if ($actionMessages->isEmpty() == true) {
            try {

                $obj_Form->transfereRequestForm($request->getPostVars());
                $obj_Model = $obj_Form->transfereFormModel();

                $obj_Facade->alterar_Model($obj_Model);

                // Setando mensagem
                $args = array(
                    "_Model",
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
                    "_Model",
                );
                $msg = $pmr->getMessage($locale, 'alteracao.erro', $args);
                $actionMessages->add('erro', new ActionError($e->getMessage()));
                $this->saveErrors($request, $actionMessages);

                // Redirecionando
                $myForward = $mapping->findForwardConfig('editar_Model');
            }
        } else {
            $this->telaCadastrar($mapping, $form, $request, $response);
            $myForward = $mapping->findForwardConfig('editar_Model');
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

        $obj_Facade = new _Facade();

        try {
            $obj_Facade->excluir_Model($request->getParameter('codigo'));

            // Setando mensagem
            $args = array(
                "_Model",
            );
            $msg = $pmr->getMessage($locale, 'excluir.sucesso', $args);
            $actionMessages->add('sucesso', new ActionError($msg));
            $this->saveErrors($request, $actionMessages);

            // Redirecionando
            $myForward = $this->inicio($mapping, $form, $request, $response);
        } catch (Exception $e) {

            $obj_Model = $obj_ModelFacade->obterDados_Model($request->getParameter('codigo'));
            $obj_Form->transfereModelForm($obj_Model);
            $obj_Form->setAcao($form->getAcao());

            // Setando variavel no smarty
            $smarty->assign("obj_Form", $obj_Form);

            // Setando erro
            $args = array(
                "_Model",
            );
            $msg = $pmr->getMessage($locale, 'excluir.erro', $args);
            $actionMessages->add('erro', new ActionError($e->getMessage()));
            $this->saveErrors($request, $actionMessages);

            // Redirecionando
            $myForward = $mapping->findForwardConfig('exibir_Model');
        }

        $obj_Form->setNomePesquisa($request->getParameter("nomePesquisa"));

        $smarty->assign("arrayMessages", $actionMessages->messages);
        return $myForward;
    }

    public function telaCadastrar($mapping, $form, &$request, &$response)
    {
        $smarty = parent::startSmarty($mapping, $form, $request, $response);
        $actionMessages = &$form->actionErrors;
        $pmr = &$this->pmr;
        $locale = &$this->locale;

        $obj_Form = new _Form();
        try {
            $obj_Form->transfereRequestForm($request->getPostVars());

            $smarty->assign("obj_Form", $obj_Form);
        } catch (Exception $e) {
            // Setando erro
            $args = array(
                "_Model",
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
        $myForward = $mapping->findForwardConfig('listar_Model');
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

        $obj_Form = new _Form();
        $obj_Facade = new _Facade();

        $nomePesquisa = ($request->getParameter("nomePesquisa"));

        try {
            // POG - substitui '*' da palavra e coloca espa�o.
            $nomePesquisa = str_replace("*", " ", $nomePesquisa);

            $enderecoBusca = "mudaPagina('" . $nomePesquisa . "',";
            $qtdPorPagina = 25;
            $paginaAtual = ($request->getParameter("paginaAtual") == null || $request->getParameter("paginaAtual") == "" ? "1" : $request->getParameter("paginaAtual"));
            $totalRegistros = ($request->getParameter("totalRegistros") == null || $request->getParameter("totalRegistros") == "" ? "0" : $request->getParameter("totalRegistros"));

            $objPagina = new Pagina($qtdPorPagina, $paginaAtual, $totalRegistros, $enderecoBusca);
            $objPagina = $obj_Facade->listar_ModelPaginado($nomePesquisa, $objPagina);
            $smarty->assign('pg', $objPagina);
        } catch (Exception $e) {
            $actionMessages->add('erro', new ActionError($e->getMessage()));
            $this->saveErrors($request, $actionMessages);
            $myForward = $mapping->findForwardConfig('listar_Model');
        }
        $smarty->assign("nomePesquisa", $request->getParameter("nomePesquisa"));

        $smarty->assign("arrayMessages", $actionMessages->messages);
        $smarty->assign('obj_Form', $obj_Form);

        $myForward = $mapping->findForwardConfig('listar_Model');
        return $myForward;
    }
}
