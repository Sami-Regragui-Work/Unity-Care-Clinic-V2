# 🏥 Unity Care Clinic V2 – Console CRUD App

A PHP console-based clinic management tool that manages patients, doctors, and departments with a simple menu-driven interface and a layered architecture (entities, repositories, services, CLI controller).[1]

## 📑 Table of Contents

-   [Project Overview](#project-overview)
-   [Tech Stack](#tech-stack)
-   [Project Structure](#project-structure)
-   [Installation & Setup](#installation-setup)
-   [Domain Model](#domain-model)
-   [Features Breakdown](#features-breakdown)
-   [Available Scripts](#available-scripts)
-   [License](#license)
-   [Contributing](#contributing)

## <h2 id="project-overview">🎯 Project Overview</h2>

This mini-project is a **console** CRUD application: all interactions happen in the terminal using textual menus and prompts (no web frontend).  
The goal is to practice clean architecture in PHP with a class-diagram-driven design while keeping the UI minimal and focused on functionality.[1]

## <h2 id="tech-stack">🛠️ Tech Stack</h2>

-   PHP 8.x (CLI mode)
-   MySQL (or MariaDB) for persistence
-   PDO or MySQLi for database access
-   Composer (optional) for autoloading and future extensions[2]

## <h2 id="project-structure">📁 Project Structure</h2>

```bash
.
├── src/
│   ├── Core/
│   │   ├── Database.php          # DB connection handling
│   │   └── ConsoleIO.php         # Helpers for reading/writing in console
│   ├── Entity/
│   │   ├── Patient.php           # Patient entity (from class diagram)
│   │   ├── Doctor.php            # Doctor entity
│   │   └── Department.php        # Department entity
│   ├── Repository/
│   │   ├── PatientRepository.php
│   │   ├── DoctorRepository.php
│   │   └── DepartmentRepository.php
│   ├── Service/
│   │   ├── PatientService.php
│   │   ├── DoctorService.php
│   │   └── DepartmentService.php
│   └── Cli/
│       └── App.php               # Main menu + CLI controllers
├── config/
│   └── db.php                    # DB credentials (host, dbname, user, pass)
├── sql/
│   ├── ddl.sql                   # Database schema
│   └── dml.sql                   # Seed data
├── composer.json                 # PSR-4 autoload (if using Composer)[web:19]
├── index.php                     # CLI entry point: php index.php
└── README.md
```

## <h2 id="installation-setup">🚀 Installation & Setup</h2>

1. **Clone the repository**

    ```bash
    git clone <repository-url>
    cd unity-care-clinic-v2-console
    ```

2. **(Optional) Install dependencies with Composer**

    ```bash
    composer install
    ```

3. **Database setup**

    - Create a MySQL database named `UCCV2_CONSOLE`.
    - Import schema:
        ```bash
        mysql -u username -p UCCV2_CONSOLE < sql/ddl.sql
        ```
    - Import seed data:
        ```bash
        mysql -u username -p UCCV2_CONSOLE < sql/dml.sql
        ```

4. **Configure database connection**

    - Edit `config/db.php`.
    - Set `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` according to your local environment.

5. **Run the console app**
    ```bash
    php index.php
    ```
    This will display the main menu (e.g. manage patients, doctors, departments, exit).

## <h2 id="domain-model">📦 Domain Model</h2>

Entities implement the class diagram in pure PHP classes, while repositories deal with SQL and services handle business rules.[3]
The console layer calls services only, keeping presentation logic separate from persistence and domain logic.[1]

**Core Entities:**

-   **Patient**

    -   Fields: id, firstName, lastName, gender, dateOfBirth, phone, email, address, createdAt.
    -   Relations: may be assigned to a doctor (which indirectly links to a department).

-   **Doctor**

    -   Fields: id, fullName, specialization, phone, email, departmentId, createdAt.
    -   Relations: belongs to one department; can have many patients.

-   **Department**
    -   Fields: id, name, location, description, createdAt.
    -   Relations: has many doctors; indirectly associated with patients.

## <h2 id="features-breakdown">🎨 Features Breakdown</h2>

**Console Menu:**

-   Text-based main menu with numbered options (e.g. 1: Patients, 2: Doctors, 3: Departments, 0: Exit).
-   Submenus for listing, creating, updating, and deleting records.

**Patients Management:**

-   List all patients with basic details.
-   Create a patient by answering prompts (name, gender, date of birth, etc.).
-   Update and delete patients by ID.

**Doctors Management:**

-   List doctors and their departments.
-   Create, update, delete doctor records.
-   Optional: assign existing patients to a doctor via their IDs.

**Departments Management:**

-   List departments.
-   Create, update, delete departments.
-   Optional safeguard: prevent deletion if doctors are still linked.

## <h2 id="available-scripts">📜 Available Scripts</h2>

```bash
php index.php         # Start the console application
composer dump-autoload# Regenerate autoload (if using Composer)
```

## <h2 id="license">📝 License</h2>

This project is open source for educational use; feel free to study, adapt, and extend it in your own learning journey.

## <h2 id="contributing">🤝 Contributing</h2>

Improvements to structure, error handling, or CLI UX are welcome; open an issue or submit a pull request describing your change clearly.
