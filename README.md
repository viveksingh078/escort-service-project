# Escort Service Project

The **Escort Service Project** is a modern, secure, and fully-featured web application built using the **Laravel framework**. It is designed to manage escort profiles, client interactions, real-time communication, subscription plans, and admin controls — all within a responsive and user-friendly interface.

## 🚀 Features

### 🔐 User Management
- Escort registration and secure login system
- Profile creation with photo uploads, ID verification, and bio details
- Separate dashboard for escorts, clients, and admin

### 💬 Real-Time Communication
- Integrated Peer.js for real-time video and audio calls
- Instant messaging with AJAX and WebSockets
- Typing indicators, read receipts, and online/offline user status

### 💳 Subscription Module
- Admin-side control for creating and managing plans
- Free, Essential, and Premium plans with customizable features
- Secure payment gateway integration (optional)

### 🧩 Admin Panel
- Manage users, subscriptions, and settings from a single dashboard
- View and approve escort verification requests
- Update system configuration, roles, and permissions

### 🌐 Frontend
- Elegant UI built with Bootstrap 5 and Blade templates
- Mobile-friendly and fully responsive design
- Smooth animations and interactive forms

## 🛠️ Tech Stack

- Backend: Laravel (PHP)
- Frontend: HTML5, CSS3, JavaScript, Bootstrap
- Database: MySQL
- Real-Time Engine: Peer.js, WebSockets
- Version Control: Git & GitHub

## 📦 Installation

Clone the repository:

git clone https://github.com/viveksingh078/escort-service-project.git

Go to the project directory:

cd escort-service-project

Install dependencies:

composer install

Copy environment file:

cp .env.example .env

Generate application key:

php artisan key:generate

Run migrations:

php artisan migrate

Start the development server:

php artisan serve
