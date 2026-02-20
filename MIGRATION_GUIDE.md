# Guia - Sistema de Especificações Flexível

## 📋 O que é?

Um sistema flexível de especificações para produtos baseado em tipos de especificações.

Estrutura:
- **SpecType**: Define tipos de especificações (Cor, RAM, Storage, Voltagem, Bateria, etc.)
- **Spec**: Valores específicos (Vermelho, 8GB, 256GB, 110V, 5000mAh, etc.)
- **ProductVariantSpec**: Relaciona specs com variantes (chave-valor por variante)

## 🚀 Como Usar no Filament

### 1. Cadastrar Tipos de Especificação
**Acesse: Catalogo > Tipos de Especificações**

Campos:
- **Nome**: Ex: "Cor", "Bateria", "Processador", "Tamanho de Tela"
- **Selecionável no frontend**: Marque se deve aparecer como filtro
- **Ordem de exibição**: Controla a ordem na interface

### 2. Editar Variante de Produto
**Acesse: Catalogo > Product Variants**

1. Preencha produto, SKU, preço e estoque
2. Na seção **Especificações** (aba):
   - Clique em **+ Adicionar Especificação**
   - Selecione o **Tipo de Especificação**
   - Preencha o **Valor**
   - Salve
   
Exemplo:
```
Variante iPhone 15 Pro
Tipo: Cor          | Valor: Preto
Tipo: RAM          | Valor: 8GB  
Tipo: Storage      | Valor: 256GB
```

## 💡 Exemplos de Uso

### Exemplo 1: Smartphone
```
Produto: iPhone 15

Variante 1 (Preto 8GB/256GB):
  - Cor: Preto
  - Memória RAM: 8GB
  - Armazenamento: 256GB
  Preço: R$ 5.999,00

Variante 2 (Azul 8GB/512GB):
  - Cor: Azul
  - Memória RAM: 8GB
  - Armazenamento: 512GB
  Preço: R$ 6.999,00
```

### Exemplo 2: Geladeira (produto sem RAM/Storage)
```
Produto: Geladeira Brastemp

Variante 1 (Branca 110V):
  - Cor: Branca
  - Voltagem: 110V
  - Capacidade: 400L
  Preço: R$ 2.999,00

Variante 2 (Inox Bivolt):
  - Cor: Inox
  - Voltagem: Bivolt
  - Capacidade: 400L
  Preço: R$ 3.299,00
```

### Exemplo 3: Notebook com Processador
```
Produto: Notebook Gamer

Variante 1:
  - Cor: Prata
  - Memória RAM: 16GB
  - Armazenamento: 512GB SSD
  - Processador: Intel i7-12700H
  - GPU: RTX 3060
  Preço: R$ 8.999,00
```

## 📊 Estrutura do Banco de Dados

```
spec_types
├── id
├── name (ex: "Cor", "RAM", "Voltagem")
├── is_selectable (controla filtros no frontend)
└── display_order

specs
├── id
├── spec_type_id
├── value (ex: "Preto", "8GB", "110V")
└── unique(spec_type_id, value)

product_variant_specs (pivot)
├── id
├── product_variant_id
├── spec_id
└── unique(product_variant_id, spec_id)
```

## 🎯 Vantagens

✅ **Sem duplicação**: Cada valor existe uma única vez na tabela specs  
✅ **Chave-valor**: Cada variante tem seus próprios pares tipo-valor  
✅ **Dinâmico**: Adicione novos tipos sem código  
✅ **Frontend-ready**: Campo `is_selectable` controla filtros
