# Projeto — Importador de Pedidos (PDF)

Aplicação Laravel que importa pedidos a partir de arquivos PDF: extrai número do pedido, cliente e valor e exibe em uma lista.

## Requisitos

- Docker e Docker Compose
- (Opcional) PHP 8.2+ e Composer, se quiser rodar fora do Docker

## Rodar do zero com Docker

Todos os comandos abaixo na raiz do projeto: `/var/www/Projeto`.

### 1. Criar arquivo de ambiente

```bash
cp src/.env.example src/.env
```

### 2. Subir os containers (2 serviços: app + banco)

Na **primeira vez** ou após mudar código PHP, faça o build da imagem:

```bash
docker compose up -d --build
```

Depois, para só subir: `docker compose up -d`. Aguarde o PostgreSQL e o app iniciarem.

### 3. Rodar as migrações (uma vez)

```bash
docker compose exec app php artisan migrate --force
```

### 4. Acessar a aplicação

Abra no navegador: **http://localhost:8000**

- **Upload:** escolha um PDF e clique em "Enviar".
- A aplicação tenta extrair do texto: **Pedido Nº X**, **Cliente: Nome**, **Total: R$ X,XX**.

## Estrutura do projeto

- `src/` — aplicação Laravel (app, config, routes, views, etc.)
- `Dockerfile` — imagem com o código em `/var/www`, `composer install` e `key:generate` (sem volume; alterações no código exigem `docker compose up -d --build`)
- `docker-compose.yml` — **2 containers:** app (PHP, porta 8000) e PostgreSQL (5432)

## Variáveis de ambiente (Docker)

O `.env.example` já está ajustado para o Docker:

- `DB_CONNECTION=pgsql`
- `DB_HOST=db`
- `DB_DATABASE=laravel`
- `DB_USERNAME=laravel`
- `DB_PASSWORD=secret`

## Comandos úteis

```bash
# Ver logs do PHP
docker compose logs -f app

# Parar tudo
docker compose down

# Recriar migrações (cuidado: apaga dados)
docker compose exec app php artisan migrate:fresh --force
```

## Formato esperado no PDF

A extração usa expressões regulares. Exemplos de texto que funcionam:

- `Pedido Nº 12345` ou `Pedido N° 12345`
- `Cliente: Nome do Cliente`
- `Total: R$ 1.234,56` ou `Total: 1234.56`

PDFs com outro layout podem não preencher todos os campos; o texto bruto fica salvo em `texto_bruto` para ajustes futuros.
