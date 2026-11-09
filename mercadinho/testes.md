# Registro de testes

| Situação | Entrada | Resultado esperado | Resultado observado | Situação |
|---|---|---|---|---|
| Cadastrar categoria e produto válidos | | Salvar e exibir nas listas | | |
| Enviar campos obrigatórios vazios | | Recusar e orientar | | |
| Preço negativo ou estoque fracionado | | Recusar sem alterar registros | | |
| Repetir código ou nome de categoria | | Impedir duplicidade com mensagem | | |
| Editar nome, preço e categoria | | Alterar só o produto escolhido | | |
| Buscar e filtrar produtos | | Exibir só os resultados correspondentes | | |
| Excluir categoria com produtos | | Impedir e explicar o vínculo | | |
| Cancelar e confirmar exclusão | | Preservar ao cancelar, remover ao confirmar | | |
| Abrir id inexistente ou inválido | | Informar sem erro interno | | |
| Reabrir a aplicação | | Dados continuam no banco | | |
| Texto com < e > | | Mostrar texto literal | | |
| Conferir estoque e validade | | Destacar estoque baixo e avisar vencido | | |