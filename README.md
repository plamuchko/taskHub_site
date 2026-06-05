# TaskHub System

Проектът **TaskHub System** е контейнеризирана уеб-базирана Kanban система, реализирана като трислойна архитектура с отделни услуги: `frontend`, `backend` и `db`.

Целта е да демонстрира добри практики при разделяне на отговорностите, контейнеризация, оркестрация с Docker Compose и сигурна комуникация между компонентите.

## Бърз преглед

- Фронтенд: статични HTML страници (интерфейс, логин, регистрация) обслужвани от Nginx на порт `8080`.
- Бекенд: PHP REST API (Apache, PHP 8.2) изложено на порт `8000`.
- База данни: MySQL 8.0 с инициализационен скрипт `db/init.sql` и персистентен volume.
- Оркестрация: `compose.yml` управлява мрежите, томовете и стартирането на всички услуги.

## Какво демонстрира проектът

- Separation of Concerns — отделни папки и контейнери за Frontend, Backend и Database.
- Контейнеризация с Docker и създаване на образи чрез `Dockerfile`.
- Docker Compose за лесно стартиране/спиране и конфигуриране на мрежи и томове.
- CORS защита и асинхронни заявки от фронтенда към бекенда чрез Fetch API.
- Изолация на вътрешната комуникация (бекенд ⇄ база) в собствен Docker Network.

## Структура на проекта

taskhub_system/
- `frontend/`
	- `index.html` — Канбан табло и основна функционалност
	- `login.html` — Форма за вход
	- `register.html` — Форма за регистрация
- `backend/`
	- `auth.php` — REST крайна точка за вход и регистрация
	- `tasks.php` — REST крайна точка за управление на задачи (CRUD)
	- `Dockerfile` — билд инструкции за PHP + Apache контейнера
- `db/`
	- `init.sql` — скрипт за създаване на базата и начални данни
- `compose.yml` — конфигурация за Docker Compose
- `README.md` — тази документация

## Услуги и роли

### Frontend

- Технологии: HTML5, CSS3, Bootstrap 5, Vanilla JS
- Работи в контейнер на `nginx:alpine`, публичен порт `8080`.
- Събира входни данни от потребителя и ги изпраща към бекенда чрез `fetch` (JSON).
- При успешна автентикация съхранява сесията в `localStorage`.

### Backend

- PHP 8.2 + Apache, билднат чрез `backend/Dockerfile`.
- Изложен на порт `8000` за външен достъп.
- `auth.php` поддържа операции `login` и `register` — паролите се запечатват с `password_hash()`.
- `tasks.php` поддържа извличане (`GET`) и записване (`POST`) на задачи; новите задачи получават статус `"todo"` по подразбиране.
- Внедрени CORS хедъри за позволяване на заявки от фронтенда.

### Database (`db`)

- MySQL 8.0 използван като официален образ.
- При стартиране изпълнява `db/init.sql` за създаване на базата `taskhub_db`, таблиците `users` и `tasks` и примерни данни.
- Данните се запазват в Docker volume `taskhub_data`, за да останат постоянни при рестарт.

## Комуникация между услугите

- Външна: Браузър (порт `8080`) ⇄ Backend API (порт `8000`) — различен origin, затова бекендът връща CORS хедъри.
- Вътрешна: Backend ⇄ Database — комуникация в изолирана Docker мрежа `taskhub_network`; бекендът се свързва с хоста `db` на порт `3306`.

## Стартиране (Linux / WSL / PowerShell)

1. Отворете терминал и навигирайте до корена на проекта:

```bash
cd /mnt/c/Users/user/Desktop/taskhub_system
```

2. Изграждане и стартиране със Docker Compose:

```bash
sudo docker compose up --build
```

3. Отворете браузър и посетете:

http://localhost:8080/login.html

4. За спиране и почистване:

```bash
sudo docker compose down
```

Бележка: в Windows PowerShell можете да изпълнявате `docker compose up --build` без `sudo`.

## Примерен изход (логове)

```
[+] Running 4/4
 ✔ Network taskhub_network      Created
 ✔ Container taskhub_db         Created
 ✔ Container taskhub_backend    Created
 ✔ Container taskhub_frontend   Created
taskhub_db        | [System] Ready for start up connections on port 3306
taskhub_backend   | AH00558: apache2: Could not reliably determine the server's fully qualified domain name
taskhub_frontend  | /docker-entrypoint.sh: Launching Nginx...
```

## Връзки

- GitHub: https://github.com/plamuchko/taskhub_system
- Docker Hub (пример): https://hub.docker.com/r/plamendraganov22322/taskhub-backend
