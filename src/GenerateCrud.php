<?php

class GenerateCrud
{
    public function createPasta($nome)
    {
        $nome = ucfirst($nome);

        // criando a pasta
        mkdir(__DIR__."/arquivosGerados/{$nome}/", 0777, true);

    }

    public function createAction(string $nome)
    {
        $nome = ucfirst($nome);

        // lendo todo o conteudo do arquivo Action
        $conteudoArquivoAction = file_get_contents('generico/Action.php');

        $conteudoArquivoAction = str_replace("_Action", "{$nome}Action", $conteudoArquivoAction);
        $conteudoArquivoAction = str_replace("_Form", "{$nome}Form", $conteudoArquivoAction);
        $conteudoArquivoAction = str_replace("_Facade", "{$nome}Facade", $conteudoArquivoAction);
        $conteudoArquivoAction = str_replace("_Model", "{$nome}", $conteudoArquivoAction);
        
        // criando o arquivo
        $arquivo = fopen("arquivosGerados/{$nome}/{$nome}Action.php", 'w+');

        // inserindo conteudo no arquivo criado
        fwrite($arquivo, $conteudoArquivoAction);

        // fechando arquivo
        fclose($arquivo);
    }

    public function createModel(string $nome, array $collectionAttr)
    {
        $nome = ucfirst($nome);

        $conteudo = "<?php\n\n";
        $conteudo .= "class $nome\n";
        $conteudo .= "{\n";

        foreach ($collectionAttr as $attr) {
            $conteudo .= "    private \${$attr['nomeCamelCase']};\n";
        }

        $conteudo .= "\n";

        $conteudo .= $this->createGetAndSet($collectionAttr);

        $conteudo .= "}";

        // criando o arquivo Model
        $arquivo = fopen("arquivosGerados/{$nome}/{$nome}.php", 'w+');

        // inserindo conteudo
        fwrite($arquivo, $conteudo);

        // fechando arquivo
        fclose($arquivo);
    }

    public function createForm(string $nome, array $collectionAttr)
    {
        $nome = ucfirst($nome);
        $attrPaginacao = [
            ["nomeCamelCase" => "acao"], 
            ["nomeCamelCase" => "nomePesquisa"], 
            ["nomeCamelCase" => "paginaAtual"], 
            ["nomeCamelCase" => "totalRegistros"]
        ];

        $conteudo = "<?php\n";
        $conteudo .= "include_once ROOT_WEBCORR_APLICACAO_VALOR.'/sistema/WEB-INF/classes/includeDinamico.php';\n\n";
        $conteudo .= "class {$nome}Form extends AbstractBaseForm\n";
        $conteudo .= "{\n";

        // criando attr
        $conteudo .= $this->createAttributes($collectionAttr);

        $conteudo .= "\n";
        $conteudo .= "    // PAGINACAO \n";

        $conteudo .= $this->createAttributes($attrPaginacao);

        $conteudo .= "\n";

        // criando get and set
        $conteudo .= $this->createGetAndSet($collectionAttr);
        $conteudo .= $this->createGetAndSet($attrPaginacao);

        // criando validate
        $conteudo .= $this->createValidate($collectionAttr);

        $conteudo .= "\n";

        // criando transfereRequestForm
        $conteudo .= $this->createTransfereRequestForm($collectionAttr, $attrPaginacao);

        $conteudo .= "\n";

        // criando transfereRequestForm
        $conteudo .= $this->createTransfereFormModel($nome, $collectionAttr);

        $conteudo .= "\n";

        // criando transfereModelForm
        $conteudo .= $this->createTransfereModelForm($nome, $collectionAttr);

        $conteudo .= "}";

        // criando o arquivo Model
        $arquivo = fopen("arquivosGerados/{$nome}/{$nome}Form.php", 'w+');

        // inserindo conteudo
        fwrite($arquivo, $conteudo);

        // fechando arquivo
        fclose($arquivo);
    }

