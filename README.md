### Настойка и развертывание проекта

#### Требования к окружению

- Docker
- Утилита make для работы с Makefile

#### Настройка проекта
- Выполнить клонирование проекта командой  
  ``git clone git@github.com:Lubov-L/library.git``
- Перейти в папку проекта
- Требуется в .env внести данные для коннекта к базе данных
```
DATABASE_URL="mysql://library:library@mysql-library:3306/library?serverVersion=8.0.32&charset=utf8mb4"
```
- Выполнить команду ``make migrate``
- Выполнить команду ``make fixture``
- При необходимости удаления авторов у которых нет книг выполните команду ``make fixture``

#### Настройка проекта завершена

---

### API Документация
[Ссылка на документацию](https://documenter.getpostman.com/view/27410151/2s9YeK5Arv)
