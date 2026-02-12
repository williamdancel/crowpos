# 🐦 CrowPOS

> A modern, desktop-ready Point of Sale (POS) system built with Laravel
> & Livewire.
> Designed for small businesses that need speed, clarity, and
> reliability.

CrowPOS is an offline-capable desktop POS that helps you manage categories and items, run fast checkouts, and view sales reports without the clutter. Perfect for small shops, services, food, retail, and more.

------------------------------------------------------------------------

## 📸 Project Overview

CrowPOS demonstrates:

-   Secure authentication using Laravel Fortify
-   User registration & password reset flow
-   Livewire-powered interactive POS interface
-   Sales lifecycle & receipt generation
-   Feature-based routing structure
-   Desktop deployment capability via NativePHP
-   Automated feature testing using Pest

------------------------------------------------------------------------

## 🧠 Architecture Decisions

### Stable Dashboard Entry Point

To prevent test fragility and isolate UI complexity:

``` php
Route::view('/pos', 'pos')->name('dashboard');
Route::get('/pos/app', PosIndex::class)->name('pos.index');
```

This ensures:

-   Authentication tests remain stable
-   POS engine stays modular
-   Clear separation of concerns

------------------------------------------------------------------------

## 🚀 Core Features

-   🔐 User Registration & Authentication
-   🔄 Password Reset Flow
-   🛒 POS Transaction Interface
-   📦 Product & Category Management
-   💰 Sales Tracking
-   🧾 Receipt View
-   🧪 Automated Feature Testing
-   🖥 Desktop Deployment Ready

------------------------------------------------------------------------

## 🛠 Tech Stack

  Layer               Technology
  ------------------- --------------------
  Backend             Laravel
  UI                  Livewire
  Authentication      Fortify
  Styling             TailwindCSS
  Database            SQLite
  Testing             Pest
  Desktop Packaging   NativePHP

------------------------------------------------------------------------

## 📦 Installation

``` bash
git clone https://github.com/williamdancel/crowpos.git
cd crowpos
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

Then register a user through the application.

------------------------------------------------------------------------

## 🖥 Desktop Build (Optional)

CrowPOS can be packaged as a desktop application using NativePHP.

``` bash
php artisan native:build
```

This allows CrowPOS to run as a standalone desktop POS system.

------------------------------------------------------------------------

## 🧪 Running Tests

``` bash
./vendor/bin/pest
```

Includes:

-   Authentication tests
-   Registration flow
-   Password reset flow
-   Settings management

------------------------------------------------------------------------

## 🎯 Intended Use Case

CrowPOS is designed for:

-   Small retail stores
-   Cafés
-   Local businesses
-   Desktop-first POS environments

------------------------------------------------------------------------

## 📈 What This Project Demonstrates

-   Full-stack Laravel development
-   Livewire component architecture
-   Secure authentication systems
-   Database modeling
-   Feature test strategy
-   Debugging & refactoring workflow
-   Deployment flexibility (Web + Desktop)

------------------------------------------------------------------------

## 👨‍💻 Developer

**William Harry A. Dancel**
Full-Stack PHP Developer

-   7+ years PHP experience
-   Laravel / CodeIgniter / VueJS / Livewire / NativePHP
-   REST APIs & business systems

🔗 Github: https://github.com/williamdancel
🔗 Portfolio: https://whadancel.dev/

------------------------------------------------------------------------

© 2026 CrowPOS. All rights reserved.
