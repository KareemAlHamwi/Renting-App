# Renting App

A simple Laravel backend API for managing basic rental app logic & data, with an admin dashboard website to handle verifying users/properties and general administration.

Built with **Laravel 12** and **Sanctum** for token-based authentication.

## API (Flutter App) Endpoints

### Auth (Public)

| Method | Path | Description |
|--------|------|-------------|
| POST | `/api/auth/register` | Register a new user |
| POST | `/api/auth/login` | Login and get API token |

### Public Read-Only Resources

| Method | Path | Description |
|--------|------|-------------|
| GET | `/api/users/{username}` | View a user public profile by username |
| GET | `/api/governorates` | List governorates |
| GET | `/api/governorates/{id}` | Get governorate by ID |
| GET | `/api/properties` | List properties |
| GET | `/api/properties/{id}` | Show single property |

### Protected Routes (Sanctum)

> All routes below require: `Authorization: Bearer <token>`

#### Auth

| Method | Path | Description |
|--------|------|-------------|
| POST | `/api/auth/logout` | Logout and revoke current token |
| POST | `/api/auth/logout-all` | Logout and revoke all tokens |

#### User (Authenticated User)

| Method | Path | Description |
|--------|------|-------------|
| GET | `/api/user/my-profile` | Get authenticated user profile |
| PUT | `/api/user/update` | Update user profile |
| PUT | `/api/user/phone` | Change phone number |
| PUT | `/api/user/password` | Change password |
| DELETE | `/api/user/delete` | Delete account |

#### Properties (Owner)

| Method | Path | Description |
|--------|------|-------------|
| POST | `/api/properties` | Create property |
| PUT | `/api/properties/{property}` | Update property |
| DELETE | `/api/properties/{property}` | Delete property |

#### Property Photos

| Method | Path | Description |
|--------|------|-------------|
| POST | `/api/properties/{propertyId}/photos` | Upload property photo(s) |
| DELETE | `/api/properties/{propertyId}/photos/{id}` | Delete property photo |

---

## Web (Admin Dashboard) Endpoints

> All routes below require admin authentication (Laravel session auth).

| Method | Path | Description |
|--------|------|-------------|
| GET | `/login` | Show admin login page |
| POST | `/login` | Authenticate admin |
| POST | `/logout` | Logout admin |
| GET | `/` | Dashboard home (overview & stats) |

### Users Management

| Method | Path | Description |
|--------|------|-------------|
| GET | `/users` | List all users |
| GET | `/users/{user}` | Show single user details |
| POST | `/users/{user}/verify` | Verify a user account |

### Properties Management

| Method | Path | Description |
|--------|------|-------------|
| GET | `/properties` | List all properties |
| GET | `/properties/{property}` | Show single property details |
| POST | `/properties/{property}/verify` | Verify a property |

---

## Database Diagram

![Database Diagram](database/diagrams/renting_app_database.png)
