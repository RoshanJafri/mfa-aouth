# Fully Software-Based Multi-Factor Authentication (MFA) System

A Laravel-powered, software-only MFA system that integrates OAuth login, device fingerprinting, and geolocation-based trust checks to provide secure, context-aware user authentication without relying on hardware devices.

---

## Table of Contents
1. [Introduction](#introduction)  
2. [Programming Languages](#programming-languages)  
3. [Implementations](#implementations)  
   - [Authentication Flow](#authentication-flow)  
   - [Data Storage](#data-storage)  
4. [Conclusions](#conclusions)  
5. [References](#references)  

---

## 1. Introduction
In the current digital security landscape, safeguarding user authentication is a paramount concern.  
Traditional username-password systems are increasingly vulnerable to attacks such as **phishing**, **credential stuffing**, and **brute-force attacks**.

This project presents the development of a **multi-factor authentication (MFA)** system **entirely implemented in software**, with no reliance on external hardware devices.

Built with **Laravel**, the system integrates:
- **OAuth-based social login**
- **Geolocation-based trust checks**
- **Device fingerprinting**

The design focuses on:
- **Security**
- **Usability**
- **Scalability**

It is intended for modern web applications that require **secure and context-aware user verification**.

---

## 2. Programming Languages

- **PHP (Laravel)** – Backend logic, user management, and routing.  
- **JavaScript** – Browser-side device fingerprinting and client metadata collection.  
- **HTML/CSS/Blade Templates** – UI and form rendering.  
- **SQL (MySQL)** – Persistent storage of users, authentication logs, and trust data.

---

## 3. Implementations

### 3.1 Authentication Flow

#### 1. Primary Login
- Login via **Email/Password**, **Google**, or **GitHub OAuth**.  
- OAuth handled via **Laravel Socialite**.

#### 2. Device Fingerprinting
- Unique **device ID** generated in JS (based on browser/system data).  
- Compared with trusted devices stored in DB.  
- If trusted → skip OTP.  
- If new → enforce MFA.

#### 3. Geolocation Verification
- IP captured on login.  
- Future enhancement: integrate geolocation APIs for location risk analysis.

#### 4. One-Time Password (OTP) System
- For new devices, generate **6-digit OTP** and send via email.  
- Successful OTP verification can store the device as trusted.

---

### 3.2 Data Storage

#### User Table Fields:
- `trusted_devices` – List of hashed device IDs (e.g., `fp_a7f9d3c1`).  
- `geolocation_history` – Stores historical login locations (future use).  
- `last_login_at`, `last_login_ip` – Tracks login metadata.

---

## 4. Conclusions
This **software-only MFA system** balances security and convenience by:
- Removing dependency on hardware tokens.
- Using **device fingerprinting** and **geolocation tracking**.
- Mitigating common attack vectors while maintaining a smooth UX.

The system’s **modular design** allows for easy integration of:
- **Browser behavior analysis**
- **Biometric APIs**
- **Additional security factors**

This makes it suitable for **real-world deployments** where security and user trust are critical.

---

## 5. References
1. Laravel Documentation – https://laravel.com/docs  
2. Laravel Socialite – https://laravel.com/docs/socialite  
3. OWASP Authentication Guidelines – https://owasp.org/www-project-authentication-cheat-sheet/  
4. Device Fingerprinting Concepts – https://amiunique.org  