    public function createDao(string $nome, string $prefixo, array $collectionAttr)
    {
        $nome = ucfirst($nome);

        $conteudo = "<?php\n";
        $conteudo .= "include_once ROOT_WEBCORR_APLICACAO_VALOR.'/sistema/WEB-INF/classes/includeDinamico.php';\n\n";
        $conteudo .= "class {$nome}DAO extends DAOFactory\n";
        $conteudo .= "{\n";

        // criando incluir
        $conteudo .= $this->createIncluirDao($nome, $prefixo, $collectionAttr);

        $conteudo .= "\n";

        // criando alterar
        $conteudo .= $this->createAlterarDao($nome, $prefixo, $collectionAttr);

        $conteudo .= "\n";

        // criando excluir
        $conteudo .= $this->createExcluirDao($nome, $prefixo);

        $conteudo .= "\n";

        // criando obter
        $conteudo .= $this->createObterDao($nome, $prefixo, $collectionAttr);

        $conteudo .= "\n";

        // criando obter
        $conteudo .= $this->createListarDao($nome, $prefixo, $collectionAttr);

        $conteudo .= "}";

        // criando o arquivo DAO
        $arquivo = fopen("arquivosGerados/{$nome}/{$nome}DAO.php", 'w+');

        // inserindo conteudo
        fwrite($arquivo, $conteudo);

        // fechando arquivo
        fclose($arquivo);
    }

    public function createFacade(string $nome, string $prefixo, array $collectionAttr)
    {
        $nome = ucfirst($nome);

        // lendo todo o conteudo do arquivo Action
        $conteudoArquivoFacade = file_get_contents('generico/Facade.php');

        $conteudoArquivoFacade = str_replace("_Nome", "{$nome}", $conteudoArquivoFacade);

        // criando o arquivo
        $arquivo = fopen("arquivosGerados/{$nome}/{$nome}Facade.php", 'w+');

        // inserindo conteudo no arquivo criado
        fwrite($arquivo, $conteudoArquivoFacade);

        // fechando arquivo
        fclose($arquivo);
    }

    public function createTable(string $nome, string $prefixo, array $collectionAttr)
    {
        $conLocal = pg_connect("host=localhost user=postgres password=acesse dbname=emploi");

        $nome = strtolower($nome);

        $sql = "CREATE TABLE IF NOT EXISTS tb_{$nome} ";
        $sql .= "( ";

        $sql .= " {$prefixo}_codigo SERIAL, ";

        for ($i = 0; $i < count($collectionAttr); $i++) {
            $attr = strtolower($collectionAttr[$i]['nome']);

            if($collectionAttr[$i]['obrigatorio'] == 1) {
                $sql .= "{$prefixo}_{$attr} {$collectionAttr[$i]['tipo']} NOT NULL";
            } else {
                $sql .= "{$prefixo}_{$attr} {$collectionAttr[$i]['tipo']}";
            }

            if ($i != count($collectionAttr) - 1) {
                $sql .= ", ";
            }
        }

        $sql .= ") ";

        pg_query($conLocal, $sql);
        pg_close($conLocal);

        return $sql;
    }

    public function createGetAndSet(array $collectionAttr)
    {
        $conteudo = "";
        foreach ($collectionAttr as $attr) {

            $nome = $attr["nomeCamelCase"];
            //Criando Get
            $conteudo .= "    public function get" . ucfirst($nome) . "()\n";
            $conteudo .= "    {\n";
            $conteudo .= "        return \$this->{$nome};\n";
            $conteudo .= "    }\n";

            $conteudo .= "\n";

            //Criando Set
            $conteudo .= "    public function set" . ucfirst($nome) . "(\${$nome})\n";
            $conteudo .= "    {\n";
            $conteudo .= "        \$this->{$nome} = \${$nome};\n";
            $conteudo .= "    }\n";

            $conteudo .= "\n";
        }

        return $conteudo;
    }

    public function createValidate(array $collectionAttr)
    {
        $conteudo = "    public function validate(&\$mapping, &\$request)\n";
        $conteudo .= "    {\n";
        $conteudo .= "        \$actionMessages = &\$this->actionErrors;\n";
        $conteudo .= "        \$pmr = &\$this->pmr;\n";
        $conteudo .= "        \$locale = &\$this->locale;\n\n";

        foreach ($collectionAttr as $attr) {
            if ($attr['nomeCamelCase'] != "codigo" && $attr['obrigatorio'] == 1) {
                $conteudo .= "        \${$attr['nomeCamelCase']} = trim(\$request->getParameter('{$attr["nomeCamelCase"]}'));\n";
            }
        }

        $conteudo .= "\n";

        foreach ($collectionAttr as $attr) {
            if ($attr['nomeCamelCase'] != "codigo" && $attr['obrigatorio'] == 1) {
                $conteudo .= "        if (trim(\${$attr['nomeCamelCase']}) == '') {\n";
                $conteudo .= "            \$args = array(\n";

                $attrNome = str_replace("_", " ", $attr["nome"]);

                $conteudo .= "                '" . ucwords($attrNome) . "',\n";
                $conteudo .= "            );\n";
                $conteudo .= "            \$msg = \$pmr->getMessage(\$locale, 'campo.null', \$args);\n";
                $conteudo .= "            \$actionMessages->add('erro', new ActionError(\$msg));\n";
                $conteudo .= "        }\n";
                $conteudo .= "\n";
            }
        }

        $conteudo .= "        \$this->saveErrors(\$request, \$actionMessages);\n";
        $conteudo .= "        \$this->saveFormBean(\$request, \$this);\n";
        $conteudo .= "        return \$actionMessages;\n";
        $conteudo .= "    }\n";

        return $conteudo;
    }

