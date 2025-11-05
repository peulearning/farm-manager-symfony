# 🐄 Farm Manager — Sistema de Gestão de Fazendas e Gados

Projeto desenvolvido como parte do **teste técnico para Full Stack Web Developer**.
O sistema foi construído em **Symfony 6**, com foco em **boas práticas**, **organização de código**, e **regras de negócio claras e encapsuladas** nas entidades.

---

## 🚀 Tecnologias Utilizadas

- **PHP 8.2+**
- **Symfony 6 (WebApp)** — Estrutura MVC
- **Doctrine ORM** — Mapeamento objeto-relacional
- **Twig** — Templates e views
- **Bootstrap** — Layout simples e responsivo
- **KnpPaginatorBundle** — Paginação de dados
- **Doctrine Fixtures** — Geração de dados fictícios
- **MySQL 8.0**

---

## ⚙️ Instalação e Execução do Projeto

### 1. Criar e configurar o projeto Symfony

```bash
composer create-project symfony/skeleton farm-manager-symfony
cd farm-manager-symfony
```

### 2. Instalar Dependências Essenciais

```
composer require webapp
composer require doctrine maker doctrine/migrations
composer require symfony/validator symfony/form
composer require knplabs/knp-paginator-bundle
composer require --dev orm-fixtures
```

### 3. Configuração de Banco de dados

No arquivo .env
```
DATABASE_URL="mysql://root:root@127.0.0.1:3306/farm_manager?serverVersion=8.0"
```

⚠️ Ajuste o usuário, senha e nome do banco de dados conforme sua configuração local.

### 4. Criar o Schema do Banco de Dados

