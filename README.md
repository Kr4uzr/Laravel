# Task API

API REST para gerenciamento de tarefas desenvolvida em Laravel 12.

## Tecnologias

- PHP 8.3
- Laravel 12
- MySQL 8.0
- Docker e Docker Compose
- PHPUnit (testes)

## Requisitos

- Docker
- Docker Compose
- Git

## Instalação

Clone o repositório e entre na pasta do projeto:

```bash
git clone https://github.com/Kr4uzr/Laravel.git
cd Laravel
```

Copie o arquivo `.env.example` para `.env`:

```bash
cp .env.example .env
```

Configure as variáveis de ambiente do banco de dados no `.env` se necessário. As configurações padrão já estão prontas para uso com Docker, mas caso queira:

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=task_api
DB_USERNAME=task_user
DB_PASSWORD=password123
```

## Como rodar

Suba os containers:

```bash
docker-compose up -d
```

Execute as migrations:

```bash
docker-compose exec app php artisan migrate
```

A API estará disponível em `http://localhost:8000`.

## Testes

Antes de executar os testes, é necessário criar o banco de dados de teste:

```bash
docker-compose exec mysql mysql -uroot -proot -e "CREATE DATABASE IF NOT EXISTS task_api_test;"
```

Para executar os testes unitários:

```bash
docker-compose exec app php artisan test
```

## Estrutura da API

### Base URL

```
http://localhost:8000/api
```

### Endpoints

#### Listar todas as tarefas

```
GET /api/tasks
```

Resposta (200 OK):
```json
[
    {
        "id": 1,
        "title": "Tarefa exemplo",
        "description": "Descrição da tarefa",
        "completed": false,
        "created_at": "2024-01-15 10:30:00",
        "updated_at": "2024-01-15 10:30:00"
    }
]
```

#### Criar nova tarefa

```
POST /api/tasks
```

Body:
```json
{
    "title": "Nova tarefa",
    "description": "Descrição opcional"
}
```

Resposta (201 Created):
```json
{
    "id": 1,
    "title": "Nova tarefa",
    "description": "Descrição opcional",
    "completed": false,
    "created_at": "2026-01-30 10:30:00",
    "updated_at": "2026-01-30 10:30:00"
}
```

#### Buscar tarefa por ID

```
GET /api/tasks/{id}
```

Resposta (200 OK):
```json
{
    "id": 1,
    "title": "Tarefa exemplo",
    "description": "Descrição da tarefa",
    "completed": false,
    "created_at": "2026-01-30 10:30:00",
    "updated_at": "2026-01-30 10:30:00"
}
```

#### Atualizar tarefa

```
PUT /api/tasks/{id}
```

Body (todos os campos são opcionais):
```json
{
    "title": "Tarefa atualizada",
    "description": "Nova descrição",
    "completed": true
}
```

Resposta (200 OK):
```json
{
    "id": 1,
    "title": "Tarefa atualizada",
    "description": "Nova descrição",
    "completed": true,
    "created_at": "2026-01-30 10:30:00",
    "updated_at": "2026-01-30 15:45:00"
}
```

#### Remover tarefa

```
DELETE /api/tasks/{id}
```

Resposta (204 No Content - sem corpo)

## Códigos HTTP

- 200 OK - Requisição bem-sucedida
- 201 Created - Recurso criado com sucesso
- 204 No Content - Recurso removido com sucesso
- 404 Not Found - Recurso não encontrado
- 422 Unprocessable Entity - Erro de validação
- 500 Internal Server Error - Erro interno do servidor

## Validação

### Criar tarefa (POST)

- `title` (obrigatório, string, máximo 255 caracteres)
- `description` (opcional, string)

### Atualizar tarefa (PUT)

- `title` (opcional, string, máximo 255 caracteres)
- `description` (opcional, string)
- `completed` (opcional, boolean)

## Testando a API

### Postman

Importe a collection disponível em `postman/TaskAPI.postman_collection.json` no Postman.

## Estrutura do Projeto

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       └── TaskController.php
│   ├── Requests/
│   │   ├── StoreTaskRequest.php
│   │   └── UpdateTaskRequest.php
│   └── Resources/
│       └── TaskResource.php
├── Models/
│   └── Task.php
└── Services/
    └── TaskService.php

database/
├── factories/
│   └── TaskFactory.php
└── migrations/
    └── YYYY_MM_DD_create_tasks_table.php

routes/
└── api.php

tests/
└── Unit/
    └── TaskServiceTest.php

postman/
└── TaskAPI.postman_collection.json
```

## Banco de Dados

O MySQL está configurado no Docker Compose. Credenciais padrão:

- User: task_user
- Password: password123

Para acessar via phpMyAdmin: `http://localhost:8080`

## Observações

- A API possui rate limiting de 50 requisições por minuto
- O campo `completed` sempre inicia como `false` ao criar uma tarefa