    public function createAttributes(array $collectionAttr)
    {
        $conteudo = "";
        foreach ($collectionAttr as $attr) {
            $conteudo .= "    private \${$attr['nomeCamelCase']};\n";
        }

        return $conteudo;
    }

    public function createTransfereRequestForm(array $collectionAttr, array $attrPaginacao)
    {
        $conteudo = "    public function transfereRequestForm(\$request)\n";
        $conteudo .= "    {\n";

        foreach ($collectionAttr as $attr) {
            $conteudo .= "        \$this->set" . ucfirst($attr['nomeCamelCase']) . "(trim(\$request['" . $attr["nomeCamelCase"] . "']));\n";
        }

        $conteudo .= "\n";
        $conteudo .= "        // PAGINACAO \n";

        foreach ($attrPaginacao as $attr) {
            $conteudo .= "        \$this->set" . ucfirst($attr['nomeCamelCase']) . "(trim(\$request['" . $attr['nomeCamelCase'] . "']));\n";
        }

        $conteudo .= "    }\n";

        return $conteudo;
    }

    public function createTransfereFormModel(string $nome, array $collectionAttr)
    {
        $conteudo = "    public function transfereFormModel()\n";
        $conteudo .= "    {\n";

        $conteudo .= "        \$obj" . ucfirst($nome) . " = new " . ucfirst($nome) . "();\n\n";

        foreach ($collectionAttr as $attr) {
            $conteudo .= "        \$obj" . ucfirst($nome) . "->set" . ucfirst($attr['nomeCamelCase']) . "(\$this->get" . ucfirst($attr['nomeCamelCase']) . "());\n";
        }

        $conteudo .= "\n";

        $conteudo .= "        return \$obj" . ucfirst($nome) . ";\n";

        $conteudo .= "    }\n";

        return $conteudo;
    }

    public function createTransfereModelForm(string $nome, array $collectionAttr)
    {
        $nomeUcFirst = ucfirst($nome);
        $conteudo = "    public function transfereModelForm(\$obj{$nomeUcFirst})\n";
        $conteudo .= "    {\n";

        foreach ($collectionAttr as $attr) {
            $attrUcFirst = ucfirst($attr["nomeCamelCase"]);
            $conteudo .= "        \$this->set{$attrUcFirst}(\$obj{$nomeUcFirst}->get{$attrUcFirst}());\n";
        }

        $conteudo .= "    }\n";

        return $conteudo;
    }

    public function createBindParam(string $nome, string $nomeFuncao, array $collectionAttr)
    {
        $conteudo = "";

        for ($i = 0; $i < count($collectionAttr); $i++) {
            $attrUcFirst = ucfirst($collectionAttr[$i]["nomeCamelCase"]);

            if ($collectionAttr[$i]["tipo"] == "VARCHAR" || $collectionAttr[$i]["tipo"] == "DATE") {
                $tipo = "PDO::PARAM_STR";
            } else {
                $tipo = "PDO::PARAM_INT";
            }

            $conteudo .= "        \$query->bindParam(':{$collectionAttr[$i]["nomeCamelCase"]}', \$obj{$nome}->get{$attrUcFirst}(), {$tipo});\n";
        }

        $conteudo .= "\n";

        $conteudo .= "        if (!\$query->execute()) {\n";
        $conteudo .= "            \$collectionErro = \$query->errorInfo();\n";
        $conteudo .= "            throw new Exception('{$nome}DAO->{$nomeFuncao}{$nome} ' . \$collectionErro[2]);\n";
        $conteudo .= "        }\n\n";

        $conteudo .= "        return true;\n";

        return $conteudo;
    }