```
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

---

### 5. Populando Banco de Dados

```
php bin/console doctrine:fixtures:load
```

---

### 6. Rodar Servidor Local
```
symfony server:start
```

Acesse: 👉 http://127.0.0.1:8000

---

## 🧩 7. Estrutura de Entidades e Relacionamentos

O sistema é composto por três entidades principais:

### 🧑‍⚕️ Veterinário
Responsável por acompanhar e supervisionar as fazendas.

| Campo | Tipo | Regras de Negócio |
|--------|------|-------------------|
| **id** | integer | Identificador único |
| **crmv** | string | Deve ser único |
| **nome** | string | Obrigatório |
| **fazendas** | ManyToMany | Um veterinário pode atender várias fazendas |

🔒 **Regra de Negócio:**
Não pode existir mais de um veterinário com o mesmo CRMV (`@UniqueEntity`).

---

### 🏡 Fazenda
Representa uma propriedade rural responsável pelos animais e supervisionada por veterinários.

| Campo | Tipo | Regras de Negócio |
|--------|------|-------------------|
| **id** | integer | Identificador único |
| **nome** | string | Único e obrigatório |
| **tamanho** | float | Deve ser positivo |
| **responsavel** | string | Obrigatório |
| **veterinarios** | ManyToMany | Pode possuir vários veterinários |
| **gados** | OneToMany | Contém vários animais (gados) |

🔒 **Regra de Negócio:**
Cada fazenda deve ter um nome único e ao adicionar um gado, a fazenda é automaticamente associada.

---

### 🐄 Gado
Animal criado em uma fazenda, com dados de produção e condições de abate.

| Campo | Tipo | Regras de Negócio |
|--------|------|-------------------|
| **id** | integer | Identificador único |
| **codigo** | string | Único e obrigatório |
| **leite** | float | Produção diária em litros |
| **racao** | float | Consumo diário em kg |
| **peso** | float | Peso em kg |
| **dataNascimento** | date | Obrigatório |
| **vivo** | boolean | Define se está vivo |
| **dataAbate** | datetime (nullable) | Data do abate |
| **fazenda** | ManyToOne | Associação obrigatória |

🔒 **Regras de Negócio (método `podeSerAbatido()`):**
Um animal pode ser abatido se atender **pelo menos uma** das condições:
- Idade > 5 anos
- Leite < 40 litros/dia
- Leite < 70 litros/dia **e** consome > 50 kg de ração/dia
- Peso > 18 arrobas (1 arroba = 15 kg)

---

## 🧠 8. Regras de Negócio Implementadas

| Entidade | Regra |
|-----------|-------|
| **Veterinário** | CRMV deve ser único |
| **Fazenda** | Nome deve ser único |
| **Gado** | Código deve ser único |
| **Gado** | Um gado deve pertencer a uma fazenda obrigatoriamente |
| **Gado** | Lógica de abate automática |
| **Fazenda** | Adiciona ou remove gados e mantém vínculo bidirecional |
| **Veterinário** | Pode estar vinculado a várias fazendas (ManyToMany) |

---

## 📊 9. Relatórios Implementados (Requisito 2.3)

Localizados na rota principal `/` e processados pelo **RelatorioController**.

| Relatório | Descrição |
|------------|------------|
| 🩸 **Animais Abatidos** | Lista todos os gados com `vivo = false` |
| 🥛 **Leite Produzido por Semana** | Soma total de `leite * 7` de todos os animais |
| 🌾 **Ração Necessária por Semana** | Soma total de `racao * 7` de todos os animais |
| 🐮 **Animais Jovens com Alto Consumo de Ração** | Gados com ≤ 1 ano e consumo > 500 kg/semana |

Cada relatório é exibido em uma **view Twig** com design limpo e intuitivo.

---

## 🧩 10. Estrutura do Projeto

```
src/
├── Controller/
│ ├── VeterinarioController.php
│ ├── FazendaController.php
│ ├── GadoController.php
│ └── RelatorioController.php
├── Entity/
│ ├── Veterinario.php
│ ├── Fazenda.php
│ └── Gado.php
├── Form/
│ ├── VeterinarioType.php
│ ├── FazendaType.php
│ └── GadoType.php
├── Repository/
│ ├── VeterinarioRepository.php
│ ├── FazendaRepository.php
│ └── GadoRepository.php
└── DataFixtures/
└── AppFixtures.php
```


---

## 🧩 11. Padrões e Boas Práticas

✅ **Clean Architecture:**
As entidades encapsulam as regras de negócio e o controller apenas orquestra as operações.

✅ **Doctrine ORM + Repositórios customizados:**
Consultas personalizadas e métodos reutilizáveis.

✅ **Validação automática:**
Regras declaradas via `Symfony\Validator` e `UniqueEntity`.

✅ **Feedback ao usuário:**
Mensagens amigáveis e visuais com Bootstrap e Flash Messages.

✅ **Paginação com KnpPaginatorBundle:**
Melhor desempenho em listagens extensas.

---

## 🧾 12. Rotas Principais

| Entidade | Caminho | Método | Descrição |
|-----------|----------|---------|------------|
| Veterinários | `/veterinarios/` | GET | Listagem |
| 〃 | `/veterinarios/new` | GET/POST | Criação |
| Fazendas | `/fazendas/` | GET | Listagem |
| 〃 | `/fazendas/new` | GET/POST | Criação |
| Gados | `/gados/` | GET | Listagem |
| 〃 | `/gados/new` | GET/POST | Criação |
| Relatórios | `/` | GET | Tela inicial com relatórios |

---

## 🧮 13. Cálculos Implementados

| Método | Descrição | Exemplo |
|---------|------------|----------|
| `getLeiteSemana()` | Leite diário × 7 | 15L/dia → 105L/semana |
| `getRacaoSemana()` | Ração diária × 7 | 60kg/dia → 420kg/semana |
| `getPesoArroba()` | Peso ÷ 15 | 450kg → 30 arrobas |
| `getIdadeAnos()` | Idade atual em anos | 2017 → 8 anos |

---

## 🗂️ 14. Banco de Dados — Modelo Lógico

```
Veterinario (id, nome, crmv)
⬋⬊ ManyToMany
Fazenda (id, nome, tamanho, responsavel)
⬋⬊ OneToMany
Gado (id, codigo, leite, racao, peso, dataNascimento, vivo, dataAbate, fazenda_id)
```


---

## 🧾 15. Autor

👨‍💻 **Pedro Henrique Araújo Mattos Ribeiro**
💡 Desenvolvedor Web Full Stack
---
