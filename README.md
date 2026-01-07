# Renting App

A simple Laravel backend API for managing basic rental app logic & data, with an admin dashboard website to handle verifying users/properties and general administration.

Built with **Laravel 12** and **Sanctum** for token-based authentication.

## API (Flutter App) Endpoints

### Auth (Public)

| Method | Path                 | Description             |
| ------ | -------------------- | ----------------------- |
| POST   | `/api/auth/register` | Register a new user     |
| POST   | `/api/auth/login`    | Login and get API token |

### Public Read-Only Resources

| Method | Path                                         | Description                            |
| ------ | -------------------------------------------- | -------------------------------------- |
| GET    | `/api/users/{username}`                      | View a user public profile by username |
| GET    | `/api/governorates`                          | List governorates                      |
| GET    | `/api/governorates/{governorate}`            | Get governorate by ID                  |
| GET    | `/api/properties/{property}`                 | Show single property                   |
| GET    | `/api/properties/{property}/reserved-periods`| Get reserved periods for a property    |
| GET    | `/api/properties/{property}/reviews`         | List all reviews for a property        |

### Protected Routes (Sanctum)

> All routes below require: `Authorization: Bearer <token>`

#### Auth

| Method | Path                   | Description                     |
| ------ | ---------------------- | ------------------------------- |
| POST   | `/api/auth/logout`     | Logout and revoke current token |
| POST   | `/api/auth/logout-all` | Logout and revoke all tokens    |

#### User (Authenticated User)

| Method | Path                               | Description                                      |
| ------ | ---------------------------------- | ------------------------------------------------ |
| GET    | `/api/user/my-profile`             | Get authenticated user profile                   |
| PUT    | `/api/user/update`                 | Update user profile                              |
| PUT    | `/api/user/phone`                  | Change phone number                              |
| PUT    | `/api/user/password`               | Change password                                  |
| DELETE | `/api/user/delete`                 | Delete account                                   |
| GET | `/api/user/is-verified`                 | Check account verification                                   |
| GET    | `/api/user/properties`             | List authenticated user's properties             |
| GET    | `/api/user/properties/{property}`  | Show a single property owned by the user         |

#### Notifications

| Method | Path                               | Description                                      |
| ------ | ---------------------------------- | ------------------------------------------------ |
| POST    | `/api/push/token`             | Store FCM token                   |
| DELETE    | `/api/push/token`                 | Delete FCM token                              |

#### Properties

| Method | Path                                      | Description                                      |
| ------ | ----------------------------------------- | ------------------------------------------------ |
| GET    | `/api/properties`                         | List properties                                  |
| POST   | `/api/properties`                         | Create property                                  |
| PUT    | `/api/properties/{property}`              | Update property                                  |
| DELETE | `/api/properties/{property}`              | Delete property                                  |
| GET    | `/api/properties/{property}/reservations` | List reservations for a property (landlord view) |
| POST   | `/api/properties/{property}/reservations` | Create a reservation for a property              |

#### Property Photos

| Method | Path                                                | Description              |
| ------ | --------------------------------------------------- | ------------------------ |
| POST   | `/api/properties/{property}/photos`                 | Upload property photo(s) |
| DELETE | `/api/properties/{property}/photos/{propertyPhoto}` | Delete property photo    |

#### Favorites

| Method | Path                               | Description                           |
| ------ | ---------------------------------- | ------------------------------------- |
| GET    | `/api/favorites`                   | List authenticated user favorites     |
| GET    | `/api/favorites/{property}`        | Get a single favorite (by property)   |
| POST   | `/api/favorites/{property}/toggle` | Toggle favorite for a property        |

#### Reservations

| Method | Path                                 | Description                              |
| ------ | ------------------------------------ | ---------------------------------------- |
| GET    | `/api/reservations`                  | List authenticated tenant reservations   |
| PUT    | `/api/reservations/{reservation}`    | Update reservation                       |
| POST   | `/api/reservations/{reservation}/approve` | Approve reservation                  |
| POST   | `/api/reservations/{reservation}/cancel`  | Cancel reservation                   |
| POST   | `/api/reservations/{reservation}/review`  | Add a review for a reservation       |

#### Reviews

| Method | Path                      | Description        |
| ------ | ------------------------- | ------------------ |
| GET    | `/api/reviews/{review}`   | Show single review |
| PUT    | `/api/reviews/{review}`   | Update review      |
| DELETE | `/api/reviews/{review}`   | Delete review      |

---

## Web (Admin Dashboard) Endpoints

> All routes below require admin authentication (Laravel session auth).

| Method | Path      | Description                       |
| ------ | --------- | --------------------------------- |
| GET    | `/login`  | Show admin login page             |
| POST   | `/login`  | Authenticate admin                |
| POST   | `/logout` | Logout admin                      |
| GET    | `/`       | Dashboard home (overview & stats) |

### Users Management

| Method | Path                   | Description              |
| ------ | ---------------------- | ------------------------ |
| GET    | `/users`               | List all users           |
| GET    | `/users/{user}`        | Show single user details |
| POST   | `/users/{user}/verify` | Verify a user account    |

### Properties Management

| Method | Path                            | Description                  |
| ------ | ------------------------------- | ---------------------------- |
| GET    | `/properties`                   | List all properties          |
| GET    | `/properties/{property}`        | Show single property details |
| POST   | `/properties/{property}/verify` | Verify a property            |

### Reservations Management

| Method | Path                                   | Description                      |
| ------ | -------------------------------------- | -------------------------------- |
| GET    | `/reservations`                        | List all reservations            |
| GET    | `/reservations/{reservation}`          | Show single reservation details  |
| POST   | `/reservations/{reservation}/cancel`   | Cancel a reservation             |

---

## Database Diagram

![Database Diagram](database/diagrams/renting_app_database.png)
