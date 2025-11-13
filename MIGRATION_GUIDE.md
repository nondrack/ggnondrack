# 🚀 Guia de Migração - Novo Banco de Dados

## 📋 Visão Geral

Este guia orienta a migração do banco `shop2b` para o novo banco `ggnondrack_shop`, que possui uma estrutura otimizada e organizada.

## ✨ Melhorias Implementadas

### 1. **Unificação de Tabelas**
- ❌ Antes: Tabelas duplicadas `usuario` e `cliente`
- ✅ Agora: Tabela única `usuario` com campo `tipo` (cliente/admin)

### 2. **Nomenclatura Clara**
- ❌ Antes: Tabela `item` (ambígua)
- ✅ Agora: Tabela `item_venda` (descritiva)

### 3. **Timestamps Automáticos**
- `data_cadastro`: Preenchido automaticamente na criação
- `data_atualizacao`: Atualizado automaticamente em edições

### 4. **Tipos de Dados Corretos**
- ❌ Antes: `DOUBLE` para valores monetários
- ✅ Agora: `DECIMAL(10,2)` para precisão exata

### 5. **Status Expandido**
- Antes: `aberta`, `paga`, `cancelada`
- Agora: `aberta`, `aguardando_pagamento`, `paga`, `enviada`, `entregue`, `cancelada`

### 6. **Índices para Performance**
- Adicionados índices em campos frequentemente consultados
- Melhora significativa na velocidade de queries

### 7. **Foreign Keys Nomeadas**
- Constraints com nomes descritivos (`fk_venda_usuario`, etc.)
- Facilita manutenção e debugging

## 📝 Passo a Passo da Migração

### Passo 1: Criar o Novo Banco

Execute o arquivo SQL no phpMyAdmin ou MySQL:

```bash
# Via phpMyAdmin:
1. Acesse http://localhost/phpmyadmin
2. Clique em "Importar"
3. Selecione: database_new.sql
4. Clique em "Executar"

# Via linha de comando:
mysql -u root -p < database_new.sql
```

### Passo 2: Migrar os Dados

Execute o script PHP de migração:

```bash
cd c:\xampp\htdocs\ggnondrack
php migrate_to_new_database.php
```

**Saída esperada:**
```
====================================
MIGRAÇÃO: shop2b → ggnondrack_shop
====================================

1. Migrando usuários...
   ✓ 6 usuários migrados

2. Migrando categorias...
   ✓ 5 categorias migradas

3. Migrando produtos...
   ✓ X produtos migrados

4. Migrando vendas...
   ✓ X vendas migradas

5. Migrando itens de venda...
   ✓ X itens migrados

====================================
✓ MIGRAÇÃO CONCLUÍDA COM SUCESSO!
====================================
```

### Passo 3: Verificar Configuração

O arquivo `config/Conexao.php` já foi atualizado para usar o novo banco:

```php
private static $db = "ggnondrack_shop"; // ✓ Atualizado
```

### Passo 4: Testar a Aplicação

1. **Login**: Teste com usuário existente
2. **Cadastro**: Crie novo usuário
3. **Produtos**: Navegue pelos produtos
4. **Carrinho**: Adicione produtos ao carrinho
5. **Checkout**: Finalize uma compra

## 🔧 Arquivos Atualizados

### Models
- ✅ `models/Venda.php` - Usa `usuario_id` e `item_venda`
- ✅ `models/Usuario.php` - Cadastro simplificado (sem duplicação)

### Views
- ✅ `views/carrinho/finalizar.php` - Usa `usuarioId` ao invés de `clienteId`

### Config
- ✅ `config/Conexao.php` - Aponta para `ggnondrack_shop`

## 📊 Estrutura do Novo Banco

```
ggnondrack_shop/
├── usuario           (unifica usuario + cliente)
│   ├── id (PK)
│   ├── nome
│   ├── email (UNIQUE)
│   ├── senha
│   ├── tipo (cliente/admin)
│   ├── ativo (S/N)
│   ├── data_cadastro
│   └── data_atualizacao
│
├── categoria
│   ├── id (PK)
│   ├── nome
│   ├── descricao
│   ├── ativo
│   └── data_cadastro
│
├── produto
│   ├── id (PK)
│   ├── nome
│   ├── descricao
│   ├── categoria_id (FK)
│   ├── preco (DECIMAL)
│   ├── estoque
│   ├── imagem
│   ├── ativo
│   ├── data_cadastro
│   └── data_atualizacao
│
├── venda
│   ├── id (PK)
│   ├── usuario_id (FK)
│   ├── status (expandido)
│   ├── metodo_pagamento
│   ├── valor_total (DECIMAL)
│   ├── txid
│   ├── data_criacao
│   ├── data_pagamento
│   ├── data_envio
│   ├── data_entrega
│   └── data_cancelamento
│
└── item_venda
    ├── id (PK)
    ├── venda_id (FK)
    ├── produto_id (FK)
    ├── quantidade
    ├── preco_unitario (DECIMAL)
    └── subtotal (DECIMAL)
```

## ⚠️ Notas Importantes

### Backup
Antes de migrar, faça backup do banco antigo:

```bash
mysqldump -u root -p shop2b > backup_shop2b.sql
```

### Rollback
Se algo der errado, você pode restaurar:

```bash
mysql -u root -p shop2b < backup_shop2b.sql
```

### Manter Banco Antigo
O script de migração NÃO deleta o banco `shop2b`. Você pode mantê-lo como backup ou removê-lo depois:

```sql
DROP DATABASE shop2b; -- Apenas após confirmar que tudo funciona
```

## 🎯 Benefícios

1. **Simplicidade**: Uma tabela para usuários ao invés de duas
2. **Performance**: Índices otimizados em campos chave
3. **Precisão**: Valores monetários com DECIMAL
4. **Rastreabilidade**: Timestamps automáticos
5. **Escalabilidade**: Estrutura preparada para crescimento
6. **Manutenção**: Código mais limpo e organizado

## 🆘 Problemas Comuns

### Erro: "Database ggnondrack_shop doesn't exist"
**Solução**: Execute o arquivo `database_new.sql` primeiro

### Erro: "Table 'venda' doesn't exist in shop2b"
**Solução**: Normal se o banco antigo não tinha vendas. A migração continua.

### Erro: Foreign Key Constraint
**Solução**: O script desabilita checks temporariamente. Se persistir, verifique se todos os IDs referenciados existem.

## 📞 Suporte

Se encontrar problemas durante a migração, verifique:

1. ✅ Banco `ggnondrack_shop` foi criado
2. ✅ Script de migração executou sem erros
3. ✅ `Conexao.php` aponta para banco correto
4. ✅ Permissões do MySQL estão corretas

---

**Data de criação**: 12 de novembro de 2025  
**Versão**: 1.0.0  
**Status**: ✅ Pronto para produção