    public function createIncluirDao(string $nome, string $prefixo, array $collectionAttr)
    {
        $nomeUcFirst = ucfirst($nome);
        $nomeLower = strtolower($nome);
        $conteudo = "    public function incluir{$nomeUcFirst}(\$obj{$nomeUcFirst})\n";
        $conteudo .= "    {\n";

        $conteudo .= "        \$sql = 'INSERT INTO construtora.tb_{$nomeLower} ';\n";
        $conteudo .= "        \$sql .= '( ';\n";

        for ($i = 0; $i < count($collectionAttr); $i++) {
            $conteudo .= "        \$sql .= '    {$prefixo}_{$collectionAttr[$i]['nome']}";

            if ($i != count($collectionAttr) - 1) {
                $conteudo .= ", ';\n";
            } else {
                $conteudo .= " ';\n";
            }
        }

        $conteudo .= "        \$sql .= ') ';\n";
        $conteudo .= "        \$sql .= 'VALUES ';\n";
        $conteudo .= "        \$sql .= '( ';\n";

        for ($i = 0; $i < count($collectionAttr); $i++) {
            $conteudo .= "        \$sql .= '    :{$collectionAttr[$i]['nomeCamelCase']}";

            if ($i != count($collectionAttr) - 1) {
                $conteudo .= ", ';\n";
            } else {
                $conteudo .= " ';\n";
            }
        }

        $conteudo .= "        \$sql .= ') ';\n\n";

        $conteudo .= "        \$query = parent::\$connection->pdo->prepare(\$sql);\n\n";

        $conteudo .= $this->createBindParam($nomeUcFirst, "incluir", $collectionAttr);

        $conteudo .= "    }\n";

        return $conteudo;
    }

    public function createAlterarDao(string $nome, string $prefixo, array $collectionAttr)
    {
        $nomeUcFirst = ucfirst($nome);
        $nomeLower = strtolower($nome);
        $conteudo = "    public function alterar{$nomeUcFirst}(\$obj{$nomeUcFirst})\n";
        $conteudo .= "    {\n";

        $conteudo .= "        \$sql = 'UPDATE construtora.tb_{$nomeLower} SET ';\n";

        for ($i = 0; $i < count($collectionAttr); $i++) {
            $conteudo .= "        \$sql .= '    {$prefixo}_{$collectionAttr[$i]['nome']} = :{$collectionAttr[$i]["nomeCamelCase"]}";

            if ($i != count($collectionAttr) - 1) {
                $conteudo .= ", ';\n";
            } else {
                $conteudo .= " ';\n";
            }
        }


        $conteudo .= "        \$sql .= 'WHERE ';\n";
        $conteudo .= "        \$sql .= '    {$prefixo}_{$nomeLower} = :codigo{$nomeUcFirst} ';\n\n";

        $conteudo .= "        \$query = parent::\$connection->pdo->prepare(\$sql);\n\n";

        $conteudo .= "        \$query->bindParam(':codigo{$nomeUcFirst}', \$obj{$nomeUcFirst}->getCodigo(), PDO::PARAM_INT);\n";

        $conteudo .= $this->createBindParam($nomeUcFirst, "alterar", $collectionAttr);

        $conteudo .= "    }\n";

        return $conteudo;
    }

    public function createExcluirDao(string $nome, string $prefixo)
    {
        $nomeUcFirst = ucfirst($nome);
        $nomeLower = strtolower($nome);
        $conteudo = "    public function excluir{$nomeUcFirst}(\$codigo)\n";
        $conteudo .= "    {\n";

        $conteudo .= "        \$sql = 'DELETE FROM construtora.tb_{$nomeLower} ';\n";

        $conteudo .= "        \$sql .= 'WHERE ';\n";
        $conteudo .= "        \$sql .= '    {$prefixo}_codigo = :codigo{$nomeUcFirst} ';\n\n";

        $conteudo .= "        \$query = parent::\$connection->pdo->prepare(\$sql);\n\n";

        $conteudo .= "        \$query->bindParam(':codigo{$nomeUcFirst}', \$codigo, PDO::PARAM_INT);\n";

        $conteudo .= "\n";

        $conteudo .= "        if (!\$query->execute()) {\n";
        $conteudo .= "            \$collectionErro = \$query->errorInfo();\n";
        $conteudo .= "            throw new Exception('{$nomeUcFirst}DAO->excluir{$nomeUcFirst} ' . \$collectionErro[2]);\n";
        $conteudo .= "        }\n\n";

        $conteudo .= "        return true;\n";
        $conteudo .= "    }\n";

        return $conteudo;
    }

