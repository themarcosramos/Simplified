# Planejamento de Testes

## Testes Unitários

### Models\ExtractTest
- Verifica atributos fillable
- Verifica se a classe tem o método `personable`

### Models\StoreTest
- Verifica atributos fillable
- Cadastro com sucesso
- Cadastro com e-mail duplicado
- Cadastro com documento duplicado
- Verifica se a classe tem o método `wallet`
- Verifica se a classe tem o método `extracts`
- Verifica se a classe **não** tem o método `transactions`

### Models\TransactionTest
- Verifica atributos fillable
- Cadastro com sucesso
- Cadastro com falha por dados obrigatórios ausentes
- Verifica se a classe tem o método `user`

### Models\UserTest
- Verifica atributos fillable
- Cadastro com sucesso
- Cadastro com e-mail duplicado
- Cadastro com documento duplicado
- Verifica se a classe tem o método `wallet`
- Verifica se a classe tem o método `transactions`
- Verifica se a classe tem o método `extracts`
- Verifica se dados sensíveis não são retornados

### Models\WalletTest
- Verifica atributos fillable
- Verifica se todos os campos de valor iniciam com zero
- Método para incrementar saldo disponível
- Método para decrementar saldo disponível
- Método para incrementar saldo bloqueado
- Método para decrementar saldo bloqueado
- Verifica se a classe tem o método `personable`

## Testes de Integração 

### Http\Controllers\Api\TransactionTest
- Acesso negado para usuários não autenticados
- Acesso negado para usuários sem permissão
- Listagem de transferências
- Falha quando pagador não tem saldo
- Falha com recebedor inexistente
- Falha com valor inferior ao permitido
- Transferência agendada
- Processamento de transferência não autorizada
- Falha na API de autorização
- Transferência autorizada entre usuários
- Falha na API de notificação
- Transferência autorizada entre usuário e loja
- Transferência não autorizada da loja para usuário
- Não permitir permissão duplicada para transferência concluída
- Autorizar transferência duplicada após cancelamento da primeira
- Transferência autorizada da loja para o usuário

