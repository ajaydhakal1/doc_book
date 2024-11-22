# **Appointment Management System**

A user-friendly appointment management system tailored for doctors and patients to manage appointments efficiently. This project allows patients to book appointments with their preferred doctors, while doctors can organize their schedules seamlessly.

<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

---

## **Features**

-   **User Authentication**: Register and log in securely.
-   **Role Management**: Distinct roles for doctors and patients.
-   **Appointment Booking**: Patients can book appointments with doctors.
-   **Doctor Schedule Management**: Doctors can view and manage their appointments.
-   **Responsive Design**: Optimized for desktops and mobile devices.

---

## **Tech Stack**

-   **Backend**: Laravel 11
-   **Frontend**: Blade Templates, TailwindCSS
-   **Database**: SQLite
-   **Authentication**: Laravel Breeze
-   **Version Control**: Git & GitHub

---

### Minimal Viable Product (MVP)

-   **User Registration and Authentication:**
    -   Users (patients, doctors, admin) must be able to register, log in, and authenticate.
-   **Patient Appointment Booking:**
    -   Patients can view available time slots of doctors and book appointments with the related doctor.
-   **Doctor Schedule Management:**
    -   Doctors can manage their availability by creating and updating schedules and their appointments by modifying the status of the appointment.
-   **Reviews:**
    -   Doctors can leave reviews on patients' medical health after an appointment is completed.
-   **Appointment Status:**
    -   Patients can view,edit and delete their appointments only.
-   **Admin Role:**

    -   Admins can manage users, appointments, schedules, roles, permissions and reviews.

    ***

## Usage

## **For Patients**

-   **Register/Login.**
-   **View all doctors.**
-   **Book appointments based on availability.**

## **For Doctors**

-   **Register/Login.**
-   **View all booked appointments.**
-   **Manage your schedule effectively.**

---

## Database Schema

The database schema is designed to support the core features of the appointment system. Below is an outline of the schema.

### 1. User Table

**Table Name**: `users`

| Field      | Type    | Description                         |
| ---------- | ------- | ----------------------------------- |
| `id`       | INT     | Primary Key, unique user identifier |
| `name`     | VARCHAR | Name of the user                    |
| `email`    | VARCHAR | Email address of the user           |
| `password` | VARCHAR | User password                       |

-   **Roles**:
    -   `Admin`
    -   `Manager`
    -   `Patient`
    -   `Doctor`

### 2 Patients Table

**Table Name**: `patients`

| Field     | Type   | Description                                     |
| --------- | ------ | ----------------------------------------------- |
| `id`      | INT    | Primary Key, unique patient identifier          |
| `user_id` | INT    | Foreign Key (users.id), links to the User table |
| `phone`   | String | Stores patient's phone number.                  |
| `address` | String | Store patient's address.                        |
| `age`     | INT    | Store patient's age.                            |
| `gender`  | Enum   | Store patient's gender.                         |

### 3 Doctor Table

**Table Name**: `doctors`

| Field           | Type   | Description                                                    |
| --------------- | ------ | -------------------------------------------------------------- |
| `id`            | INT    | Primary Key, unique doctor identifier                          |
| `user_id`       | INT    | Foreign Key (users.id), links to the User table                |
| `speciality_id` | INT    | Foreign Key(specialities.id), links to the Specialities Table. |
| `phone`         | String | Store doctor's phone number.                                   |

### 4 Reviews Table

**Table Name**: `reviews`

| Field             | Type | Description                                                   |
| ----------------- | ---- | ------------------------------------------------------------- |
| `id`              | INT  | Primary Key, unique review identifier                         |
| `appointments_id` | INT  | Foreign Key (appointments.id), links to the Appointment table |

### 5 Patient History Table

**Table Name**: `patient_history`

| Field            | Type | Description                                                   |
| ---------------- | ---- | ------------------------------------------------------------- |
| `id`             | INT  | Primary Key, unique record identifier                         |
| `patient_id`     | INT  | Foreign Key (patients.id), links to the Patient table         |
| `appointment_id` | INT  | Foreign Key (appointments.id), links to the Appointment table |
| `review_id`      | INT  | Foreign Key (reviews.id), links to the Review table           |

### 6 Schedule Table

**Table Name**: `schedule`

| Field        | Type | Description                                         |
| ------------ | ---- | --------------------------------------------------- |
| `id`         | INT  | Primary Key, unique schedule identifier             |
| `doctor_id`  | INT  | Foreign Key (doctors.id), links to the Doctor table |
| `date`       | Date | Date of the schedule                                |
| `start_time` | Time | Starting time of the schedule                       |
| `end_time`   | Time | Ending time of the schedule                         |

### 7 Appointments Table

**Table Name**: `appointments`

| Field        | Type                                | Description                                           |
| ------------ | ----------------------------------- | ----------------------------------------------------- |
| `id`         | INT                                 | Primary Key, unique appointment identifier            |
| `patient_id` | INT                                 | Foreign Key (patients.id), links to the Patient table |
| `doctor_id`  | INT                                 | Foreign Key (doctors.id), links to the Doctor table   |
| `disease`    | String                              | Store the patient's disease or problem                |
| `date`       | DATE                                | Date of the appointment                               |
| `start_time` | Time                                | Starting time of the appointment                      |
| `end_time`   | Time                                | Ending time of the appointment                        |
| `review`     | TEXT                                | Optional review provided by the patient               |
| `status`     | ENUM(booked, incomplete, completed) | Store the status of the appointment.                  |

## `Full documentation coming soon...`