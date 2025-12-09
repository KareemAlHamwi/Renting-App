# Renting‑App

A simple Laravel backend API for managing basic rental app logic & data,
with an admin dashboard to handle verifing users and more.  

Built with **Laravel 12** and **Sanctum** for token-based authentication.

## API Endpoints

| Method | Path | Description |
|--------|------|-------------|
| POST   | `/api/auth/register`       | Register a new user |
| POST   | `/api/auth/login`          | Login and get API token |
| POST   | `/api/auth/logout`         | Logout and revoke tokens |
|
| GET    | `/api/user/show`           | Show user details |
| POST   | `/api/user/update`         | Update user profile |
| POST   | `/api/user/change-phone-number` | Change user phone number |
| POST   | `/api/user/change-password` | Change user password |
| DELETE | `/api/user/destroy`        | Delete user account |

# Database Diagram

![Database Diagram](database/diagrams/renting_app_database.png)