    public function createObterDao(string $nome, string $prefixo, array $collectionAttr)
    {
        $nomeUcFirst = ucfirst($nome);
        $nomeLower = strtolower($nome);
        $conteudo = "    public function obter{$nomeUcFirst}(\$codigo)\n";
        $conteudo .= "    {\n";

        $conteudo .= "        \$sql = 'SELECT ';\n";
        
        for ($i = 0; $i < count($collectionAttr); $i++) {
            $attrToLower = strtolower($collectionAttr[$i]["nome"]);

            $conteudo .= "        \$sql .= '    {$prefixo}_{$attrToLower}";

            if ($i != count($collectionAttr) - 1) {
                $conteudo .= ", ';\n";
            } else {
                $conteudo .= " ';\n";
            }
        }

        $conteudo .= "        \$sql .= 'FROM construtora.tb_{$nomeLower} ';\n";

        $conteudo .= "        \$sql .= 'WHERE ';\n";
        $conteudo .= "        \$sql .= '    {$prefixo}_codigo = :codigo{$nomeUcFirst} ';\n\n";

        $conteudo .= "        \$query = parent::\$connection->pdo->prepare(\$sql);\n\n";

        $conteudo .= "        \$query->bindParam(':codigo{$nomeUcFirst}', \$codigo, PDO::PARAM_INT);\n";

        $conteudo .= "\n";

        $conteudo .= "        if (\$query->execute()) {\n";
        $conteudo .= "            \$rs = \$query->fetch(PDO::FETCH_ASSOC);\n";
        $conteudo .= "            \$obj{$nomeUcFirst} = new {$nomeUcFirst}();\n\n";

        $conteudo .= "            \$obj{$nomeUcFirst}->setCodigo(\$rs['{$prefixo}_codigo']);\n";

        foreach ($collectionAttr as $attr) {
            $attrToUpper = ucfirst($attr['nomeCamelCase']);
            $attrToLower = strtolower($attr['nome']);
            $conteudo .= "            \$obj{$nomeUcFirst}->set{$attrToUpper}(\$rs['{$prefixo}_{$attrToLower}']);\n";
        }

        $conteudo .= "        } else {\n";

        $conteudo .= "            \$collectionErro = \$query->errorInfo();\n";
        $conteudo .= "            throw new Exception('{$nomeUcFirst}DAO->obter{$nomeUcFirst} ' . \$collectionErro[2]);\n";

        $conteudo .= "        }\n\n";

        $conteudo .= "        return \$obj{$nomeUcFirst};\n";
        $conteudo .= "    }\n";

        return $conteudo;
    }

    public function createListarDao(string $nome, string $prefixo, array $collectionAttr)
    {
        $nomeUcFirst = ucfirst($nome);
        $nomeLower = strtolower($nome);
        $conteudo = "    public function listar{$nomeUcFirst}()\n";
        $conteudo .= "    {\n";

        $conteudo .= "        \$sql = 'SELECT ';\n";
        
        for ($i = 0; $i < count($collectionAttr); $i++) {
            $attrToLower = strtolower($collectionAttr[$i]["nome"]);

            $conteudo .= "        \$sql .= '    {$prefixo}_{$attrToLower}";

            if ($i != count($collectionAttr) - 1) {
                $conteudo .= ", ';\n";
            } else {
                $conteudo .= " ';\n";
            }
        }

        $conteudo .= "        \$sql .= 'FROM construtora.tb_{$nomeLower} ';\n\n";

        $conteudo .= "        \$query = parent::\$connection->pdo->prepare(\$sql);\n";

        $conteudo .= "\n";

        $conteudo .= "        \$collection{$nomeUcFirst} = array();\n";
        $conteudo .= "        if (\$query->execute()) {\n";
        $conteudo .= "            while(\$rs = \$query->fetch(PDO::FETCH_ASSOC)) {\n";
        $conteudo .= "                \$obj{$nomeUcFirst} = new {$nomeUcFirst}();\n\n";

        $conteudo .= "                \$obj{$nomeUcFirst}->setCodigo(\$rs['{$prefixo}_codigo']);\n";

        foreach ($collectionAttr as $attr) {
            $attrToUpper = ucfirst($attr['nomeCamelCase']);
            $attrToLower = strtolower($attr['nome']);
            $conteudo .= "                \$obj{$nomeUcFirst}->set{$attrToUpper}(\$rs['{$prefixo}_{$attrToLower}']);\n";
        }

        $conteudo .= "                array_push(\$collection{$nomeUcFirst}, \$obj{$nomeUcFirst});\n";
        $conteudo .= "            }\n";
        $conteudo .= "        } else {\n";

        $conteudo .= "            \$collectionErro = \$query->errorInfo();\n";
        $conteudo .= "            throw new Exception('{$nomeUcFirst}DAO->listar{$nomeUcFirst} ' . \$collectionErro[2]);\n";

        $conteudo .= "        }\n\n";

        $conteudo .= "        return \$collection{$nomeUcFirst};\n";
        $conteudo .= "    }\n";

        return $conteudo;
    }
}
