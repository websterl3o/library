# Library

![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Vue.js](https://img.shields.io/badge/Vue.js-35495E?style=for-the-badge&logo=vuedotjs&logoColor=4FC08D)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Pest](https://img.shields.io/badge/Pest-F22F46?style=for-the-badge&logo=pest&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)
![DOMPDF](https://img.shields.io/badge/DOMPDF-ff0000?style=for-the-badge&logo=adobe-acrobat-reader&logoColor=white)

Aplicação web para cadastro e gerenciamento de Livros, Autores e Assuntos. O projeto implementa um CRUD completo baseado em uma modelagem de dados relacional estrita, incorporando requisitos sistêmicos como formatação rigorosa de dados monetários, tratamento de exceções em nível de persistência e geração de relatórios otimizados através de Views no banco de dados.

---

## 🎯 Objetivos

O projeto foi arquitetado com foco em **escalabilidade**, **manutenibilidade** e **qualidade de código**, atendendo aos seguintes critérios de aceite:

* **Gestão de Entidades (CRUD):** Implementação completa para `Livro`, `Autor` e `Assunto`, respeitando os relacionamentos (N:N) estabelecidos no diagrama original.
* **Extensão de Domínio:** Inclusão do campo monetário `Valor (R$)` na entidade `Livro`, com tratamento adequado de *casting* e formatação (Mutators/Accessors).
* **Performance e Persistência:** Geração de um relatório agrupado por autor, construído obrigatoriamente a partir de uma `View` no banco de dados, minimizando o processamento em camada de aplicação.
* **Resiliência (Error Handling):** Tratamento de exceções centralizado, isolando erros de banco de dados (ex: violação de *Foreign Key*) para evitar `Try/Catch` genéricos e retornar *feedbacks* precisos ao usuário.
* **Arquitetura em Camadas:** Adoção estrita do padrão `Controller -> Service -> Repository` visando desacoplamento, Single Responsibility Principle (SRP) e alta testabilidade.
* **Confiabilidade (TDD):** Cobertura de testes unitários e *Feature Tests* conduzidos com o framework Pest, garantindo o comportamento das regras de negócio.
* **Interface (UI/UX):** Interface responsiva e padronizada utilizando Vue.js e Tailwind CSS, garantindo feedback visual claro para ações de sucesso ou falha.

---

## 🏗️ Estrutura e Ecossistema

O repositório adota uma separação de responsabilidades (Separation of Concerns) que isola a aplicação Laravel dos artefatos de infraestrutura, da infraestrutura e dos scripts de automação.

```text
Library
├── src/          # Código-fonte da aplicação (Laravel 11+, Vue 3)
├── infra/        # Configurações dos contêineres (PHP, Nginx e MySQL)
├── scripts/      # Script de setup do ambiente (setup.sh)
```


### Componentes do Ecossistema

| Camada             | Tecnologia     | Propósito / Decisão Arquitetural                                                                                                        |
| ------------------ | -------------- | --------------------------------------------------------------------------------------------------------------------------------------- |
| **Backend**        | Laravel (PHP)  | Fornecer o core da lógica de negócio, ORM (Eloquent), roteamento e tratamento de exceções de forma padronizada.                         |
| **Design Pattern** | Service/Repo   | Abstração do acesso a dados (`Repositories`) e regras de negócio (`Services`), mantendo controladores magros.                           |
| **Exportação PDF** | DOMPDF         | Geração do relatório consolidado de autores e livros consumindo diretamente da View SQL do banco para altíssima performance.            |
| **Frontend**       | Vue.js 3       | Interface reativa orientada a componentes. Integrado via *Inertia.js* para atuar como SPA eliminando o overhead de API e estado global. |
| **Estilização**    | Tailwind CSS   | Construção ágil de interfaces sob medida através de classes utilitárias.                                                                |
| **Testes**         | Pest PHP       | Framework para execução do TDD com sintaxe focada em legibilidade e velocidade.                                                         |
| **Infraestrutura** | Docker Compose | Isolamento total de dependências garantindo paridade entre desenvolvimento e produção.                                                  |
| **Banco de Dados** | MySQL 8.0      | Motor relacional responsável pela persistência, integridade referencial e hospedagem da *View* do relatório.                            |

---

## ⚙️ Pré-requisitos

O ambiente de desenvolvimento foi projetado para ser 100% isolado via contêineres. Para executar o projeto, são exigidos:

* [Git](https://git-scm.com/)
* [Docker](https://www.docker.com/products/docker-desktop/) (Engine 24.0+)
* [Docker Compose](https://docs.docker.com/compose/) (V2)

---

## 🚀 Passo a Passo de Instalação (Setup)

Todos os scripts e instruções para a implantação foram mapeados e organizados para demonstração na apresentação técnica.

**1. Clonar o repositório**

```bash
git clone https://github.com/websterl3o/library.git
cd library

```

**2. Configurar variáveis de ambiente**
Copie o arquivo de exemplo para o diretório da aplicação (`src/`):

```bash
cp src/.env.example src/.env

```

**3. Subir a infraestrutura (Docker)**
Inicie os contêineres em *background*:

```bash
docker compose up -d --build

```

**4. Instalar dependências e inicializar a aplicação**

```bash
# Instalar dependências do PHP e Node
docker compose exec app composer install
docker compose exec app npm install

# Gerar chave da aplicação
docker compose exec app php artisan key:generate

# Executar as migrations (incluindo a View do relatório) e popular o banco
docker compose exec app php artisan migrate:fresh --seed

# Compilar os assets do Frontend via Vite
docker compose exec app npm run build

```

**5. Acessar a aplicação**

* **Web:** `http://localhost:8000`

**6. Testes**
* **Testes (TDD):** Para validar a suíte automatizada:
```bash
docker compose exec app php artisan test
```

* **Relatório de Cobertura (Coverage):** Para gerar o relatório visual em HTML da cobertura de testes (`src/coverage-report`):
```bash
docker compose exec app php -d pcov.enabled=1 artisan test --coverage-html=coverage-report
```
