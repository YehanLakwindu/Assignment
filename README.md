# Weather Dashboard with Auth0 MFA Login

A secure, Laravel-based Weather Dashboard application featuring user authentication via Auth0 with Multi-Factor Authentication (MFA). Users authenticate with email/password and confirm identity using an MFA verification code sent via email.

---

## Table of Contents

-   [Project Overview](#project-overview)
-   [Features](#features)
-   [Tech Stack](#tech-stack)
-   [Prerequisites](#prerequisites)
-   [Setup Instructions](#setup-instructions)
-   [Configuration](#configuration)
-   [Running the Application](#running-the-application)
-   [Deployment](#deployment)
-   [Troubleshooting](#troubleshooting)
-   [Frequently Asked Questions (FAQs)](#frequently-asked-questions-faqs)
-   [License](#license)
-   [Contact](#contact)

---

## Project Overview

This project demonstrates how to integrate Laravel with Auth0 for authentication while implementing Multi-Factor Authentication (MFA) via email. The app restricts access to weather data until users authenticate securely, ensuring data privacy.

---

## Features

-   User authentication via Auth0 OAuth 2.0
-   Multi-Factor Authentication (MFA) enabled through email verification
-   Email selection for receiving MFA codes
-   Tailwind CSS-based responsive UI
-   Session management with login/logout flow
-   Protected weather dashboard route accessible only after login

---

## Tech Stack

| Technology     | Description              |
| -------------- | ------------------------ |
| Laravel        | PHP framework            |
| PHP            | Backend language         |
| Auth0          | Authentication platform  |
| Tailwind CSS   | Styling & responsive UI  |
| JavaScript     | Frontend interaction     |
| MySQL / SQLite | Database (if applicable) |

---

## Prerequisites

-   PHP >= 8.0
-   Composer
-   Laravel 9.x or later
-   Auth0 Account
-   Node.js & npm (optional for frontend tooling)
-   Web server (Apache, Nginx) or Laravel Sail / Valet / Homestead for development

---

## Setup Instructions

1. **Clone the repository:**

    ```bash
    git clone https://github.com/YehanLakwindu/weather-dashboard.git
    cd weather-dashboard
    ```
